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
    $anak_ke_suami = $_POST['anak_ke_suami'];
    $status_perkawinan_suami = $_POST['status_perkawinan_suami'];
    $perkawinan_ke_suami = $_POST['perkawinan_ke_suami'];
    $istri_ke_suami = $_POST['istri_ke_suami'];
    $kewarganegaraan_suami = $_POST['kewarganegaraan_suami'];
    $kebangsaan_suami = $_POST['kebangsaan_suami'];

    // Data Ayah
    $nik_ayah_suami = $_POST['nik_ayah_suami'];
    $nama_ayah_suami = $_POST['nama_ayah_suami'];
    $agama_ayah_suami = $_POST['agama_ayah_suami'];
    $tempat_lahir_ayah_suami = $_POST['tempat_lahir_ayah_suami'];
    $tanggal_lahir_ayah_suami = $_POST['tanggal_lahir_ayah_suami'];
    $alamat_ayah_suami = $_POST['alamat_ayah_suami'];
    $pekerjaan_ayah_suami = $_POST['pekerjaan_ayah_suami'];

    // Data Ibu
    $nik_ibu_suami = $_POST['nik_ibu_suami'];
    $nama_ibu_suami = $_POST['nama_ibu_suami'];
    $agama_ibu_suami = $_POST['agama_ibu_suami'];
    $tempat_lahir_ibu_suami = $_POST['tempat_lahir_ibu_suami'];
    $tanggal_lahir_ibu_suami = $_POST['tanggal_lahir_ibu_suami'];
    $alamat_ibu_suami = $_POST['alamat_ibu_suami'];
    $pekerjaan_ibu_suami = $_POST['pekerjaan_ibu_suami'];

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
    $anak_ke_istri = $_POST['anak_ke_istri'];
    $status_perkawinan_istri = $_POST['status_perkawinan_istri'];
    $perkawinan_ke_istri = $_POST['perkawinan_ke_istri'];
    $kewarganegaraan_istri = $_POST['kewarganegaraan_istri'];
    $kebangsaan_istri = $_POST['kebangsaan_istri'];

    // Data Ayah Istri
    $nik_ayah_istri = $_POST['nik_ayah_istri'];
    $nama_ayah_istri = $_POST['nama_ayah_istri'];
    $agama_ayah_istri = $_POST['agama_ayah_istri'];
    $tempat_lahir_ayah_istri = $_POST['tempat_lahir_ayah_istri'];
    $tanggal_lahir_ayah_istri = $_POST['tanggal_lahir_ayah_istri'];
    $alamat_ayah_istri = $_POST['alamat_ayah_istri'];
    $pekerjaan_ayah_istri = $_POST['pekerjaan_ayah_istri'];

    // Data Ibu Istri
    $nik_ibu_istri = $_POST['nik_ibu_istri'];
    $nama_ibu_istri = $_POST['nama_ibu_istri'];
    $agama_ibu_istri = $_POST['agama_ibu_istri'];
    $tempat_lahir_ibu_istri = $_POST['tempat_lahir_ibu_istri'];
    $tanggal_lahir_ibu_istri = $_POST['tanggal_lahir_ibu_istri'];
    $alamat_ibu_istri = $_POST['alamat_ibu_istri'];
    $pekerjaan_ibu_istri = $_POST['pekerjaan_ibu_istri'];

    // Data Saksi 1
    $nik_saksi1 = $_POST['nik_saksi1'];
    $nama_saksi1 = $_POST['nama_saksi1'];
    $agama_saksi1 = $_POST['agama_saksi1'];
    $tempat_lahir_saksi1 = $_POST['tempat_lahir_saksi1'];
    $alamat_saksi1 = $_POST['alamat_saksi1'];
    $pekerjaan_saksi1 = $_POST['pekerjaan_saksi1'];

    // Data Saksi 2
    $nik_saksi2 = $_POST['nik_saksi2'];
    $nama_saksi2 = $_POST['nama_saksi2'];
    $agama_saksi2 = $_POST['agama_saksi2'];
    $tempat_lahir_saksi2 = $_POST['tempat_lahir_saksi2'];
    $alamat_saksi2 = $_POST['alamat_saksi2'];
    $pekerjaan_saksi2 = $_POST['pekerjaan_saksi2'];

    // Data Perkawinan
    $tanggal_pemberkatan = $_POST['tanggal_pemberkatan'];
    $tanggal_lapor = $_POST['tanggal_lapor'];
    $pukul = $_POST['pukul'];
    $agama_perkawinan = $_POST['agama_perkawinan'];
    $nama_badan_peradilan = $_POST['nama_badan_peradilan'];
    $nomor_putusan = $_POST['nomor_putusan'];
    $tanggal_putusan = $_POST['tanggal_putusan'];
    $nama_pemuka_agama = $_POST['nama_pemuka_agama'];
    $ijin_perwakilan_wna = $_POST['ijin_perwakilan_wna'];
    $jumlah_anak = $_POST['jumlah_anak'];
    $nama_anak = $_POST['nama_anak'];
    $no_akta_kelahiran = $_POST['no_akta_kelahiran'];

    // Siapkan pernyataan SQL
    $sql = "INSERT INTO pencatatan_perkawinan (
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
        anak_ke_suami, 
        status_perkawinan_suami, 
        perkawinan_ke_suami, 
        istri_ke_suami, 
        kewarganegaraan_suami, 
        kebangsaan_suami,
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
        anak_ke_istri, 
        status_perkawinan_istri, 
        perkawinan_ke_istri, 
        kewarganegaraan_istri, 
        kebangsaan_istri,
        nik_ayah_istri, 
        nama_ayah_istri, 
        agama_ayah_istri, 
        tempat_lahir_ayah_istri, 
        tanggal_lahir_ayah_istri, 
        alamat_ayah_istri, 
        pekerjaan_ayah_istri,
        nik_ibu_istri, 
        nama_ibu_istri, 
        agama_ibu_istri, 
        tempat_lahir_ibu_istri, 
        tanggal_lahir_ibu_istri, 
        alamat_ibu_istri, 
        pekerjaan_ibu_istri,
        nik_saksi1, 
        nama_saksi1, 
        agama_saksi1, 
        tempat_lahir_saksi1, 
        alamat_saksi1, 
        pekerjaan_saksi1,
        nik_saksi2, 
        nama_saksi2, 
        agama_saksi2, 
        tempat_lahir_saksi2, 
        alamat_saksi2, 
        pekerjaan_saksi2,
        tanggal_pemberkatan, 
        tanggal_lapor, 
        pukul, 
        agama_perkawinan, 
        nama_badan_peradilan, 
        nomor_putusan, 
        tanggal_putusan, 
        nama_pemuka_agama, 
        ijin_perwakilan_wna, 
        jumlah_anak, 
        nama_anak, 
        no_akta_kelahiran
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
        $anak_ke_suami, 
        '$status_perkawinan_suami', 
        $perkawinan_ke_suami, 
        $istri_ke_suami, 
        '$kewarganegaraan_suami', 
        '$kebangsaan_suami',
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
        $anak_ke_istri, 
        '$status_perkawinan_istri', 
        $perkawinan_ke_istri, 
        '$kewarganegaraan_istri', 
        '$kebangsaan_istri',
        '$nik_ayah_istri', 
        '$nama_ayah_istri', 
        '$agama_ayah_istri', 
        '$tempat_lahir_ayah_istri', 
        '$tanggal_lahir_ayah_istri', 
        '$alamat_ayah_istri', 
        '$pekerjaan_ayah_istri',
        '$nik_ibu_istri', 
        '$nama_ibu_istri', 
        '$agama_ibu_istri', 
        '$tempat_lahir_ibu_istri', 
        '$tanggal_lahir_ibu_istri', 
        '$alamat_ibu_istri', 
        '$pekerjaan_ibu_istri',
        '$nik_saksi1', 
        '$nama_saksi1', 
        '$agama_saksi1', 
        '$tempat_lahir_saksi1', 
        '$alamat_saksi1', 
        '$pekerjaan_saksi1',
        '$nik_saksi2', 
        '$nama_saksi2', 
        '$agama_saksi2', 
        '$tempat_lahir_saksi2', 
        '$alamat_saksi2', 
        '$pekerjaan_saksi2',
        '$tanggal_pemberkatan', 
        '$tanggal_lapor', 
        '$pukul', 
        '$agama_perkawinan', 
        '$nama_badan_peradilan', 
        '$nomor_putusan', 
        '$tanggal_putusan', 
        '$nama_pemuka_agama', 
        '$ijin_perwakilan_wna', 
        $jumlah_anak, 
        '$nama_anak', 
        '$no_akta_kelahiran'
    )";

    // Eksekusi pernyataan
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pencatatan perkawinan berhasil!');</script>";
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
    <title>Pencatatan Perkawinan</title>
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
            <h1>Pencatatan Perkawinan</h1>
            <p class="sub-header">Silakan isi data berikut untuk pencatatan perkawinan.</p>
        </div>

        <form action="" method="POST" class="registration-form">
            <h2>I. DATA SUAMI</h2>
            <label for="nik_suami">Nomor NIK:</label>
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

            <label for="anak_ke_suami">Anak Ke-:</label>
            <input type="number" id="anak_ke_suami" name="anak_ke_suami" required>

            <label for="status_perkawinan_suami">Status Perkawinan Sebelumnya:</label>
            <input type="text" id="status_perkawinan_suami" name="status_perkawinan_suami" required>

            <label for="perkawinan_ke_suami">Perkawinan yang Ke-:</label>
            <input type="number" id="perkawinan_ke_suami" name="perkawinan_ke_suami" required>

            <label for="istri_ke_suami">Istri yang Ke- (bagi yang poligami):</label>
            <input type="number" id="istri_ke_suami" name="istri_ke_suami">

            <label for="kewarganegaraan_suami">Kewarganegaraan:</label>
            <input type="text" id="kewarganegaraan_suami" name="kewarganegaraan_suami" required>

            <label for="kebangsaan_suami">Kebangsaan (bagi WNA):</label>
            <input type="text" id="kebangsaan_suami" name="kebangsaan_suami">

            <h2>II. DATA AYAH DARI SUAMI</h2>
            <label for="nik_ayah_suami">Nomor NIK:</label>
            <input type="text" id="nik_ayah_suami" name="nik_ayah_suami" required>

            <label for="nama_ayah_suami">Nama Lengkap:</label>
            <input type="text" id="nama_ayah_suami" name="nama_ayah_suami" required>

            <label for="agama_ayah_suami">Agama:</label>
            <input type="text" id="agama_ayah_suami" name="agama_ayah_suami" required>

            <label for="tempat_lahir_ayah_suami">Tempat Tanggal Lahir:</label>
            <input type="text" id="tempat_lahir_ayah_suami" name="tempat_lahir_ayah_suami" required>
            <input type="date" id="tanggal_lahir_ayah_suami" name="tanggal_lahir_ayah_suami" required>

            <label for="alamat_ayah_suami">Alamat:</label>
            <input type="text" id="alamat_ayah_suami" name="alamat_ayah_suami" required>

            <label for="pekerjaan_ayah_suami">Pekerjaan:</label>
            <input type="text" id="pekerjaan_ayah_suami" name="pekerjaan_ayah_suami" required>

            <h2>III. DATA IBU DARI SUAMI</h2>
            <label for="nik_ibu_suami">Nomor NIK:</label>
            <input type="text" id="nik_ibu_suami" name="nik_ibu_suami" required>

            <label for="nama_ibu_suami">Nama Lengkap:</label>
            <input type="text" id="nama_ibu_suami" name="nama_ibu_suami" required>

            <label for="agama_ibu_suami">Agama:</label>
            <input type="text" id="agama_ibu_suami" name="agama_ibu_suami" required>

            <label for="tempat_lahir_ibu_suami">Tempat Tanggal Lahir:</label>
            <input type="text" id="tempat_lahir_ibu_suami" name="tempat_lahir_ibu_suami" required>
            <input type="date" id="tanggal_lahir_ibu_suami" name="tanggal_lahir_ibu_suami" required>

            <label for="alamat_ibu_suami">Alamat:</label>
            <input type="text" id="alamat_ibu_suami" name="alamat_ibu_suami" required>

            <label for="pekerjaan_ibu_suami">Pekerjaan:</label>
            <input type="text" id="pekerjaan_ibu_suami" name="pekerjaan_ibu_suami" required>

            <h2>IV. DATA ISTRI</h2>
            <label for="nik_istri">Nomor NIK:</label>
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

            <label for="anak_ke_istri">Anak Ke-:</label>
            <input type="number" id="anak_ke_istri" name="anak_ke_istri" required>

            <label for="status_perkawinan_istri">Status Perkawinan Sebelumnya:</label>
            <input type="text" id="status_perkawinan_istri" name="status_perkawinan_istri" required>

            <label for="perkawinan_ke_istri">Perkawinan yang Ke-:</label>
            <input type="number" id="perkawinan_ke_istri" name="perkawinan_ke_istri" required>

            <label for="kewarganegaraan_istri">Kewarganegaraan:</label>
            <input type="text" id="kewarganegaraan_istri" name="kewarganegaraan_istri" required>

            <label for="kebangsaan_istri">Kebangsaan (bagi WNA):</label>
            <input type="text" id="kebangsaan_istri" name="kebangsaan_istri">

            <h2>V. DATA AYAH DARI ISTRI</h2>
            <label for="nik_ayah_istri">Nomor NIK:</label>
            <input type="text" id="nik_ayah_istri" name="nik_ayah_istri" required>

            <label for="nama_ayah_istri">Nama Lengkap:</label>
            <input type="text" id="nama_ayah_istri" name="nama_ayah_istri" required>

            <label for="agama_ayah_istri">Agama:</label>
            <input type="text" id="agama_ayah_istri" name="agama_ayah_istri" required>

            <label for="tempat_lahir_ayah_istri">Tempat Tanggal Lahir:</label>
            <input type="text" id="tempat_lahir_ayah_istri" name="tempat_lahir_ayah_istri" required>
            <input type="date" id="tanggal_lahir_ayah_istri" name="tanggal_lahir_ayah_istri" required>

            <label for="alamat_ayah_istri">Alamat:</label>
            <input type="text" id="alamat_ayah_istri" name="alamat_ayah_istri" required>

            <label for="pekerjaan_ayah_istri">Pekerjaan:</label>
            <input type="text" id="pekerjaan_ayah_istri" name="pekerjaan_ayah_istri" required>

            <h2>VI. DATA IBU DARI ISTRI</h2>
            <label for="nik_ibu_istri">Nomor NIK:</label>
            <input type="text" id="nik_ibu_istri" name="nik_ibu_istri" required>

            <label for="nama_ibu_istri">Nama Lengkap:</label>
            <input type="text" id="nama_ibu_istri" name="nama_ibu_istri" required>

            <label for="agama_ibu_istri">Agama:</label>
            <input type="text" id="agama_ibu_istri" name="agama_ibu_istri" required>

            <label for="tempat_lahir_ibu_istri">Tempat Tanggal Lahir:</label>
            <input type="text" id="tempat_lahir_ibu_istri" name="tempat_lahir_ibu_istri" required>
            <input type="date" id="tanggal_lahir_ibu_istri" name="tanggal_lahir_ibu_istri" required>

            <label for="alamat_ibu_istri">Alamat:</label>
            <input type="text" id="alamat_ibu_istri" name="alamat_ibu_istri" required>

            <label for="pekerjaan_ibu_istri">Pekerjaan:</label>
            <input type="text" id="pekerjaan_ibu_istri" name="pekerjaan_ibu_istri" required>

            <h2>VII. DATA SAKSI</h2>
            <h3>SAKSI 1</h3>
            <label for="nik_saksi1">Nomor NIK:</label>
            <input type="text" id="nik_saksi1" name="nik_saksi1" required>

            <label for="nama_saksi1">Nama Lengkap:</label>
            <input type="text" id="nama_saksi1" name="nama_saksi1" required>

            <label for="agama_saksi1">Agama:</label>
            <input type="text" id="agama_saksi1" name="agama_saksi1" required>

            <label for="tempat_lahir_saksi1">Tempat Tanggal Lahir:</label>
            <input type="text" id="tempat_lahir_saksi1" name="tempat_lahir_saksi1" required>
            <input type="date" id="tanggal_lahir_saksi1" name="tanggal_lahir_saksi1" required>

            <label for="alamat_saksi1">Alamat:</label>
            <input type="text" id="alamat_saksi1" name="alamat_saksi1" required>

            <label for="pekerjaan_saksi1">Pekerjaan:</label>
            <input type="text" id="pekerjaan_saksi1" name="pekerjaan_saksi1" required>

            <h3>SAKSI 2</h3>
            <label for="nik_saksi2">Nomor NIK:</label>
            <input type="text" id="nik_saksi2" name="nik_saksi2" required>

            <label for="nama_saksi2">Nama Lengkap:</label>
            <input type="text" id="nama_saksi2" name="nama_saksi2" required>

            <label for="agama_saksi2">Agama:</label>
            <input type="text" id="agama_saksi2" name="agama_saksi2" required>

            <label for="tempat_lahir_saksi2">Tempat Tanggal Lahir:</label>
            <input type="text" id="tempat_lahir_saksi2" name="tempat_lahir_saksi2" required>
            <input type="date" id="tanggal_lahir_saksi2" name="tanggal_lahir_saksi2" required>

            <label for="alamat_saksi2">Alamat:</label>
            <input type="text" id="alamat_saksi2" name="alamat_saksi2" required>

            <label for="pekerjaan_saksi2">Pekerjaan:</label>
            <input type="text" id="pekerjaan_saksi2" name="pekerjaan_saksi2" required>

            <h2>VIII. DATA PERKAWINAN</h2>
            <label for="tanggal_pemberkatan">Tanggal Perkawinan:</label>
            <input type="date" id="tanggal_pemberkatan" name="tanggal_pemberkatan" required>

            <label for="tanggal_lapor">Hari, Tanggal, Bulan, Tahun Melapor:</label>
            <input type="date" id="tanggal_lapor" name="tanggal_lapor" required>

            <label for="pukul">Pukul:</label>
            <input type="time" id="pukul" name="pukul" required>

            <label for="agama_perkawinan">Agama:</label>
            <input type="text" id="agama_perkawinan" name="agama_perkawinan" required>

            <label for="nama_badan_peradilan">Nama Badan Peradilan:</label>
            <input type="text" id="nama_badan_peradilan" name="nama_badan_peradilan" required>

            <label for="nomor_putusan">Nomor Putusan Penetapan Peradilan:</label>
            <input type="text" id="nomor_putusan" name="nomor_putusan" required>

            <label for="tanggal_putusan">Tanggal Putusan Penetapan Peradilan:</label>
            <input type="date" id="tanggal_putusan" name="tanggal_putusan" required>

            <label for="nama_pemuka_agama">Nama Pemuka Agama:</label>
            <input type="text" id="nama_pemuka_agama" name="nama_pemuka_agama" required>

            <label for="ijin_perwakilan_wna">Ijin Perwakilan bagi WNA:</label>
            <input type="text" id="ijin_perwakilan_wna" name="ijin_perwakilan_wna">

            <label for="jumlah_anak">Jumlah Anak yang Telah Diakui/Disahkan:</label>
            <input type="number" id="jumlah_anak" name="jumlah_anak" required>

            <label for="nama_anak">Nama Anak:</label>
            <input type="text" id="nama_anak" name="nama_anak" required>

            <label for="no_akta_kelahiran">No Akta Kelahiran:</label>
            <input type="text" id="no_akta_kelahiran" name="no_akta_kelahiran" required>

            <button type="submit">Daftar Perkawinan</button>
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