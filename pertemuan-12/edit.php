<?php
session_start();
require 'koneksi.php';
require 'fungsi.php';

// 1. Ambil nilai cid dari GET dan validasi
$cid = filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

// 2. Cek apakah cid valid
if (!$cid) {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('read.php');
}

// 3. Ambil data lama dari DB menggunakan prepared statement
$stmt = mysqli_prepare($conn, "SELECT cid, cnama, cemail, cpesan FROM tbl_tamu WHERE cid = ?");
if (!$stmt) {
    $_SESSION['flash_error'] = 'Query tidak benar.';
    redirect_ke('read.php');
}

mysqli_stmt_bind_param($stmt, "i", $cid);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

// 4. Jika data tidak ditemukan
if (!$row) {
    $_SESSION['flash_error'] = 'Record tidak ditemukan.';
    redirect_ke('read.php');
}

// 5. Nilai awal (prefill form) dari database
$nama = $row['cnama'] ?? '';
$email = $row['cemail'] ?? '';
$pesan = $row['cpesan'] ?? '';

// 6. Ambil error dan nilai old input kalau ada (dari validasi gagal)
$flash_error = $_SESSION['flash_error'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['flash_error'], $_SESSION['old']);

// Jika ada data old (dari input user sebelumnya), timpa data database
if (!empty($old)) {
    $nama = $old['nama'] ?? $nama;
    $email = $old['email'] ?? $email;
    $pesan = $old['pesan'] ?? $pesan;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Tamu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Ini Header</h1>
    <nav>
        <ul>
            <li><a href="#home">Beranda</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#contact">Kontak</a></li>
        </ul>
    </nav>
</header>

<main>
    <section id="contact">
        <h2>Edit Buku Tamu</h2>
        
        <?php if (!empty($flash_error)): ?>
        <div style="padding:10px; margin-bottom:10px; background:#f8d7da; color:#721c24; border-radius: 6px;">
            <?= $flash_error; ?>
        </div>
        <?php endif; ?>

        <form action="proses_update.php" method="POST">
            <input type="text" name="cid" value="<?= (int)$cid; ?>" readonly>

            <label for="txtNama"><span>Nama:</span>
                <input type="text" id="txtNama" name="txtNamaEd" 
                       placeholder="Masukkan nama" required autocomplete="name"
                       value="<?= !empty($nama) ? htmlspecialchars($nama) : '' ?>">
            </label>

            <label for="txtEmail"><span>Email:</span>
                <input type="email" id="txtEmail" name="txtEmailEd"
                       placeholder="Masukkan email" required autocomplete="email"
                       value="<?= !empty($email) ? htmlspecialchars($email) : '' ?>">
            </label>

            <label for="txtPesan"><span>Pesan Anda:</span>
                <textarea id="txtPesan" name="txtPesanEd" rows="4"
                          placeholder="Tulis pesan anda..." required><?= !empty($pesan) ? htmlspecialchars($pesan) : '' ?></textarea>
            </label>

            <label for="txtCaptcha"><span>Captcha 2 x 3 = ?</span>
                <input type="number" id="txtCaptcha" name="txtCaptcha"
                       placeholder="Jawab Pertanyaan..." required>
            </label>

            <button type="submit">Kirim</button>
            <button type="reset">Batal</button>
            <a href="read.php" class="reset">Kembali</a>
        </form>
    </section>
</main>
</body>
</html>