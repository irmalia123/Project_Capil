<?php
// KONEKSI DATABASE LANGSUNG
$host = "localhost";
$username = "root";
$password = "";
$dbname = "siak_parepare";

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    $database_connected = false;
    $database_error = $conn->connect_error;
} else {
    $database_connected = true;
    $database_error = "";
}

// Fungsi untuk redirect jika belum login (untuk halaman public tidak perlu redirect)
function redirect_if_not_logged_in() {
    // Tidak melakukan redirect untuk halaman public
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Pengaduan Masyarakat - Disdukcapil Parepare</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --text-color: #2c3e50;
            --light-bg: #ecf0f1;
            --white: #ffffff;
            --transition: all 0.3s ease;
        }

        body {
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .logo i {
            font-size: 2rem;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-menu a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: var(--transition);
        }

        .nav-menu a:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .nav-menu a.active {
            background-color: var(--accent-color);
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .menu-toggle span {
            width: 25px;
            height: 3px;
            background-color: var(--white);
            transition: var(--transition);
        }

        /* Slider Styles */
        .slider {
            position: relative;
            height: 80vh;
            overflow: hidden;
        }

        .slides {
            display: flex;
            transition: transform 0.8s ease-in-out;
            height: 100%;
        }

        .slide {
            min-width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--white);
        }

        .slide-content {
            z-index: 2;
            max-width: 800px;
            padding: 2rem;
        }

        .slide h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
            animation: fadeInUp 1s ease;
        }

        .slide p {
            font-size: 1.4rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
            animation: fadeInUp 1s ease 0.3s both;
        }

        .slide-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            filter: brightness(0.7);
        }

        .slide-1 .slide-bg {
            background: linear-gradient(rgba(44, 62, 80, 0.7), rgba(44, 62, 80, 0.7)), 
                       url('https://images.unsplash.com/photo-1551135049-8a33b42738b4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide-2 .slide-bg {
            background: linear-gradient(rgba(52, 152, 219, 0.7), rgba(41, 128, 185, 0.7)), 
                       url('https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide-3 .slide-bg {
            background: linear-gradient(rgba(231, 76, 60, 0.7), rgba(192, 57, 43, 0.7)), 
                       url('https://images.unsplash.com/photo-1565689228865-c1b3f6b16c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .cta-button {
            display: inline-block;
            background-color: var(--accent-color);
            color: var(--white);
            padding: 1.2rem 2.5rem;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.2rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            animation: fadeInUp 1s ease 0.6s both;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .cta-button:hover {
            background-color: #c0392b;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }

        /* Slider Navigation */
        .slider-nav {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 1rem;
            z-index: 3;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: var(--transition);
        }

        .slider-dot.active {
            background-color: var(--white);
            transform: scale(1.3);
        }

        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            color: var(--white);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            transition: var(--transition);
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slider-arrow:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .slider-arrow.prev {
            left: 2rem;
        }

        .slider-arrow.next {
            right: 2rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            color: var(--primary-color);
        }

        .section-title h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 100px;
            height: 4px;
            background: var(--secondary-color);
            margin: 0.5rem auto;
            border-radius: 2px;
        }

        /* Pengaduan Terbaru */
        .pengaduan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .pengaduan-card {
            background: var(--white);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: var(--transition);
        }

        .pengaduan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .pengaduan-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .pengaduan-card .date {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .pengaduan-card .status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .status.process {
            background-color: #fff3cd;
            color: #856404;
        }

        .status.done {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status.new {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Features Section */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-card i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: var(--white);
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: var(--light-bg);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-section a:hover {
            color: var(--secondary-color);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* TATA CARA PENGADUAN */
        .steps-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 40px;
        }

        .step {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1);
            padding: 25px;
            width: 280px;
            text-align: center;
            transition: 0.3s ease;
        }

        .step:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .step img {
            width: 70px;
            height: 70px;
            background-color: #fff9e6;
            padding: 12px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .step h3 {
            font-size: 16px;
            color: #e67e22;
            margin-bottom: 8px;
        }

        .step p {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .note {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }

        
        /* Login Form Styles */
        .login-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            padding: 60px 20px;
            margin: 4rem 0;
            border-radius: 15px;
            color: var(--white);
        }

        .login-container {
            max-width: 400px;
            margin: 0 auto;
            background: var(--white);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .login-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            text-align: center;
        }

        .login-subtitle {
            font-size: 1rem;
            margin-bottom: 2rem;
            color: #666;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .login-btn {
            width: 100%;
            background-color: var(--accent-color);
            color: var(--white);
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 1rem;
        }

        .login-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .login-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-links a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .login-links a:hover {
            color: var(--primary-color);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .action-btn {
            background: var(--white);
            padding: 1.5rem;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-color);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .action-btn:hover {
            border-color: var(--secondary-color);
            transform: translateY(-3px);
        }

        .action-btn i {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }


        .btn-container {
            text-align: center;
            margin-top: 25px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .action-btn {
            background: var(--white);
            padding: 1.5rem;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-color);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .action-btn:hover {
            border-color: var(--secondary-color);
            transform: translateY(-3px);
        }

        .action-btn i {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        /* Database Status */
        .db-status {
            text-align: center;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
            font-weight: bold;
        }

        .db-status.connected {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .db-status.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* ========== MEDIA QUERIES ========== */
        
        /* Tablet */
        @media (max-width: 1024px) {
            .slide h1 {
                font-size: 2.8rem;
            }
            
            .slide p {
                font-size: 1.2rem;
            }
            
            .features {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Mobile */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: row;
                justify-content: space-between;
                padding: 0 1rem;
            }

            .menu-toggle {
                display: flex;
            }

            .nav-menu {
                position: fixed;
                top: 70px;
                left: -100%;
                flex-direction: column;
                background-color: var(--primary-color);
                width: 100%;
                text-align: center;
                transition: var(--transition);
                box-shadow: 0 10px 27px rgba(0,0,0,0.05);
                padding: 2rem 0;
                gap: 0;
            }

            .nav-menu.active {
                left: 0;
            }

            .nav-menu li {
                margin: 15px 0;
            }

            .slider {
                height: 60vh;
            }

            .slide h1 {
                font-size: 2rem;
            }

            .slide p {
                font-size: 1rem;
            }

            .cta-button {
                padding: 1rem 2rem;
                font-size: 1rem;
            }

            .slider-arrow {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .slider-arrow.prev {
                left: 1rem;
            }

            .slider-arrow.next {
                right: 1rem;
            }

            .main-content {
                padding: 0 1rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .pengaduan-grid {
                grid-template-columns: 1fr;
            }

            .steps-container {
                gap: 15px;
            }

            .step {
                width: 100%;
                max-width: 300px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        /* Small Mobile */
        @media (max-width: 480px) {
            .logo span {
                font-size: 1.2rem;
            }

            .slider {
                height: 50vh;
            }

            .slide h1 {
                font-size: 1.8rem;
            }

            .slide p {
                font-size: 0.9rem;
            }

            .cta-button {
                padding: 0.8rem 1.5rem;
                font-size: 0.9rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .feature-card {
                padding: 1.5rem;
            }

            .step {
                padding: 20px;
            }

            .step img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">
                <img src="img/logopare.png" width="50">
                <i class="fas fa-landmark"></i>
                <span>DISDUKCAPIL Parepare</span>
            </div>
            
            <div class="menu-toggle" id="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            
            <nav>
                <ul class="nav-menu" id="nav-menu">
                    <li><a href="index.php" class="active">HOME</a></li>
                    <li><a href="buat_pengaduan.php" >BUAT PENGADUAN</a></li>
                    <li><a href="auth/login.php">LOGIN ADMIN</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- SLIDER SECTION -->
    <section class="slider">
        <div class="slides">
            <!-- Slide 1 -->
            <div class="slide slide-1">
                <div class="slide-bg"></div>
                <div class="slide-content">
                    <h1>Selamat Datang</h1>
                    <p>Website Pengaduan Masyarakat Disdukcapil Parepare</p>
                    <a href="auth/login.php" class="cta-button">
                        <i class="fas fa-bullhorn"></i> Ajukan Pengaduan
                    </a>
                </div>
            </div>
            
            <!-- Slide 2 -->
            <div class="slide slide-2">
                <div class="slide-bg"></div>
                <div class="slide-content">
                    <h1>Layanan Publik Terpadu</h1>
                    <p>Melayani dengan cepat, transparan, dan akuntabel untuk kenyamanan masyarakat</p>
                    <a href="pengaduan.php" class="cta-button">
                        <i class="fas fa-search"></i> Lacak Pengaduan
                    </a>
                </div>
            </div>
            
            <!-- Slide 3 -->
            <div class="slide slide-3">
                <div class="slide-bg"></div>
                <div class="slide-content">
                    <h1>KTP, KK & Akta</h1>
                    <p>Pelayanan administrasi kependudukan yang mudah dan efisien</p>
                    <a href="cara.php" class="cta-button">
                        <i class="fas fa-info-circle"></i> Panduan Layanan
                    </a>
                </div>
            </div>
        </div>

        <!-- Slider Navigation -->
        <button class="slider-arrow prev" onclick="changeSlide(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slider-arrow next" onclick="changeSlide(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <div class="slider-nav">
            <div class="slider-dot active" onclick="currentSlide(0)"></div>
            <div class="slider-dot" onclick="currentSlide(1)"></div>
            <div class="slider-dot" onclick="currentSlide(2)"></div>
        </div>
    </section>

    <!-- TATA CARA MELAKUKAN PENGADUAN -->
    <section id="tatacara-pengaduan" style="background-color: #fffef6; padding: 50px 20px;">
        <div class="section-title" style="text-align: center;">
            <h2 style="color: #333;">Tata Cara Melakukan Pengaduan</h2>
            <p style="color: #555;">Berikut langkah-langkah yang harus dilakukan masyarakat untuk menyampaikan pengaduan kepada Disdukcapil Parepare secara Online</p>
        </div>

        <div class="steps-container">
            <div class="step">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Persiapkan Dokumen" />
                <h3>Persiapkan Dokumen Pendukung</h3>
            </div>

            <div class="step">
                <img src="https://cdn-icons-png.flaticon.com/512/1828/1828919.png" alt="Isi Formulir" />
                <h3>Isi Formulir Pengaduan</h3>
            </div>

            <div class="step">
                <img src="https://cdn-icons-png.flaticon.com/512/2331/2331949.png" alt="Sampaikan Pengaduan" />
                <h3>Sampaikan Pengaduan</h3>
            </div>

            <div class="step">
                <img src="https://cdn-icons-png.flaticon.com/512/1048/1048948.png" alt="Verifikasi" />
                <h3>Verifikasi dan Pencatatan</h3>
            </div>

            <div class="step">
                <img src="https://cdn-icons-png.flaticon.com/512/483/483361.png" alt="Tindak Lanjut" />
                <h3>Proses Tindak Lanjut</h3>
            </div>

            <div class="step">
                <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" alt="Hasil Pengaduan" />
                <h3 style="color: #2e9e3b;">6. Informasi Hasil Pengaduan</h3>
            </div>
        </div>

        <div class="note">
            <p><strong>Catatan:</strong> Pengaduan tanpa identitas dan bukti pendukung yang jelas tidak dapat diproses untuk menjaga keabsahan laporan.</p>
        </div>
       

    <!-- FITUR LAYANAN -->
    <section>
        <div class="section-title">
            <h2>Layanan Kami</h2>
            <p>Berbagai layanan yang tersedia di Disdukcapil Parepare</p>
        </div>

        <div class="features">
            <div class="feature-card">
                <i class="fas fa-id-card"></i>
                <h3>KTP Elektronik</h3>
                <p>Pelayanan pembuatan dan perpanjangan KTP Elektronik</p>
                <h4>Persyaratan:</h4>
                <ul style="text-align: left; margin-left: 20px;">
                    <li>Fotokopi Kartu Keluarga (KK)</li>
                    <li>Telah berusia 17 tahun atau sudah menikah</li>
                </ul>
            </div>

            <div class="feature-card">
                <i class="fas fa-users"></i>
                <h3>Kartu Keluarga</h3>
                <p>Penerbitan dan perubahan Kartu Keluarga</p>
                <h4>Persyaratan:</h4>
                <ul style="text-align: left; margin-left: 20px;">
                    <li>Fotokopi dokumen pendukung (akta, KTP, surat nikah, dsb.)</li>
                    <li>KK lama (bagi yang melakukan perubahan data)</li>
                </ul>
            </div>

            <div class="feature-card">
                <i class="fas fa-baby"></i>
                <h3>Akta Kelahiran</h3>
                <p>Pencatatan dan penerbitan Akta Kelahiran</p>
                <h4>Persyaratan:</h4>
                <ul style="text-align: left; margin-left: 20px;">
                    <li>Surat keterangan lahir dari rumah sakit/bidan/kelurahan</li>
                    <li>Fotokopi KK dan KTP orang tua</li>
                    <li>Fotokopi buku nikah/akta perkawinan orang tua</li>
                    <li>Formulir permohonan akta kelahiran</li>
                </ul>
            </div>

            <div class="feature-card">
                <i class="fas fa-book"></i>
                <h3>Akta Kematian</h3>
                <p>Pencatatan dan penerbitan Akta Kematian</p>
                <h4>Persyaratan:</h4>
                <ul style="text-align: left; margin-left: 20px;">
                    <li>Surat keterangan kematian dari kelurahan atau rumah sakit</li>
                    <li>Kartu Keluarga (KK)</li>
                    <li>KTP asli yang meninggal dunia</li>
                    <li>Mengisi formulir permohonan akta kematian</li>
                </ul>
            </div>

            <div class="feature-card">
                <i class="fas fa-child"></i>
                <h3>Kartu Identitas Anak (KIA)</h3>
                <p>Pembuatan Kartu Identitas Anak bagi warga usia di bawah 17 tahun.</p>
                <h4>Persyaratan:</h4>
                <ul style="text-align: left; margin-left: 20px;">
                    <li>Fotokopi Akta Kelahiran Anak</li>
                    <li>Fotokopi KK dan KTP orang tua</li>
                    <li>Pas foto anak ukuran 2x3 (2 lembar)</li>
                </ul>
            </div>

            <div class="feature-card">
                <i class="fas fa-exchange-alt"></i>
                <h3>SKPWNI (Pindah Keluar / Pindah Masuk)</h3>
                <p>Pelayanan penerbitan Surat Keterangan Pindah WNI untuk penduduk yang berpindah domisili.</p>
                <h4>Persyaratan:</h4>
                <ul style="text-align: left; margin-left: 20px;">
                    <li>Fotokopi KK dan KTP pemohon</li>
                    <li>Surat pengantar pindah dari daerah asal</li>
                    <li>Alamat lengkap</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Kontak Kami</h3>
                <p><i class="fas fa-map-marker-alt"></i> Jln. Veteran No.16 Kota Parepare</p>
                <p><i class="fas fa-phone"></i> 0811428227-0811415227</p>
                <p><i class="fas fa-envelope"></i> https://disdukcapil.pareparekota.go.id/</p>
            </div>
            
            <div class="footer-section">
                <h3>Link Cepat</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="lapor.php">Buat Pengaduan</a></li>
                    <li><a href="pengaduan.php">Lacak Pengaduan</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Jam Layanan</h3>
                <p>Senin - Kamis: 08.00 - 16.00</p>
                <p>Jumat        : 08.00 - 16.30</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy;Disdukcapil Parepare</p>
        </div>
    </footer>

    <script>
        // Slider Functionality
        let currentSlide = 0;
        const slides = document.querySelector('.slides');
        const dots = document.querySelectorAll('.slider-dot');
        const totalSlides = 3;

        function showSlide(n) {
            currentSlide = (n + totalSlides) % totalSlides;
            slides.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function changeSlide(n) {
            showSlide(currentSlide + n);
        }

        function currentSlide(n) {
            showSlide(n);
        }

        // Auto slide change every 5 seconds
        setInterval(() => {
            changeSlide(1);
        }, 5000);

        // Mobile Menu Toggle
        const mobileMenu = document.getElementById('mobile-menu');
        const navMenu = document.getElementById('nav-menu');

        mobileMenu.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-menu a').forEach(n => n.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            navMenu.classList.remove('active');
        }));

        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Animasi scroll untuk elements
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements untuk animasi
        document.querySelectorAll('.pengaduan-card, .feature-card, .action-btn').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>