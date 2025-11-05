
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judul Halaman</title>
    <link rel="stylesheet" href="style.css">
</head>    
<body>
    <header>       
        <h1>Ini Header</h1>
        <button class="menu-toggle" id="menu-toggle" aria-label="Toggle Navigation">
            &#9776;
        </button>
        <nav>
            <ul type="square">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#contact">kontak</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="home">
        <h2>Selamat Datang</h2>
        <?php
        echo "halo dunia!";
        echo "Perkenalkan nama saya Raka Pradipta";
        ?>
        <p>Ini contoh paragraf HTML.</p>
    </section>
    <section id="about">
        <?php
            $nim = "2511500008";
            $Nama = "Raka Pradipta &#128526;";
            $nama = "Raka";
            $TempatLahir = "Jebus";
            $TanggalLahir = "06 Februari 2005";
            $Hobi = "Futsal, Volly, dan Sepak Bola";
            $Pasangan = "Rulita Rizqi Aprillia &hearts;";
            $Pekerjaan = "Mahasiswa";
            $NamaOrangTua = "Bapak Muhammad Dian Alifi dan Ibu Rusmi Hartati";
            $NamaKakak = "Adityo Nurzi dan Dwi Adji Muzhaffar";
            $NamaAdik = "-";

        ?>
        <h2>Tentang Saya</h2>
        <p><strong>NIM:</strong> 
            <?php
                echo $nim;        
            ?>
        </p>
        <p><strong>Nama Lengkap:</strong>
            <?php
                echo $Nama;
            ?>
        </p>
        <p><strong>Tempat Lahir:</strong>
            <?php
                echo $TempatLahir;
            ?>
        </p>
        <p><strong>Tanggal Lahir:</strong>
            <?php
                echo $TanggalLahir;
            ?>
        </p>
        <p><strong>Hobi:</strong>
            <?php
                echo $Hobi;
            ?>
        </p>
        <p><strong>Pasangan:</strong>
            <?php
                echo $Pasangan;
            ?>
        </p>
        <p><strong>Pekerjaan:</strong>
            <?php
                echo $Pekerjaan;
            ?>
        </p>
        <p><strong>Nama Orang Tua:</strong>
            <?php
                echo $NamaOrangTua;
            ?>
        </p>
        <p><Strong>Nama Kakak:</Strong>
            <?php
                echo $NamaKakak;
            ?>
        </p>
        <p><strong>Nama Adik:</strong>
            <?php
                echo $NamaAdik;
            ?>
        </p>
    </section>
    <section id="contact">
        <h2>Kontak Kami</h2>
        <form action="" method="GET">
            <label for="txtNama"><span>Nama:</span>
            <input type="text" id="txtNama" name="txtNama" placeholder="Masukkan nama" required autocomplete="name">
            </label>
            <label for="txtEmail"><span>Email:</span>
            <input type="email" id="txtEmail" name="txtEmail"  placeholder="Masukkan Email" required autocomplete="email">
            </label>
            <label for="txtPesan"><span>Pesan Anda</span>
            <textarea id="txtPesan" name="txtPesan"  rows="4" placeholder="Tulis pesan anda..." required></textarea>
            </label>
            <button type="submit">Kirim</button>
            <button type="reset">Batal</button>
        </form>
    </section>
    <section id="IPK">
        <h2>Nilai saya</h2>
        <?php
        $namaMatkul1 = "Kalkulus";
        $namaMatkul2 = "Logika Informatika";
        $namaMatkul3 = "Pengantar Teknik Informatika";
        $namaMatkul4 = "Aplikasi Perkantoran";
        $namaMatkul5 = "Konsep Basis Data";

        $sksMatkul1 = "3";
        $sksMatkul2 = "3";
        $sksMatkul3 = "3";
        $sksMatkul4 = "3";
        $sksMatkul5 = "3";

        $nilaiHadir1 = "90";
        $nilaiHadir2 = "100";
        $nilaiHadir3 = "100";
        $nilaiHadir4 = "80";
        $nilaiHadir5 = "100";

        $nilaiTugas1 = "90";
        $nilaiTugas2 = "100";
        $nilaiTugas3 = "100";
        $nilaiTugas4 = "90";
        $nilaiTugas5 = "80";

        $nilaiUTS1 = "70";
        $nilaiUTS2 = "80";
        $nilaiUTS3 = "80";
        $nilaiUTS4 = "90";
        $nilaiUTS5 = "80";

        $nilaiUAS1 = "80";
        $nilaiUAS2 = "90";
        $nilaiUAS3 = "90";
        $nilaiUAS4 = "100";
        $nilaiUAS5 = "90";

        ?>

        <?php
    function grade($nilaiAkhir, $nilaHadir) {
        if ($nilaiHadir < 70) {
            $grade = "E";
            $status = "GAGAL";
            $angkaMutu = 0.00;
        } elsief ($nilaiAkhir >= 91 && $nilaiAkhir <= 100) {
            $grade = "A";
            $status = "GAGAL";
            $angkaMutu = 4.00;
        }
    }


</main>
    <footer>
        <p>&copy; 2025 Raka Pradipta [2511500008]</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>