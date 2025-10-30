<?php
include '../config/session.php';
redirect_if_not_logged_in();
include '../config/database.php'; // Sertakan file koneksi database Anda

// Fungsi untuk mengupload file gambar
function uploadFile($file, $target_dir) {
    $target_file = $target_dir . basename($file['name']);

    // Cek apakah file gambar adalah gambar
    if (getimagesize($file['tmp_name']) === false) {
        echo "<script>alert('File " . htmlspecialchars($file['name']) . " bukan gambar.');</script>";
        return false;
    }

    // Cek ukuran file
    if ($file['size'] > 500000) { // 500KB
        echo "<script>alert('Maaf, ukuran file " . htmlspecialchars($file['name']) . " terlalu besar.');</script>";
        return false;
    }

    // Coba untuk mengupload file
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $target_file; // Kembalikan path file yang diupload
    }

    // Jika terjadi kesalahan saat mengupload, tampilkan pesan kesalahan
    echo "<script>alert('Maaf, terjadi kesalahan saat mengupload file " . htmlspecialchars($file['name']) . ".');</script>";
    return false;
}


// Proses data jika formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Data KTP
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $agama = $_POST['agama'];
    $pekerjaan = $_POST['pekerjaan'];
    $kewarganegaraan = $_POST['kewarganegaraan'];
    $tanggal_penerbitan = $_POST['tanggal_penerbitan'];
    $nomor_ktp = $_POST['nomor_ktp'];

    // Direktori untuk menyimpan file
    $target_dir = "uploads/";

    // Upload foto 3x4
    $foto_3x4_path = uploadFile($_FILES['foto_3x4'], $target_dir);
    if ($foto_3x4_path) {
        // Upload foto tanda tangan
        $foto_tanda_tangan_path = uploadFile($_FILES['foto_tanda_tangan'], $target_dir);
        if ($foto_tanda_tangan_path) {
            // Siapkan pernyataan SQL
            $sql = "INSERT INTO penerbitan_ktp (
                nik, 
                nama, 
                tempat_lahir, 
                tanggal_lahir, 
                jenis_kelamin, 
                alamat, 
                agama, 
                pekerjaan, 
                kewarganegaraan, 
                tanggal_penerbitan, 
                nomor_ktp, 
                foto_3x4, 
                foto_tanda_tangan
            ) VALUES (
                '$nik', 
                '$nama', 
                '$tempat_lahir', 
                '$tanggal_lahir', 
                '$jenis_kelamin', 
                '$alamat', 
                '$agama', 
                '$pekerjaan', 
                '$kewarganegaraan', 
                '$tanggal_penerbitan', 
                '$nomor_ktp', 
                '$foto_3x4_path', 
                '$foto_tanda_tangan_path'
            )";

            // Eksekusi pernyataan
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Penerbitan KTP berhasil!');</script>";
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerbitan KTP</title>
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
        .registration-form input[type="file"],
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
        <h1>Penerbitan KTP</h1>
        <p class="sub-header">Silakan isi data berikut untuk penerbitan KTP.</p>
    </div>

    <form action="" method="POST" class="registration-form" enctype="multipart/form-data">
        <h2>I. DATA KTP</h2>
        <label for="nik">Nomor NIK:</label>
        <input type="text" id="nik" name="nik" required>

        <label for="nama">Nama Lengkap:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="tempat_lahir">Tempat Lahir:</label>
        <input type="text" id="tempat_lahir" name="tempat_lahir" required>

        <label for="tanggal_lahir">Tanggal Lahir:</label>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>

        <label for="jenis_kelamin">Jenis Kelamin:</label>
        <select id="jenis_kelamin" name="jenis_kelamin" required>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>

        <label for="alamat">Alamat:</label>
        <input type="text" id="alamat" name="alamat" required>

        <label for="agama">Agama:</label>
        <input type="text" id="agama" name="agama" required>

        <label for="pekerjaan">Pekerjaan:</label>
        <input type="text" id="pekerjaan" name="pekerjaan" required>

        <label for="kewarganegaraan">Kewarganegaraan:</label>
        <input type="text" id="kewarganegaraan" name="kewarganegaraan" required>

        <label for="tanggal_penerbitan">Tanggal Penerbitan:</label>
        <input type="date" id="tanggal_penerbitan" name="tanggal_penerbitan" required>

        <label for="nomor_ktp">Nomor KTP:</label>
        <input type="text" id="nomor_ktp" name="nomor_ktp" required>

        <label for="foto_3x4">Upload Foto 3x4:</label>
        <input type="file" id="foto_3x4" name="foto_3x4" accept="image/*" required>

        <label for="foto_tanda_tangan">Upload Foto Tanda Tangan:</label>
        <input type="file" id="foto_tanda_tangan" name="foto_tanda_tangan" accept="image/*" required>

        <button type="submit">Daftar KTP</button>
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
