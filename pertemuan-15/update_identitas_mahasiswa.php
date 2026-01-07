<?php
session_start();
require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';

# Cek method form, hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error_mhs'] = 'Akses tidak valid.';
    redirect_ke('read_identitas_mahasiswa.php');
}

# Validasi cmid dari POST
$cmid = filter_input(INPUT_POST, 'cmid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$cmid) {
    $_SESSION['flash_error_edit'] = 'ID Mahasiswa tidak valid.';
    redirect_ke('edit_identitas_mahasiswa.php?cmid=' . (int)$_POST['cmid']);
}

# Ambil dan bersihkan nilai dari form
$nim = bersihkan($_POST['txtNim'] ?? '');
$nama = bersihkan($_POST['txtNmLengkap'] ?? '');
$tempat_lahir = bersihkan($_POST['txtT4Lhr'] ?? '');
$tanggal_lahir = bersihkan($_POST['txtTglLhr'] ?? '');
$hobi = bersihkan($_POST['txtHobi'] ?? '');
$pasangan = bersihkan($_POST['txtPasangan'] ?? '');
$pekerjaan = bersihkan($_POST['txtKerja'] ?? '');
$nama_ortu = bersihkan($_POST['txtNmOrtu'] ?? '');
$nama_kakak = bersihkan($_POST['txtNmKakak'] ?? '');
$nama_adik = bersihkan($_POST['txtNmAdik'] ?? '');

# Validasi input
$errors = [];

# Validasi Nama
if (empty($nama)) {
    $errors[] = 'Nama lengkap wajib diisi.';
} elseif (strlen($nama) < 2) {
    $errors[] = 'Nama minimal 2 karakter.';
}

# Validasi Tempat Lahir
if (empty($tempat_lahir)) {
    $errors[] = 'Tempat lahir wajib diisi.';
}

# Validasi Tanggal Lahir
if (empty($tanggal_lahir)) {
    $errors[] = 'Tanggal lahir wajib diisi.';
} elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_lahir)) {
    $errors[] = 'Format tanggal lahir tidak valid. Gunakan format YYYY-MM-DD.';
}

# Validasi Hobi
if (empty($hobi)) {
    $errors[] = 'Hobi wajib diisi.';
}

# Validasi Nama Orang Tua
if (empty($nama_ortu)) {
    $errors[] = 'Nama orang tua wajib diisi.';
}

# Jika ada error, simpan nilai lama dan redirect
if (!empty($errors)) {
    $_SESSION['old_edit'] = [
        'nama' => $nama,
        'tempat_lahir' => $tempat_lahir,
        'tanggal_lahir' => $tanggal_lahir,
        'hobi' => $hobi,
        'pasangan' => $pasangan,
        'pekerjaan' => $pekerjaan,
        'nama_ortu' => $nama_ortu,
        'nama_kakak' => $nama_kakak,
        'nama_adik' => $nama_adik
    ];
    
    $_SESSION['flash_error_edit'] = implode('<br>', $errors);
    redirect_ke('edit_identitas_mahasiswa.php?cmid=' . $cmid);
}

# Update data di database menggunakan prepared statement
$sql = "UPDATE tbl_identitas_mahasiswa SET 
        cnama = ?, 
        ctempat_lahir = ?, 
        ctanggal_lahir = ?, 
        chobi = ?, 
        cpasangan = ?, 
        cpekerjaan = ?, 
        cnama_ortu = ?, 
        cnama_kakak = ?, 
        cnama_adik = ? 
        WHERE cmid = ?";
        
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION['flash_error_edit'] = 'Terjadi kesalahan sistem (prepare gagal).';
    redirect_ke('edit_identitas_mahasiswa.php?cmid=' . $cmid);
}

# Bind parameter dan eksekusi
mysqli_stmt_bind_param($stmt, "sssssssssi", 
    $nama, $tempat_lahir, $tanggal_lahir, $hobi, 
    $pasangan, $pekerjaan, $nama_ortu, $nama_kakak, 
    $nama_adik, $cmid);

if (mysqli_stmt_execute($stmt)) {
    unset($_SESSION['old_edit']);
    $_SESSION['flash_sukses_mhs'] = 'Data mahasiswa berhasil diperbarui!';
    
    # Update session biodata jika data yang diupdate adalah data yang sedang ditampilkan
    if (isset($_SESSION['biodata']) && $_SESSION['biodata']['nim'] == $nim) {
        $_SESSION['biodata'] = [
            "nim" => $nim,
            "nama" => $nama,
            "tempat" => $tempat_lahir,
            "tanggal" => $tanggal_lahir,
            "hobi" => $hobi,
            "pasangan" => $pasangan,
            "pekerjaan" => $pekerjaan,
            "ortu" => $nama_ortu,
            "kakak" => $nama_kakak,
            "adik" => $nama_adik
        ];
    }
    
    # Konsep PRG: Redirect ke halaman data mahasiswa
    redirect_ke('read_identitas_mahasiswa.php');
} else {
    $_SESSION['old_edit'] = [
        'nama' => $nama,
        'tempat_lahir' => $tempat_lahir,
        'tanggal_lahir' => $tanggal_lahir,
        'hobi' => $hobi,
        'pasangan' => $pasangan,
        'pekerjaan' => $pekerjaan,
        'nama_ortu' => $nama_ortu,
        'nama_kakak' => $nama_kakak,
        'nama_adik' => $nama_adik
    ];
    
    $_SESSION['flash_error_edit'] = 'Data gagal diperbarui. Silakan coba lagi.';
    redirect_ke('edit_identitas_mahasiswa.php?cmid=' . $cmid);
}

mysqli_stmt_close($stmt);
?>