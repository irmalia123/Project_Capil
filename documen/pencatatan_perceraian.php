<?php
include '../config/session.php';
redirect_if_not_logged_in();
include '../config/database.php'; // Sertakan file koneksi database Anda

// Proses data jika formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Data Suami
    $nik_suami = $_POST['nik_suami'];
    $kk_suami = $_POST['kk_suami'];
    $paspor_suami = $_POST['paspor_suami'];
    $nama_suami = $_POST['nama_suami'];
    $tempat_lahir_suami = $_POST['tempat_lahir_suami'];
    $tanggal_lahir_suami = $_POST['tanggal_lahir_suami'];
    $alamat_suami = $_POST['alamat_suami'];
    $pendidikan_terakhir_suami = $_POST['pendidikan_terakhir_suami'];
    $agama_suami = $_POST['agama_suami'];
    $pekerjaan_suami = $_POST['pekerjaan_suami'];
    $perceraian_ke_suami = $_POST['perceraian_ke_suami'];
    $kewarganegaraan_suami = $_POST['kewarganegaraan_suami'];

    // Data Istri
    $nik_istri = $_POST['nik_istri'];
    $kk_istri = $_POST['kk_istri'];
    $paspor_istri = $_POST['paspor_istri'];
    $nama_istri = $_POST['nama_istri'];
    $tempat_lahir_istri = $_POST['tempat_lahir_istri'];
    $tanggal_lahir_istri = $_POST['tanggal_lahir_istri'];
    $alamat_istri = $_POST['alamat_istri'];
    $pendidikan_terakhir_istri = $_POST['pendidikan_terakhir_istri'];
    $agama_istri = $_POST['agama_istri'];
    $pekerjaan_istri = $_POST['pekerjaan_istri'];
    $kewarganegaraan_istri = $_POST['kewarganegaraan_istri'];

    // Data Perceraian
    $pengaju_perceraian = $_POST['pengaju_perceraian'];
    $nomor_akta_perkawinan = $_POST['nomor_akta_perkawinan'];
    $tempat_pencatatan_perkawinan = $_POST['tempat_pencatatan_perkawinan'];
    $nomor_putusan_pengadilan = $_POST['nomor_putusan_pengadilan'];
    $tanggal_keputusan_pengadilan = $_POST['tanggal_keputusan_pengadilan'];
    $nama_peradilan = $_POST['nama_peradilan'];
    $nama_lembaga_putusan = $_POST['nama_lembaga_putusan'];
    $sebab_perceraian = $_POST['sebab_perceraian'];
    $tanggal_lapor = $_POST['tanggal_lapor'];

    // Siapkan pernyataan SQL
    $sql = "INSERT INTO pencatatan_perceraian (
        nik_suami, 
        kk_suami, 
        paspor_suami, 
        nama_suami, 
        tempat_lahir_suami, 
        tanggal_lahir_suami, 
        alamat_suami, 
        pendidikan_terakhir_suami, 
        agama_suami, 
        pekerjaan_suami, 
        perceraian_ke_suami, 
        kewarganegaraan_suami, 
        nik_istri, 
        kk_istri, 
        paspor_istri, 
        nama_istri, 
        tempat_lahir_istri, 
        tanggal_lahir_istri, 
        alamat_istri, 
        pendidikan_terakhir_istri, 
        agama_istri, 
        pekerjaan_istri, 
        kewarganegaraan_istri, 
        pengaju_perceraian, 
        nomor_akta_perkawinan, 
        tempat_pencatatan_perkawinan, 
        nomor_putusan_pengadilan, 
        tanggal_keputusan_pengadilan, 
        nama_peradilan, 
        nama_lembaga_putusan, 
        sebab_perceraian, 
        tanggal_lapor
    ) VALUES (
        '$nik_suami', 
        '$kk_suami', 
        '$paspor_suami', 
        '$nama_suami', 
        '$tempat_lahir_suami', 
        '$tanggal_lahir_suami', 
        '$alamat_suami', 
        '$pendidikan_terakhir_suami', 
        '$agama_suami', 
        '$pekerjaan_suami', 
        $perceraian_ke_suami, 
        '$kewarganegaraan_suami', 
        '$nik_istri', 
        '$kk_istri', 
        '$paspor_istri', 
        '$nama_istri', 
        '$tempat_lahir_istri', 
        '$tanggal_lahir_istri', 
        '$alamat_istri', 
        '$pendidikan_terakhir_istri', 
        '$agama_istri', 
        '$pekerjaan_istri', 
        '$kewarganegaraan_istri', 
        '$pengaju_perceraian', 
        '$nomor_akta_perkawinan', 
        '$tempat_pencatatan_perkawinan', 
        '$nomor_putusan_pengadilan', 
        '$tanggal_keputusan_pengadilan', 
        '$nama_peradilan', 
        '$nama_lembaga_putusan', 
        '$sebab_perceraian', 
        '$tanggal_lapor'
    )";

    // Eksekusi pernyataan
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pencatatan perceraian berhasil!');</script>";
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
    <title>Pencatatan Perceraian</title>
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
        .registration-form input[type="number"] {
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
        <h1>Pencatatan Perceraian</h1>
        <p class="sub-header">Silakan isi data berikut untuk pencatatan perceraian.</p>
    </div>

    <form action="" method="POST" class="registration-form">
        <h2>I. DATA SUAMI</h2>
        <label for="nik_suami">NIK:</label>
        <input type="text" id="nik_suami" name="nik_suami" required>

        <label for="kk_suami">Nomor Kartu Keluarga:</label>
        <input type="text" id="kk_suami" name="kk_suami" required>

        <label for="paspor_suami">Nomor Paspor:</label>
        <input type="text" id="paspor_suami" name="paspor_suami">

        <label for="nama_suami">Nama Lengkap:</label>
        <input type="text" id="nama_suami" name="nama_suami" required>

        <label for="tempat_lahir_suami">Tempat Tanggal Lahir:</label>
        <input type="text" id="tempat_lahir_suami" name="tempat_lahir_suami" required>
        <input type="date" id="tanggal_lahir_suami" name="tanggal_lahir_suami" required>

        <label for="alamat_suami">Alamat:</label>
        <input type="text" id="alamat_suami" name="alamat_suami" required>

        <label for="pendidikan_terakhir_suami">Pendidikan Terakhir:</label>
        <input type="text" id="pendidikan_terakhir_suami" name="pendidikan_terakhir_suami" required>

        <label for="agama_suami">Agama:</label>
        <input type="text" id="agama_suami" name="agama_suami" required>

        <label for="pekerjaan_suami">Pekerjaan:</label>
        <input type="text" id="pekerjaan_suami" name="pekerjaan_suami" required>

        <label for="perceraian_ke_suami">Perceraian yang Ke-:</label>
        <input type="number" id="perceraian_ke_suami" name="perceraian_ke_suami" required>

        <label for="kewarganegaraan_suami">Kewarganegaraan:</label>
        <input type="text" id="kewarganegaraan_suami" name="kewarganegaraan_suami" required>

        <h2>II. DATA ISTRI</h2>
        <label for="nik_istri">NIK:</label>
        <input type="text" id="nik_istri" name="nik_istri" required>

        <label for="kk_istri">Nomor Kartu Keluarga:</label>
        <input type="text" id="kk_istri" name="kk_istri" required>

        <label for="paspor_istri">Nomor Paspor:</label>
        <input type="text" id="paspor_istri" name="paspor_istri">

        <label for="nama_istri">Nama Lengkap:</label>
        <input type="text" id="nama_istri" name="nama_istri" required>

        <label for="tempat_lahir_istri">Tempat Tanggal Lahir:</label>
        <input type="text" id="tempat_lahir_istri" name="tempat_lahir_istri" required>
        <input type="date" id="tanggal_lahir_istri" name="tanggal_lahir_istri" required>

        <label for="alamat_istri">Alamat:</label>
        <input type="text" id="alamat_istri" name="alamat_istri" required>

        <label for="pendidikan_terakhir_istri">Pendidikan Terakhir:</label>
        <input type="text" id="pendidikan_terakhir_istri" name="pendidikan_terakhir_istri" required>

        <label for="agama_istri">Agama:</label>
        <input type="text" id="agama_istri" name="agama_istri" required>

        <label for="pekerjaan_istri">Pekerjaan:</label>
        <input type="text" id="pekerjaan_istri" name="pekerjaan_istri" required>

        <label for="kewarganegaraan_istri">Kewarganegaraan:</label>
        <input type="text" id="kewarganegaraan_istri" name="kewarganegaraan_istri" required>

        <h2>III. DATA PERCERAIAN</h2>
        <label for="pengaju_perceraian">Yang Mengajukan Perceraian:</label>
        <select id="pengaju_perceraian" name="pengaju_perceraian" required>
            <option value="suami">Suami</option>
            <option value="istri">Istri</option>
        </select>

        <label for="nomor_akta_perkawinan">Nomor Akta Perkawinan:</label>
        <input type="text" id="nomor_akta_perkawinan" name="nomor_akta_perkawinan" required>

        <label for="tempat_pencatatan_perkawinan">Tempat Pencatatan Perkawinan:</label>
        <input type="text" id="tempat_pencatatan_perkawinan" name="tempat_pencatatan_perkawinan" required>

        <label for="nomor_putusan_pengadilan">Nomor Putusan Pengadilan:</label>
        <input type="text" id="nomor_putusan_pengadilan" name="nomor_putusan_pengadilan" required>

        <label for="tanggal_keputusan_pengadilan">Tanggal Keputusan Pengadilan:</label>
        <input type="date" id="tanggal_keputusan_pengadilan" name="tanggal_keputusan_pengadilan" required>

        <label for="nama_peradilan">Nama Peradilan yang Memutus Perkara:</label>
        <input type="text" id="nama_peradilan" name="nama_peradilan" required>

        <label for="nama_lembaga_putusan">Nama Lembaga yang Menerbitkan Putusan Perceraian:</label>
        <input type="text" id="nama_lembaga_putusan" name="nama_lembaga_putusan" required>

        <label for="sebab_perceraian">Sebab Perceraian:</label>
        <textarea id="sebab_perceraian" name="sebab_perceraian" required></textarea>

        <label for="tanggal_lapor">Hari, Tanggal, Bulan, Tahun Melapor:</label>
        <input type="date" id="tanggal_lapor" name="tanggal_lapor" required>

        <button type="submit">Daftar Perceraian</button>
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
