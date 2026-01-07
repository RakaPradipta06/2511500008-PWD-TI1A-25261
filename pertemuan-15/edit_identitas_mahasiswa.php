<?php
session_start();
require 'koneksi.php';
require 'fungsi.php';

# Validasi cmid dari GET
$cmid = filter_input(INPUT_GET, 'cmid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$cmid) {
    $_SESSION['flash_error_mhs'] = 'Akses tidak valid.';
    redirect_ke('read_identitas_mahasiswa.php');
}

# Ambil data lama dari database
$stmt = mysqli_prepare($conn, "SELECT * FROM tbl_identitas_mahasiswa WHERE cmid = ? LIMIT 1");
if (!$stmt) {
    $_SESSION['flash_error_mhs'] = 'Query tidak benar.';
    redirect_ke('read_identitas_mahasiswa.php');
}

mysqli_stmt_bind_param($stmt, "i", $cmid);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$row) {
    $_SESSION['flash_error_mhs'] = 'Data mahasiswa tidak ditemukan.';
    redirect_ke('read_identitas_mahasiswa.php');
}

# Nilai awal (prefill form)
$nim = $row['cnim'] ?? '';
$nama = $row['cnama'] ?? '';
$tempat_lahir = $row['ctempat_lahir'] ?? '';
$tanggal_lahir = date('Y-m-d', strtotime($row['ctanggal_lahir'])) ?? '';
$hobi = $row['chobi'] ?? '';
$pasangan = $row['cpasangan'] ?? '';
$pekerjaan = $row['cpekerjaan'] ?? '';
$nama_ortu = $row['cnama_ortu'] ?? '';
$nama_kakak = $row['cnama_kakak'] ?? '';
$nama_adik = $row['cnama_adik'] ?? '';

# Ambil error dan nilai old input jika ada
$flash_error = $_SESSION['flash_error_edit'] ?? '';
$old = $_SESSION['old_edit'] ?? [];
unset($_SESSION['flash_error_edit'], $_SESSION['old_edit']);

if (!empty($old)) {
    $nama = $old['nama'] ?? $nama;
    $tempat_lahir = $old['tempat_lahir'] ?? $tempat_lahir;
    $tanggal_lahir = $old['tanggal_lahir'] ?? $tanggal_lahir;
    $hobi = $old['hobi'] ?? $hobi;
    $pasangan = $old['pasangan'] ?? $pasangan;
    $pekerjaan = $old['pekerjaan'] ?? $pekerjaan;
    $nama_ortu = $old['nama_ortu'] ?? $nama_ortu;
    $nama_kakak = $old['nama_kakak'] ?? $nama_kakak;
    $nama_adik = $old['nama_adik'] ?? $nama_adik;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Biodata Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Edit Biodata Mahasiswa</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="read_identitas_mahasiswa.php">Data Mahasiswa</a></li>
                <li><a href="edit">Edit Biodata</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="biodata" style="max-width: 800px; margin: 20px auto;">
            <h2>Edit Biodata Sederhana Mahasiswa</h2>
            
            <?php if (!empty($flash_error)): ?>
                <div style="padding:10px; margin-bottom:10px; 
                    background:#f8d7da; color:#721c24; border-radius:6px;">
                    <?= $flash_error; ?>
                </div>
            <?php endif; ?>
            
            <form action="update_identitas_mahasiswa.php" method="POST">
                <input type="hidden" name="cmid" value="<?= (int)$cmid; ?>">
                
                <label for="txtNimEdit"><span>NIM:</span>
                    <input type="text" id="txtNimEdit" name="txtNim" 
                        value="<?= htmlspecialchars($nim); ?>" readonly>
                </label>

                <label for="txtNmLengkapEdit"><span>Nama Lengkap:</span>
                    <input type="text" id="txtNmLengkapEdit" name="txtNmLengkap" 
                        placeholder="Masukkan Nama Lengkap" required
                        value="<?= htmlspecialchars($nama); ?>">
                </label>

                <label for="txtT4LhrEdit"><span>Tempat Lahir:</span>
                    <input type="text" id="txtT4LhrEdit" name="txtT4Lhr" 
                        placeholder="Masukkan Tempat Lahir" required
                        value="<?= htmlspecialchars($tempat_lahir); ?>">
                </label>

                <label for="txtTglLhrEdit"><span>Tanggal Lahir:</span>
                    <input type="date" id="txtTglLhrEdit" name="txtTglLhr" 
                        required value="<?= htmlspecialchars($tanggal_lahir); ?>">
                    <small style="color: #666; font-size: 12px;">Format: YYYY-MM-DD</small>
                </label>

                <label for="txtHobiEdit"><span>Hobi:</span>
                    <input type="text" id="txtHobiEdit" name="txtHobi" 
                        placeholder="Masukkan Hobi" required
                        value="<?= htmlspecialchars($hobi); ?>">
                </label>

                <label for="txtPasanganEdit"><span>Pasangan:</span>
                    <input type="text" id="txtPasanganEdit" name="txtPasangan" 
                        placeholder="Masukkan Pasangan"
                        value="<?= htmlspecialchars($pasangan); ?>">
                </label>

                <label for="txtKerjaEdit"><span>Pekerjaan:</span>
                    <input type="text" id="txtKerjaEdit" name="txtKerja" 
                        placeholder="Masukkan Pekerjaan"
                        value="<?= htmlspecialchars($pekerjaan); ?>">
                </label>

                <label for="txtNmOrtuEdit"><span>Nama Orang Tua:</span>
                    <input type="text" id="txtNmOrtuEdit" name="txtNmOrtu" 
                        placeholder="Masukkan Nama Orang Tua" required
                        value="<?= htmlspecialchars($nama_ortu); ?>">
                </label>

                <label for="txtNmKakakEdit"><span>Nama Kakak:</span>
                    <input type="text" id="txtNmKakakEdit" name="txtNmKakak" 
                        placeholder="Masukkan Nama Kakak"
                        value="<?= htmlspecialchars($nama_kakak); ?>">
                </label>

                <label for="txtNmAdikEdit"><span>Nama Adik:</span>
                    <input type="text" id="txtNmAdikEdit" name="txtNmAdik" 
                        placeholder="Masukkan Nama Adik"
                        value="<?= htmlspecialchars($nama_adik); ?>">
                </label>

                <button type="submit">Kirim Perubahan</button>
                <button type="reset">Batal</button>
                <a href="read_identitas_mahasiswa.php" style="background-color: #b4b4b4; color: #272727; 
                   padding: 10px 20px; border-radius: 6px; text-decoration: none; 
                   display: inline-block; margin-left: 10px;">
                    Kembali
                </a>
            </form>
        </section>
    </main>
</body>
</html>