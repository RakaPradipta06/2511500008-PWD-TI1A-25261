<?php
session_start();
require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';

# Cek method form, hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('index.php#biodata');
}

# Ambil dan bersihkan nilai dari form biodata mahasiswa
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

# Validasi NIM (harus angka, 8-10 digit)
if (empty($nim)) {
    $errors[] = 'NIM wajib diisi.';
} elseif (!preg_match('/^[0-9]{8,10}$/', $nim)) {
    $errors[] = 'NIM harus terdiri dari 8-10 digit angka.';
}

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
    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $tanggal_lahir)) {
        # Format DD-MM-YYYY diubah menjadi YYYY-MM-DD
        $date_parts = explode('-', $tanggal_lahir);
        $tanggal_lahir = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    } else {
        $errors[] = 'Format tanggal lahir tidak valid. Gunakan format YYYY-MM-DD.';
    }
}

# Validasi Hobi
if (empty($hobi)) {
    $errors[] = 'Hobi wajib diisi.';
}

# Validasi Nama Orang Tua
if (empty($nama_ortu)) {
    $errors[] = 'Nama orang tua wajib diisi.';
}

# Cek duplikasi NIM
if (empty($errors)) {
    $check_nim = "SELECT cmid FROM tbl_mahasiswa WHERE cnim = ?";
    $stmt_check = mysqli_prepare($conn, $check_nim);
    mysqli_stmt_bind_param($stmt_check, "s", $nim);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $errors[] = 'NIM sudah terdaftar.';
    }
    mysqli_stmt_close($stmt_check);
}

# Jika ada error, simpan nilai lama dan redirect
if (!empty($errors)) {
    $_SESSION['old_biodata'] = [
        'nim' => $nim,
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
    
    $_SESSION['flash_error'] = implode('<br>', $errors);
    redirect_ke('index.php#biodata');
}

# Simpan data ke session untuk ditampilkan di section about
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

# Insert data ke database menggunakan prepared statement
$sql = "INSERT INTO tbl_mahasiswa (cnim, cnama, ctempat_lahir, ctanggal_lahir, 
        chobi, cpasangan, cpekerjaan, cnama_ortu, cnama_kakak, cnama_adik) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
    redirect_ke('index.php#biodata');
}

# Bind parameter dan eksekusi
mysqli_stmt_bind_param($stmt, "ssssssssss", 
    $nim, $nama, $tempat_lahir, $tanggal_lahir, 
    $hobi, $pasangan, $pekerjaan, $nama_ortu, 
    $nama_kakak, $nama_adik);

if (mysqli_stmt_execute($stmt)) {
    unset($_SESSION['old_biodata']);
    $_SESSION['flash_sukses'] = 'Data biodata mahasiswa berhasil disimpan!';
} else {
    $_SESSION['flash_error'] = 'Data gagal disimpan. Silakan coba lagi.';
    $_SESSION['old_biodata'] = [
        'nim' => $nim,
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
}

mysqli_stmt_close($stmt);

# Konsep PRG: Redirect ke halaman biodata
redirect_ke('index.php#biodata');
?>