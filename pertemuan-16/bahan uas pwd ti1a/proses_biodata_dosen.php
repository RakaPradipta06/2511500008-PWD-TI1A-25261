<?php
session_start();
require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';

# Cek method form, hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('index.php#biodata');
}

# Ambil dan bersihkan nilai dari form biodata dosen
$kode_dosen = bersihkan($_POST['txtKodeDos'] ?? '');
$nama_dosen = bersihkan($_POST['txtNmDosen'] ?? '');
$alamat_rumah = bersihkan($_POST['txtAlRmh'] ?? '');
$tanggal_jadi_dosen = bersihkan($_POST['txtTglDosen'] ?? '');
$jja_dosen = bersihkan($_POST['txtJJA'] ?? '');
$homebase_prodi = bersihkan($_POST['txtProdi'] ?? '');
$nomor_hp = bersihkan($_POST['txtNoHP'] ?? '');
$nama_pasangan = bersihkan($_POST['txtNamaPasangan'] ?? '');
$nama_anak = bersihkan($_POST['txtNmAnak'] ?? '');
$bidang_ilmu_dosen = bersihkan($_POST['txtBidangIlmu'] ?? '');

# Validasi input
$errors = [];

# Validasi kode dosen (harus angka, 8-10 digit)
if (empty($kode_dosen)) {
    $errors[] = 'kode wajib diisi.';
} elseif (!preg_match('/^[0-9]{8,10}$/', $kode_dosen)) {
    $errors[] = 'kode harus terdiri dari 8-10 digit angka.';
}

# Validasi Nama
if (empty($nama_dosen)) {
    $errors[] = 'Nama lengkap wajib diisi.';
} elseif (strlen($nama_dosen) < 2) {
    $errors[] = 'Nama minimal 2 karakter.';
}

# Validasi Alamat Rumah
if (empty($alamat_rumah)) {
    $errors[] = 'alamat wajib diisi.';
}

# Validasi Tanggal jadi dosen
if (empty($tanggal_jadi_dosen)) {
    $errors[] = 'Tanggal jadi wajib diisi.';
}

# Validasi JJA Dose
if (empty($jja_dosen)) {
    $errors[] = 'JJA Dosen wajib diisi.';
}

# Validasi Homebase Prodi
if (empty($homebase_prodi)) {
    $errors[] = 'Homebase Prodi wajib diisi.';
}

# Cek duplikasi NIM
if (empty($errors)) {
    $check_nim = "SELECT cmid FROM tbl_identitas_mahasiswa WHERE cnim = ?";
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
        'kode_dosen' => $kode_dosen,
        'nama_dosen' => $nama_dosen,
        'alamat_rumah' => $alamat_rumah,
        'tanggal_jadi_dosen' => $tanggal_jadi_dosen,
        'jja_dosen' => $jja_dosen,
        'homebase_prodi' => $homebase_prodi,
        'nomor_hp' => $nomor_hp,
        'nama_pasangan' => $nama_pasangan,
        'nama_anak' => $nama_anak,
        'bidang_ilmu_dosen' => $bidang_ilmu_dosen
    ];
    
    $_SESSION['flash_error'] = implode('<br>', $errors);
    redirect_ke('index.php#biodata');
}

# Simpan data ke session untuk ditampilkan di section about
$_SESSION['biodata'] = [
    "kode_dosen" => $kode_dosen,
    "nama_dosen" => $nama_dosen,
    "alamat_rumah" => $alamat_rumah,
    "tanggal_jadi_dosen" => $tanggal_jadi_dosen,
    "jja_dosen" => $jja_dosen,
    "homebase_prodi" => $homebase_prodi,
    "nomor_hp" => $nomor_hp,
    "nama_pasangan" => $nama_pasangan,
    "nama_anak" => $nama_anak,
    "bidang_ilmu_dosen" => $bidang_ilmu_dosen
];

# Insert data ke database menggunakan prepared statement
$sql = "INSERT INTO tbl_biodata_dosen (ckode_dosen, cnama_dosen, calamat_rumah, ctanggal_jadi, 
        cjja_dosen, chomebase_prodi, cnomor_hp, cnama_pasangan, cnama_anak, cbidang_ilmu_dosen) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
    redirect_ke('index.php#biodata');
}

# Bind parameter dan eksekusi
mysqli_stmt_bind_param($stmt, "ssssssssss", 
    $kode_dosen, $nama_dosen, $alamat_rumah, $tanggal_jadi_dosen, 
    $jja_dosen, $homebase_prodi, $nomor_hp, $nama_pasangan, 
    $nama_anak, $bidang_ilmu_dosen);

if (mysqli_stmt_execute($stmt)) {
    unset($_SESSION['old_biodata']);
    $_SESSION['flash_sukses'] = 'Data biodata dosen berhasil disimpan!';
} else {
    $_SESSION['flash_error'] = 'Data gagal disimpan. Silakan coba lagi.';
    $_SESSION['old_biodata'] = [
       'kode_dosen' => $kode_dosen,
        'nama_dosen' => $nama_dosen,
        'alamat_rumah' => $alamat_rumah,
        'tanggal_jadi_dosen' => $tanggal_jadi_dosen,
        'jja_dosen' => $jja_dosen,
        'homebase_prodi' => $homebase_prodi,
        'nomor_hp' => $nomor_hp,
        'nama_pasangan' => $nama_pasangan,
        'nama_anak' => $nama_anak,
        'bidang_ilmu_dosen' => $bidang_ilmu_dosen
    ];
}

mysqli_stmt_close($stmt);

# Konsep PRG: Redirect ke halaman biodata
redirect_ke('index.php#biodata');
?>