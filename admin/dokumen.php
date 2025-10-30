<?php
// Mengimpor file session dan database untuk mengelola sesi pengguna dan koneksi database
include '../config/session.php';
include '../config/database.php'; // Include database connection
redirect_if_not_logged_in(); // Redirect pengguna jika tidak terautentikasi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="../assets/css/dokumen.css"> <!-- CSS untuk dokumen -->
    <style>
        /* Reset CSS */
        * {
            margin: 0; /* Menghilangkan margin default */
            padding: 0; /* Menghilangkan padding default */
            box-sizing: border-box; /* Mengatur box-sizing untuk semua elemen */
            font-family: 'Poppins', sans-serif; /* Mengatur font untuk seluruh halaman */
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed; /* Sidebar tetap di sisi kiri */
            left: 0;
            top: 0;
            height: 100%; /* Tinggi penuh */
            width: 280px; /* Lebar sidebar */
            background: var(--primary-color); /* Warna latar belakang sidebar */
            padding: 20px; /* Ruang di dalam sidebar */
            color: white; /* Warna teks */
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1); /* Bayangan sidebar */
        }

        .sidebar .logo {
            display: flex; /* Mengatur logo dengan flexbox */
            align-items: center; /* Pusatkan secara vertikal */
            gap: 15px; /* Jarak antara ikon dan teks */
            padding: 15px 10px; /* Ruang di dalam logo */
            font-size: 1.6rem; /* Ukuran font logo */
            font-weight: 600; /* Ketebalan font */
            margin-bottom: 30px; /* Jarak bawah logo */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); /* Garis bawah logo */
        }

        .sidebar .menu-item {
            display: flex; /* Mengatur item menu dengan flexbox */
            align-items: center; /* Pusatkan secara vertikal */
            gap: 15px; /* Jarak antara ikon dan teks */
            padding: 15px; /* Ruang di dalam item menu */
            color: rgba(255, 255, 255, 0.8); /* Warna teks item menu */
            text-decoration: none; /* Menghilangkan garis bawah */
            border-radius: 10px; /* Sudut melengkung */
            margin-bottom: 5px; /* Jarak bawah item menu */
            transition: background-color 0.3s; /* Transisi untuk efek hover */
        }

        .sidebar .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Warna latar belakang saat hover */
            color: white; /* Warna teks saat hover */
        }

        /* Tombol Logout Styles */
        .sidebar .logout-btn {
            margin-top: 330px; /* Jarak atas untuk memindahkan tombol ke bawah */
            background-color: rgba(220, 38, 38, 0.9); /* Warna latar belakang tombol */
            color: white; /* Warna teks tombol */
            padding: 15px; /* Ruang di dalam tombol */
            border: none; /* Tanpa garis batas */
            border-radius: 10px; /* Sudut melengkung */
            cursor: pointer; /* Kursor tangan saat hover */
            text-align: center; /* Pusatkan teks */
            width: 100%; /* Lebar penuh */
            font-size: 1rem; /* Ukuran font */
            font-weight: 500; /* Ketebalan font */
            transition: background-color 0.3s, transform 0.2s; /* Transisi untuk efek hover */
            display: flex; /* Flexbox untuk penataan */
            align-items: center; /* Pusatkan secara vertikal */
            justify-content: center; /* Pusatkan secara horizontal */
            gap: 10px; /* Jarak antara ikon dan teks */
        }

        .sidebar .logout-btn:hover {
            background: #dc2626; /* Warna latar belakang saat hover */
            transform: scale(1.05); /* Efek zoom saat hover */
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 280px; /* Jarak kiri untuk konten utama */
            padding: 20px; /* Ruang di dalam konten utama */
            transition: margin-left 0.3s; /* Transisi untuk perubahan margin */
        }

        .header {
            background: white; /* Warna latar belakang header */
            padding: 20px; /* Ruang di dalam header */
            border-radius: 15px; /* Sudut melengkung */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); /* Bayangan header */
            margin-bottom: 30px; /* Jarak bawah header */
        }

        .header h1 {
            font-size: 28px; /* Ukuran font judul */
            color: var(--text-color); /* Warna teks judul */
            font-weight: 600; /* Ketebalan font */
        }

        .header .sub-header {
            color: #6b7280; /* Warna untuk sub-header */
            font-size: 1rem; /* Ukuran font sub-header */
            margin-top: 5px; /* Jarak atas sub-header */
        }

        /* Document Services Styles */
        .document-services {
            display: flex; /* Mengatur layanan dengan flexbox */
            flex-wrap: wrap; /* Membuat layanan responsif */
            gap: 20px; /* Jarak antar layanan */
        }

        .service-card {
            background: white; /* Warna latar belakang kartu layanan */
            border-radius: 8px; /* Sudut melengkung */
            padding: 20px; /* Ruang di dalam kartu layanan */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Bayangan kartu layanan */
            flex: 1 1 calc(30% - 20px); /* Mengatur lebar layanan */
            text-align: center; /* Pusatkan teks dalam kartu layanan */
            transition: transform 0.3s, box-shadow 0.3s; /* Transisi untuk efek hover */
        }

        .service-card:hover {
            transform: translateY(-5px); /* Efek angkat saat hover */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Bayangan lebih dalam saat hover */
        }

        .service-card i {
            font-size: 2rem; /* Ukuran ikon */
            color: var(--primary-color); /* Warna ikon */
            margin-bottom: 10px; /* Jarak bawah ikon */
        }

        .service-card h3 {
            margin: 10px 0; /* Jarak atas dan bawah judul */
            color: #2980b9; /* Warna judul */
        }

        .service-card p {
            color: #6b7280; /* Warna deskripsi */
            font-size: 0.9rem; /* Ukuran font deskripsi */
        }

        /* Footer Styles */
        footer {
            background-color: #2563eb; /* Warna latar belakang footer */
            color: #ecf0f1; /* Warna teks footer */
            text-align: center; /* Pusatkan teks dalam footer */
            padding: 10px; /* Ruang di dalam footer */
            position: relative; /* Posisi footer */
            bottom: 0; /* Menempel di bagian bawah */
            width: 100%; /* Lebar penuh */
            margin-top: 150px; /* Jarak atas footer */
            margin-left: 300px; /* Jarak kiri footer */
            max-width: 1200px; /* Lebar maksimum footer */
        }

        .footer-content {
            margin: 0 auto; /* Pusatkan konten footer */
        }

        .footer-links a {
            color: #ecf0f1; /* Warna tautan footer */
            margin: 0 10px; /* Jarak horizontal antar tautan */
            text-decoration: none; /* Menghilangkan garis bawah */
        }

        .footer-links a:hover {
            text-decoration: underline; /* Garis bawah saat hover */
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%; /* Lebar penuh pada perangkat kecil */
                height: auto; /* Tinggi otomatis */
                position: relative; /* Posisi relatif */
            }

            .main-content {
                margin-left: 0; /* Menghilangkan margin kiri pada perangkat kecil */
            }

            .document-services {
                flex-direction: column; /* Mengubah arah layanan menjadi kolom */
            }

            .service-card {
                flex: 1 1 100%; /* Lebar penuh pada perangkat kecil */
            }
        }
    </style>
