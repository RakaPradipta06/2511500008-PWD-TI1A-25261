<?php
    session_start();
    require 'koneksi.php';
    #
    require 'fungsi.php';

    $sql = "SELECT * FROM tbl_tamu ORDER BY cid DESC";
$q = mysqli_query($conn, $sql);
if (!$q) {
    die("Query error: " . mysqli_error($conn));
}
?>

<?php
$flash_sukses = $_SESSION['flash_sukses'] ?? ''; //jika query sukses
$flash_error = $_SESSION['flash_error'] ?? ''; //jika ada error
//bersihkan session ini
unset($_SESSION['flash_sukses'], $_SESSION['flash_error']);
?>

<?php if (!empty($flash_sukses)) : ?>
    <div style="padding:10px; margin-bottom:10px;
        background:#d4edda; color:#155724; border-radius:6px;">
        <?= $flash_sukses; ?>
    </div>
<?php endif; ?>

<?php if (!empty($flash_error)) : ?>
    <div style="padding:10px; margin-bottom:10px;
        background:#f8d7da; color:#721c24; border-radius:6px;">
        <?= $flash_error; ?>
    </div>
<?php endif; ?>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>No</th> 
            <th>Aksi</th>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Pesan</th>
            <th>Created At</th> 
        </tr>
    </thead>
    <tbody>
    <?php
    $no = 1; 
    while ($row = mysqli_fetch_assoc($q)) {
    ?>
        <tr>
            <td><?= $no++; ?></td> 
            <td><a href="edit.php?cid=<?= (int)$row['cid']; ?>">Edit</a></td>
            <td><?= $row['cid']; ?></td>
            <td><?= $row['cnama']; ?></td>
            <td><?= $row['cemail']; ?></td>
            <td><?= $row['cpesan']; ?></td>
            <td><?= $row['dcreated_at']; ?></td> 
        </tr>
    <?php } ?>
</tbody>
</table>