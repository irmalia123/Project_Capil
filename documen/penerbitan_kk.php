<?php
include '../config/session.php';
redirect_if_not_logged_in();
include '../config/database.php'; // Sertakan file koneksi database Anda

// Proses data jika formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Data Kartu Keluarga
    $nomor_kk = $_POST['nomor_kk'];
    $alamat = $_POST['alamat'];
    $rt = $_POST['rt'];
    $rw = $_POST['rw'];
    $kelurahan = $_POST['kelurahan'];
    $kecamatan = $_POST['kecamatan'];
    $kota = $_POST['kota'];
    $provinsi = $_POST['provinsi'];
    $tanggal_penerbitan = $_POST['tanggal_penerbitan'];
    $nik_kepala_keluarga = $_POST['nik_kepala_keluarga'];
    $nama_kepala_keluarga = $_POST['nama_kepala_keluarga'];

    // Siapkan pernyataan SQL
    $sql = "INSERT INTO penerbitan_kk (
        nomor_kk, 
        alamat, 
        rt, 
        rw, 
        kelurahan, 
        kecamatan, 
        kota, 
        provinsi, 
        tanggal_penerbitan, 
        nik_kepala_keluarga, 
        nama_kepala_keluarga
    ) VALUES (
        '$nomor_kk', 
        '$alamat', 
        '$rt', 
        '$rw', 
        '$kelurahan', 
        '$kecamatan', 
        '$kota', 
        '$provinsi', 
        '$tanggal_penerbitan', 
        '$nik_kepala_keluarga', 
        '$nama_kepala_keluarga'
    )";

    // Eksekusi pernyataan
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Penerbitan Kartu Keluarga berhasil!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerbitan Kartu Keluarga</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dokumen.css">
    <style>
        /* Gaya Umum untuk Formulir */
        .registration-form {
            max-width: 600px; /* Lebar maksimum formulir */
            margin: 20px auto; /* Pusatkan formulir */
            padding: 20px; /* Ruang di dalam formulir */
            border: 1px solid #ccc; /* Garis batas */
            border-radius: 8px; /* Sudut melengkung */
            background-color: #f9f9f9; /* Warna latar belakang */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Bayangan */
        }

        /* Gaya untuk Label */
        .registration-form label {
            display: block; /* Membuat label menjadi blok */
            margin-bottom: 5px; /* Jarak bawah label */
            font-weight: bold; /* Tebalkan teks label */
            color: #333; /* Warna teks label */
        }

        /* Gaya untuk Input */
        .registration-form input[type="text"],
        .registration-form input[type="date"],
        .registration-form input[type="number"],
        .registration-form textarea,
        .registration-form select {
            width: 100%; /* Lebar penuh */
            padding: 10px; /* Ruang di dalam input */
            margin-bottom: 15px; /* Jarak bawah input */
            border: 1px solid #ccc; /* Garis batas */
            border-radius: 4px; /* Sudut melengkung */
            font-size: 16px; /* Ukuran font */
        }

        /* Gaya untuk Tombol */
        .registration-form button {
            background-color: #4CAF50; /* Warna latar belakang tombol */
            color: white; /* Warna teks tombol */
            padding: 10px 15px; /* Ruang di dalam tombol */
            border: none; /* Tanpa garis batas */
            border-radius: 4px; /* Sudut melengkung */
            cursor: pointer; /* Kursor tangan saat hover */
            font-size: 16px; /* Ukuran font */
            transition: background-color 0.3s; /* Transisi warna latar belakang */
        }

        /* Gaya untuk Tombol saat Hover */
        .registration-form button:hover {
            background-color: #45a049; /* Warna latar belakang saat hover */
        }
    </style>
</head>
<body>
<nav class="sidebar">
    <div class="logo">
        <i class="fas fa-city"></i>
        <span class="menu-text">SIAK KOTA PAREPARE</span>
    </div>
    <a href="../pages/dashboard.php" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
    <a href="../pages/data_penduduk.php" class="menu-item"><i class="fas fa-users"></i> Data Penduduk</a>
    <a href="../pages/dokumen.php" class="menu-item"><i class="fas fa-file-alt"></i> Form Pengajuan</a>
    </a>
</nav>

<main class="main-content">
    <div class="header">
        <h1>Penerbitan Kartu Keluarga</h1>
        <p class="sub-header">Silakan isi data berikut untuk penerbitan Kartu Keluarga.</p>
    </div>

    <form action="" method="POST" class="registration-form">
        <h2>I. DATA KARTU KELUARGA</h2>
        <label for="nomor_kk">Nomor Kartu Keluarga:</label>
        <input type="text" id="nomor_kk" name="nomor_kk" required>

        <label for="alamat">Alamat:</label>
        <input type="text" id="alamat" name="alamat" required>

        <label for="rt">RT:</label>
        <input type="text" id="rt" name="rt" required>

        <label for="rw">RW:</label>
        <input type="text" id="rw" name="rw" required>

        <label for="kelurahan">Kelurahan:</label>
        <input type="text" id="kelurahan" name="kelurahan" required>

        <label for="kecamatan">Kecamatan:</label>
        <input type="text" id="kecamatan" name="kecamatan" required>

        <label for="kota">Kota:</label>
        <input type="text" id="kota" name="kota" required>

        <label for="provinsi">Provinsi:</label>
        <input type="text" id="provinsi" name="provinsi" required>

        <label for="tanggal_penerbitan">Tanggal Penerbitan:</label>
        <input type="date" id="tanggal_penerbitan" name="tanggal_penerbitan" required>

        <label for="nik_kepala_keluarga">NIK Kepala Keluarga:</label>
        <input type="text" id="nik_kepala_keluarga" name="nik_kepala_keluarga" required>

        <label for="nama_kepala_keluarga">Nama Kepala Keluarga:</label>
        <input type="text" id="nama_kepala_keluarga" name="nama_kepala_keluarga" required>

        <button type="submit">Daftar Kartu Keluarga</button>
    </form>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2024 SIAK Kota Parepare. Semua hak dilindungi.</p>
        <div class="footer-links">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Syarat dan Ketentuan</a>
        </div>
    </div>
</footer>

</body>
</html>
