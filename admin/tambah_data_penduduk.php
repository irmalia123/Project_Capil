<?php
// Mengimpor file session untuk mengelola sesi pengguna
include '../config/session.php';
// Mengimpor file koneksi database
include '../config/database.php'; 
// Redirect pengguna jika tidak terautentikasi
redirect_if_not_logged_in();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Penduduk</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome untuk ikon -->
    <style>
        /* Reset dan Global */
        * {
            margin: 0; /* Menghilangkan margin default */
            padding: 0; /* Menghilangkan padding default */
            box-sizing: border-box; /* Mengatur box-sizing untuk semua elemen */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Mengatur font untuk seluruh halaman */
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); /* Latar belakang dengan gradien */
            min-height: 100vh; /* Memastikan tinggi minimal halaman */
            padding: 20px; /* Ruang di dalam body */
        }

        .container {
            max-width: 800px; /* Lebar maksimum kontainer */
            margin: 20px auto; /* Margin otomatis untuk pusatkan kontainer */
            background: white; /* Warna latar belakang kontainer */
            padding: 30px; /* Ruang di dalam kontainer */
            border-radius: 15px; /* Sudut melengkung */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Bayangan kontainer */
        }

        h1 {
            color: #2c3e50; /* Warna teks judul */
            text-align: center; /* Pusatkan teks judul */
            margin-bottom: 30px; /* Jarak bawah judul */
            font-size: 2.2em; /* Ukuran font judul */
            position: relative; /* Posisi relatif untuk efek garis bawah */
            padding-bottom: 10px; /* Ruang bawah judul */
        }

        h1::after {
            content: ''; /* Garis bawah judul */
            position: absolute; /* Posisi absolut untuk garis */
            bottom: 0; /* Menempel di bawah judul */
            left: 50%; /* Pusatkan garis */
            transform: translateX(-50%); /* Pusatkan garis */
            width: 100px; /* Lebar garis */
            height: 4px; /* Tinggi garis */
            background: linear-gradient(90deg, #3498db, #2ecc71); /* Warna garis */
            border-radius: 2px; /* Sudut melengkung garis */
        }

        form {
            display: grid; /* Mengatur form dengan grid */
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Mengatur kolom responsif */
            gap: 20px; /* Jarak antar elemen dalam form */
        }

        input, select {
            width: 100%; /* Lebar penuh untuk input dan select */
            padding: 12px 15px; /* Ruang di dalam input dan select */
            border: 2px solid #e0e0e0; /* Garis batas input dan select */
            border-radius: 8px; /* Sudut melengkung */
            font-size: 1em; /* Ukuran font */
            transition: all 0.3s ease; /* Transisi untuk efek fokus */
        }

        input:focus, select:focus {
            border-color: #3498db; /* Warna batas saat fokus */
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.3); /* Bayangan saat fokus */
            outline: none; /* Menghilangkan outline default */
        }

        input[type="date"] {
            padding: 10px 15px; /* Ruang di dalam input tanggal */
        }

        input[type="submit"] {
            grid-column: 1 / -1; /* Tombol submit mengambil seluruh kolom */
            background: linear-gradient(90deg, #3498db, #2ecc71); /* Warna latar belakang tombol submit */
            color: white; /* Warna teks tombol submit */
            border: none; /* Tanpa garis batas */
            padding: 15px; /* Ruang di dalam tombol */
            font-size: 1.1em; /* Ukuran font tombol */
            cursor: pointer; /* Kursor tangan saat hover */
            transition: transform 0.2s ease; /* Transisi untuk efek hover */
        }

        input[type="submit"]:hover {
            transform: translateY(-2px); /* Efek angkat saat hover */
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3); /* Bayangan saat hover */
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px; /* Ruang di dalam kontainer pada perangkat kecil */
            }

            h1 {
                font-size: 1.8em; /* Ukuran font judul pada perangkat kecil */
            }

            input, select {
                padding: 10px 12px; /* Ruang di dalam input dan select pada perangkat kecil */
            }
        }

        /* Input field labels using placeholder styling */
        input::placeholder, select::placeholder {
            color: #666; /* Warna placeholder */
            opacity: 0.7; /* Opasitas placeholder */
        }

        /* Success message animation */
        @keyframes slideIn {
            from {
                transform: translateY(-20px); /* Animasi masuk dari atas */
                opacity: 0; /* Mulai dari transparan */
            }
            to {
                transform: translateY(0); /* Berhenti di posisi normal */
                opacity: 1; /* Menjadi terlihat */
            }
        }

        .success-message {
            grid-column: 1 / -1; /* Membuat pesan sukses mengambil seluruh kolom */
            padding: 15px; /* Ruang di dalam pesan */
            background-color: #d4edda; /* Warna latar belakang pesan sukses */
            color: #155724; /* Warna teks pesan sukses */
            border-radius: 8px; /* Sudut melengkung */
            text-align: center; /* Pusatkan teks dalam pesan */
            animation: slideIn 0.5s ease; /* Animasi saat muncul */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Data Penduduk</h1>
        <form action="proses_tambah_data.php" method="POST"> <!-- Form untuk menambah data penduduk -->
            <input type="text" name="nik" placeholder="NIK" required> <!-- Input untuk NIK -->
            <input type="text" name="nama" placeholder="Nama Lengkap" required> <!-- Input untuk nama -->
            <input type="text" name="alamat" placeholder="Alamat Lengkap" required> <!-- Input untuk alamat -->
            <input type="text" name="tempat_lahir" placeholder="Tempat Lahir" required> <!-- Input untuk tempat lahir -->
            <input type="date" name="tanggal_lahir" required> <!-- Input untuk tanggal lahir -->
            <input type="text" name="agama" placeholder="Agama"> <!-- Input untuk agama -->
            <input type="text" name="pendidikan" placeholder="Pendidikan Terakhir"> <!-- Input untuk pendidikan -->
            <input type="text" name="golongan_darah" placeholder="Golongan Darah"> <!-- Input untuk golongan darah -->
            <select name="status_warga" required> <!-- Dropdown untuk status warga -->
                <option value="">Pilih Status Warga</option>
                <option value="Aktif">Aktif</option>
                <option value="Non-Aktif">Non-Aktif</option>
            </select>
            <select name="jenis_kelamin" required> <!-- Dropdown untuk jenis kelamin -->
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
            <input type="submit" value="Simpan Data"> <!-- Tombol untuk menyimpan data -->
        </form>
    </div>
</body>
</html>
