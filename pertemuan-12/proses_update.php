<?php
session_start();
require __DIR__ . '/koneksi.php'; // Sesuaikan path koneksi Anda
require_once __DIR__ . '/fungsi.php'; // Sesuaikan path fungsi Anda

# Cek method form, hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('read.php');
}

# Validasi cid wajib angka dan > 0
$cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$cid) {
    $_SESSION['flash_error'] = 'CID Tidak valid.';
    redirect_ke('edit.php?cid=' . (int)$cid);
}

# Ambil dan bersihkan (sanitasi) nilai dari form
$nama = bersihkan($_POST['txtNamaEd'] ?? '');
$email = bersihkan($_POST['txtEmailEd'] ?? '');
$pesan = bersihkan($_POST['txtPesanEd'] ?? '');
$captcha = bersihkan($_POST['txtCaptcha']);

# Validasi sederhana
$errors = []; // Array untuk menampung error

if ($nama === '') {
    $errors[] = 'Nama wajib diisi.';
}

if ($email === '') {
    $errors[] = 'Email wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format e-mail tidak valid.';
}

if ($pesan === '') {
    $errors[] = 'Pesan wajib diisi.';
}

if (!$captcha) {
    $errors[] = 'Pertanyaan wajib diisi.';
}

if (mb_strlen($nama) < 3) {
    $errors[] = 'Nama minimal 3 karakter.';
}

if (mb_strlen($pesan) < 10) {
    $errors[] = 'Pesan minimal 10 karakter.';
}

if ($captcha != "6") {
    $errors[] = 'Jawaban ' . htmlspecialchars($captcha) . ' captcha salah.';
}

# Jika ada error, simpan nilai lama dan redirect (PRG)
if (!empty($errors)) {
    $_SESSION['old'] = [
        'nama' => $nama,
        'email' => $email,
        'pesan' => $pesan
    ];
    $_SESSION['flash_error'] = implode('<br>', $errors);
    redirect_ke('edit.php?cid=' . (int)$cid);
}

# Prepared statement untuk UPDATE (WAJIB WHERE cid = ?)
$stmt = mysqli_prepare($conn, "UPDATE tbl_tamu SET cnama = ?, cemail = ?, cpesan = ? WHERE cid = ?");

if (!$stmt) {
    $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
    redirect_ke('edit.php?cid=' . (int)$cid);
}

# Bind parameter (s=string, i=integer) -> "sssi"
mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $pesan, $cid);

if (mysqli_stmt_execute($stmt)) {
    # Jika berhasil, kosongkan old value
    unset($_SESSION['old']);
    $_SESSION['flash_sukses'] = 'Terima kasih, data Anda sudah diperbaharui.';
    redirect_ke('read.php');
} else {
    # Jika gagal, simpan kembali old value
    $_SESSION['old'] = [
        'nama' => $nama,
        'email' => $email,
        'pesan' => $pesan
    ];
    $_SESSION['flash_error'] = 'Data gagal diperbaharui. Silakan coba lagi.';
    redirect_ke('edit.php?cid=' . (int)$cid);
}

mysqli_stmt_close($stmt);
?>