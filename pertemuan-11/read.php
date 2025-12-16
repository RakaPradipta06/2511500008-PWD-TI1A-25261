<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>No</th> 
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Pesan</th>
            <th>Created At</th> 
        </tr>
    </thead>
    <tbody>
        <?php
        include 'koneksi.php'; // Sesuaikan nama file koneksi kamu
        $query = mysqli_query($conn, "SELECT * FROM tbl_tamu");

        // Soal 1: Buat variabel nomor mulai dari 1
        $no = 1; 

        while ($row = mysqli_fetch_assoc($query)) {
        ?>
            <tr>
                <td><?= $no++; ?></td> 
                
                <td><?= $row['cid']; ?></td>
                <td><?= $row['cnama']; ?></td>
                <td><?= $row['cemail']; ?></td>
                <td><?= $row['cpesan']; ?></td>
                
                <td><?= $row['dcreated_at']; ?></td> 
            </tr>
        <?php } ?>
    </tbody>
</table>