<?php
include '../config/session.php';
redirect_if_not_logged_in();
include '../config/database.php'; // Sertakan file koneksi database Anda

// Proses data jika formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Data Akta Kelahiran
    $nik_anak = $_POST['nik_anak'];
    $nama_anak = $_POST['nama_anak'];
    $tempat_lahir_anak = $_POST['tempat_lahir_anak'];
    $tanggal_lahir_anak = $_POST['tanggal_lahir_anak'];
    $jenis_kelamin_anak = $_POST['jenis_kelamin_anak'];
    $nik_ayah = $_POST['nik_ayah'];
    $nama_ayah = $_POST['nama_ayah'];
    $nik_ibu = $_POST['nik_ibu'];
    $nama_ibu = $_POST['nama_ibu'];
    $tanggal_lapor = $_POST['tanggal_lapor'];
    $no_akta_kelahiran = $_POST['no_akta_kelahiran'];

    // Siapkan pernyataan SQL
    $sql = "INSERT INTO akta_kelahiran (
        nik_anak, 
        nama_anak, 
        tempat_lahir_anak, 
        tanggal_lahir_anak, 
        jenis_kelamin_anak, 
        nik_ayah, 
        nama_ayah, 
        nik_ibu, 
        nama_ibu, 
        tanggal_lapor, 
        no_akta_kelahiran
    ) VALUES (
        '$nik_anak', 
        '$nama_anak', 
        '$tempat_lahir_anak', 
        '$tanggal_lahir_anak', 
        '$jenis_kelamin_anak', 
        '$nik_ayah', 
        '$nama_ayah', 
        '$nik_ibu', 
        '$nama_ibu', 
        '$tanggal_lapor', 
        '$no_akta_kelahiran'
    )";

    // Eksekusi pernyataan
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pencatatan akta kelahiran berhasil!');</script>";
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
    <title>Pencatatan Akta Kelahiran</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dokumen.css">
    <style>
        /* Gaya Umum untuk Formulir */
        .registration-form {
            max-width: 600px;
            /* Lebar maksimum formulir */
            margin: 20px auto;
            /* Pusatkan formulir */
            padding: 20px;
            /* Ruang di dalam formulir */
            border: 1px solid #ccc;
            /* Garis batas */
            border-radius: 8px;
            /* Sudut melengkung */
            background-color: #f9f9f9;
            /* Warna latar belakang */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* Bayangan */
        }

        /* Gaya untuk Label */
        .registration-form label {
            display: block;
            /* Membuat label menjadi blok */
            margin-bottom: 5px;
            /* Jarak bawah label */
            font-weight: bold;
            /* Tebalkan teks label */
            color: #333;
            /* Warna teks label */
        }

        /* Gaya untuk Input */
        .registration-form input[type="text"],
        .registration-form input[type="date"],
        .registration-form input[type="number"],
        .registration-form textarea,
        .registration-form select {
            width: 100%;
            /* Lebar penuh */
            padding: 10px;
            /* Ruang di dalam input */
            margin-bottom: 15px;
            /* Jarak bawah input */
            border: 1px solid #ccc;
            /* Garis batas */
            border-radius: 4px;
            /* Sudut melengkung */
            font-size: 16px;
            /* Ukuran font */
        }

        /* Gaya untuk Tombol */
        .registration-form button {
            background-color: #4CAF50;
            /* Warna latar belakang tombol */
            color: white;
            /* Warna teks tombol */
            padding: 10px 15px;
            /* Ruang di dalam tombol */
            border: none;
            /* Tanpa garis batas */
            border-radius: 4px;
            /* Sudut melengkung */
            cursor: pointer;
            /* Kursor tangan saat hover */
            font-size: 16px;
            /* Ukuran font */
            transition: background-color 0.3s;
            /* Transisi warna latar belakang */
        }

        /* Gaya untuk Tombol saat Hover */
        .registration-form button:hover {
            background-color: #45a049;
            /* Warna latar belakang saat hover */
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
            <h1>Pencatatan Akta Kelahiran</h1>
            <p class="sub-header">Silakan isi data berikut untuk pencatatan akta kelahiran.</p>
        </div>

        <form action="" method="POST" class="registration-form">
            <h2>I. DATA ANAK</h2>
            <label for="nik_anak">Nomor NIK Anak:</label>
            <input type="text" id="nik_anak" name="nik_anak" required>

            <label for="nama_anak">Nama Lengkap Anak:</label>
            <input type="text" id="nama_anak" name="nama_anak" required>

            <label for="tempat_lahir_anak">Tempat Lahir Anak:</label>
            <input type="text" id="tempat_lahir_anak" name="tempat_lahir_anak" required>

            <label for="tanggal_lahir_anak">Tanggal Lahir Anak:</label>
            <input type="date" id="tanggal_lahir_anak" name="tanggal_lahir_anak" required>

            <label for="jenis_kelamin_anak">Jenis Kelamin:</label>
            <select id="jenis_kelamin_anak" name="jenis_kelamin_anak" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>

            <h2>II. DATA AYAH</h2>
            <label for="nik_ayah">Nomor NIK Ayah:</label>
            <input type="text" id="nik_ayah" name="nik_ayah" required>

            <label for="nama_ayah">Nama Lengkap Ayah:</label>
            <input type="text" id="nama_ayah" name="nama_ayah" required>

            <h2>III. DATA IBU</h2>
            <label for="nik_ibu">Nomor NIK Ibu:</label>
            <input type="text" id="nik_ibu" name="nik_ibu" required>

            <label for="nama_ibu">Nama Lengkap Ibu:</label>
            <input type="text" id="nama_ibu" name="nama_ibu" required>

            <h2>IV. DATA LAINNYA</h2>
            <label for="tanggal_lapor">Tanggal Melapor:</label>
            <input type="date" id="tanggal_lapor" name="tanggal_lapor" required>

            <label for="no_akta_kelahiran">Nomor Akta Kelahiran:</label>
            <input type="text" id="no_akta_kelahiran" name="no_akta_kelahiran" required>

            <button type="submit">Daftar Akta Kelahiran</button>
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