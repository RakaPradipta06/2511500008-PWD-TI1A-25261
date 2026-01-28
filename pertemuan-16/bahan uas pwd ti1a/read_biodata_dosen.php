<?php
session_start();
require 'koneksi.php';
require 'fungsi.php';

$sql = "SELECT * FROM tbl_dosen_dosen ORDER BY cmid DESC";
$q = mysqli_query($conn, $sql);
if (!$q) {
    die("Query error: " . mysqli_error($conn));
}

$flash_sukses = $_SESSION['flash_sukses_mhs'] ?? '';
$flash_error  = $_SESSION['flash_error_mhs'] ?? '';
unset($_SESSION['flash_sukses_mhs'], $_SESSION['flash_error_mhs']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .data-dosen {
            margin: 20px auto;
            max-width: 1200px;
            padding: 20px;
        }
        .data-dosen table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-dosen th, .data-dosen td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .data-dosen th {
            background-color: #003366;
            color: white;
            font-weight: bold;
        }
        .data-dosen tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .data-dosen tr:hover {
            background-color: #f5f5f5;
        }
        .aksi-btn {
            display: flex;
            gap: 10px;
        }
        .btn-edit {
            background-color: #0fff07ff;
            color: #000;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-hapus {
            background-color: #fd03dcff;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }
        .btn-hapus:hover {
            background-color: #c82333;
        }
        .pesan-status {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: bold;
        }
        .pesan-sukses {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .pesan-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <header>
        <h1>Data Dosen</h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="index.php#biodata">Input Biodata</a></li>
                <li><a href="#data">Data Mahasiswa</a></li>
            </ul>
        </nav>
    </header>

    <main class="data-dosen">
        <h2>Daftar Biodata Dosen</h2>
        
        <?php if (!empty($flash_sukses)): ?>
            <div class="pesan-status pesan-sukses">
                <?= $flash_sukses; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($flash_error)): ?>
            <div class="pesan-status pesan-error">
                <?= $flash_error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (mysqli_num_rows($q) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Aksi</th>
                        <th>Kode Dosen</th>
                        <th>Nama Dosen</th>
                        <th>Alamat Rumah</th>
                        <th>Tanggal Jadi Dosen</th>
                        <th>JJA Dosen</th>
                        <th>Homebase Prodi</th>
                        <th>Nomor HP</th>
                        <th>Nama Pasangan</th>
                        <th>Nama Anak</th>
                        <th>Bidang Ilmu Dosen</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($q)): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td class="aksi-btn">
                                <a href="edit_biodata_dosen.php?cmid=<?= (int)$row['cmid']; ?>" class="btn-edit">Edit</a>
                                <a href="delete_biodata_dosen.php?cmid=<?= (int)$row['cmid']; ?>" 
                                   class="btn-hapus" 
                                   onclick="return confirm('Hapus data <?= htmlspecialchars($row['cnama_dosen']); ?> (kode_dosen: <?= htmlspecialchars($row['ckode_dosen']); ?>)?')">
                                    Hapus
                                </a>
                            </td>
                            <td><?= htmlspecialchars($row['ckode_dosen']); ?></td>
                            <td><?= htmlspecialchars($row['cnama_dosen']); ?></td>
                            <td><?= htmlspecialchars($row['calamat_rumah']); ?></td>
                            <td><?= htmlspecialchars($row['ctanggal_jadi_dosen']); ?></td>
                            <td><?= htmlspecialchars($row['cjja_dosen']); ?></td>
                            <td><?= htmlspecialchars($row['chomebase_prodi'] ?? '-'); ?></td>
                            <td><?= htmlspecialchars($row['cnomor_hp']); ?></td>
                            <td><?= htmlspecialchars($row['cnama_pasangan']); ?></td>
                            <td><?= htmlspecialchars($row['cnama_anak']); ?></td>
                            <td><?= htmlspecialchars($row['cbidang_ilmu_prodi']); ?></td>
                            <td><?= formatTanggal($row['dcreated_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 40px;">
                <p style="font-size: 18px; color: #666;">Belum ada data.</p>
                <a href="index.php#biodata" style="color: #003366; text-decoration: underline;">Klik di sini untuk menambah data</a>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="index.php" style="background-color: #003366; color: white; padding: 10px 20px; 
               border-radius: 6px; text-decoration: none; display: inline-block;">
                Kembali ke Beranda
            </a>
        </div>
    </main>
</body>
</html>