</head>
<body>
<nav class="sidebar">
    <div class="logo">
        <i class="fas fa-city"></i>
        <span class="menu-text">SIAK KOTA PAREPARE</span>
    </div>
    <a href="dashboard.php" class="menu-item active"><i class="fas fa-home"></i> Dashboard</a>
    <a href="data_penduduk.php" class="menu-item"><i class="fas fa-users"></i> Data Penduduk</a>
    <a href="dokumen.php" class="menu-item"><i class="fas fa-file-alt"></i> Form Pengajuan</a>
    <!-- Logout button -->
    <form action="../login.php" method="GET" style="margin-top: auto;">
        <button type="submit" name="action" value="logout" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</nav>

<main class="main-content">
    <div class="header">
        <h1>Form Pengajuan</h1>
        <p class="sub-header">Berbagai layanan pencatatan dokumen administratif yang tersedia di SIAK Kota Parepare.</p>
    </div>

    <div class="document-services">
        <a href="../documen/pencatatan_perkawinan.php" class="service-card">
            <i class="fas fa-heart"></i>
            <h3>Pencatatan Perkawinan</h3>
            <p>Layanan pencatatan resmi perkawinan untuk warga Kota Parepare.</p>
        </a>
        <a href="../documen/pencatatan_perceraian.php" class="service-card">
            <i class="fas fa-heart-broken"></i>
            <h3>Pencatatan Perceraian</h3>
            <p>Proses pencatatan perceraian yang sah dan terverifikasi.</p>
        </a>
        <a href="../documen/penerbitan_kk.php" class="service-card">
            <i class="fas fa-users"></i>
            <h3>Penerbitan Kartu Keluarga</h3>
            <p>Pembuatan dan pembaruan Kartu Keluarga dengan mudah.</p>
        </a>
        <a href="../documen/penerbitan_ktp.php" class="service-card">
            <i class="fas fa-id-card"></i>
            <h3>Penerbitan KTP</h3>
            <p>Layanan pembuatan KTP baru atau pembaruan data.</p>
        </a>
        <a href="../documen/akta_kelahiran.php" class="service-card">
            <i class="fas fa-baby"></i>
            <h3>Kutipan Akta Kelahiran</h3>
            <p>Penerbitan dokumen resmi untuk kelahiran penduduk.</p>
        </a>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2024 SIAK Kota Parepare. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </div>
</footer>

</body>
</html>
