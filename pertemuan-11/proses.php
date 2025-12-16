<?php
session_start();
require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';


if ($_SERVER[ 'REQUEST_METHOD'] !== 'POST') {
  $_SESSION['flash_error'] = "Akses tidak valid.";
  redirect_ke('index.php#contact');
}


$nama  = bersihkan($_POST['txtNama'] ?? "");
$email = bersihkan($_POST['txtEmail'] ?? "");
$pesan = bersihkan($_POST['txtPesan'] ?? "");
$jawaban_captcha = $_POST['jawaban_captcha'] ?? "";
$hasil = $_SESSION ["hasil"]?? null;



$errors = [];

if (strlen(trim($nama)) < 3) {
  $errors[] = 'Nama wajib diisi minimal 3 karakter.';
}

if (empty($email)) {
  $errors[] = 'Email wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = 'Format email tidak valid.';
}

if (strlen(trim($pesan)) < 10) {
  $errors[] = 'Pesan wajib diisi minimal 10 karakter.';
}

if ($jawaban_captcha === "") {
  $errors[] = "Mohon mengisi Verifikasi";
} elseif (!is_numeric($jawaban_captcha) || (int)$jawaban_captcha !== (int)$hasil) {
  $errors[] = "jawaban tidak vaild";
}

if (!empty($errors)) {
  $_SESSION['old'] = [
    'nama' => $nama,
    'email' => $email,
    'pesan' => $pesan
  ];
  $_SESSION['flash_error'] = implode("<br>", $errors);
  redirect_ke('index.php#contact');
}


$sql = "INSERT INTO tbl_tamu (cnama, cemail, cpesan) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
 
  $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
  redirect_ke('index.php#contact');
}

mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $pesan);

if (mysqli_stmt_execute($stmt)) {
  unset ($_SESSION['old']);
  $_SESSION['flash_sukses'] = 'Terima kasih, Data anda sudah tersimpan.';
  redirect_ke('index.php#contact');
} else {

  $_SESSION['old'] = [
    "nama" => $nama,
    "email" => $email,
    "pesan" => $pesan
  ];
  $_SESSION["flash_error"] = 'Gagal mengirim pesan. Silakan coba lagi.';
  redirect_ke('index.php#contact');
}

mysqli_stmt_close($stmt);

$arrBiodata = [
  "nim" => $_POST["txtNim"] ?? "",
  "nama" => $_POST["txtNmLengkap"] ?? "",
  "tempat" => $_POST["txtT4Lhr"] ?? "",
  "tanggal" => $_POST["txtTglLhr"] ?? "",
  "hobi" => $_POST["txtHobi"] ?? "",
  "pasangan" => $_POST["txtPasangan"] ?? "",
  "pekerjaan" => $_POST["txtKerja"] ?? "",
  "ortu" => $_POST["txtNmOrtu"] ?? "",
  "kakak" => $_POST["txtNmKakak"] ?? "",
  "adik" => $_POST["txtNmAdik"] ?? ""
];
$_SESSION["biodata"] = $arrBiodata;

header("location: index.php#about");