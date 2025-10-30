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
    <title>Website Pengaduan Masyarakat - Dispendukcapil Bangkalan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }

            .slider {
                height: 60vh;
            }

            .slide h1 {
                font-size: 2.5rem;
            }

            .slide p {
                font-size: 1.1rem;
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
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-landmark"></i>
                <span>DISPENDUKCAPIL BANGKALAN</span>
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="index.php" class="active">HOME</a></li>
                    <li><a href="lapor.php">LAPOR</a></li>
                    <li><a href="pengaduan.php">LIHAT PENGADUAN</a></li>
                    <li><a href="cara.php">CARA</a></li>
                    <li><a href="profil.php">PROFIL DINAS</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="bantuan.php">BANTUAN</a></li>
                    <li><a href="kontak.php">KONTAK</a></li>
                    <li><a href="auth/login.php">login</a></li>
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
                    <p>Website Pengaduan Masyarakat Dispendukcapil Bangkalan</p>
                    <a href="lapor.php" class="cta-button">
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

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- Database Status -->
        <?php if ($database_connected): ?>
            <div class="db-status connected">
                <i class="fas fa-check-circle"></i> Terhubung ke Database: siak_parepare
            </div>
        <?php else: ?>
            <div class="db-status error">
                <i class="fas fa-exclamation-triangle"></i> Gagal terhubung ke database: <?php echo $database_error; ?>
            </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="lapor.php" class="action-btn">
                <i class="fas fa-edit"></i>
                <div>Buat Pengaduan</div>
            </a>
            <a href="pengaduan.php" class="action-btn">
                <i class="fas fa-search"></i>
                <div>Lacak Pengaduan</div>
            </a>
            <a href="cara.php" class="action-btn">
                <i class="fas fa-info-circle"></i>
                <div>Panduan</div>
            </a>
            <a href="faq.php" class="action-btn">
                <i class="fas fa-question-circle"></i>
                <div>FAQ</div>
            </a>
        </div>

        <!-- PENGADUAN TERBARU -->
        <section>
            <div class="section-title">
                <h2>Pengaduan Terbaru</h2>
                <p>Berikut adalah beberapa pengaduan terbaru dari masyarakat</p>
            </div>

            <div class="pengaduan-grid">
                <?php
                // Data dummy untuk pengaduan
                $dummy_pengaduan = [
                    [
                        'judul' => 'Permohonan Perbaikan Data KTP',
                        'deskripsi' => 'Terdapat kesalahan pada tempat lahir di KTP elektronik saya yang perlu diperbaiki segera.',
                        'status' => 'diproses',
                        'tanggal_dibuat' => date('Y-m-d H:i:s', strtotime('-2 days'))
                    ],
                    [
                        'judul' => 'Pengajuan Kartu Keluarga Baru',
                        'deskripsi' => 'Mengajukan pembuatan KK baru karena telah menikah dan memisahkan diri dari orang tua.',
                        'status' => 'selesai',
                        'tanggal_dibuat' => date('Y-m-d H:i:s', strtotime('-5 days'))
                    ],
                    [
                        'judul' => 'Layanan Akta Kelahiran Anak',
                        'deskripsi' => 'Proses pembuatan akta kelahiran untuk anak pertama yang baru lahir bulan lalu.',
                        'status' => 'baru',
                        'tanggal_dibuat' => date('Y-m-d H:i:s', strtotime('-1 day'))
                    ],
                    [
                        'judul' => 'Perubahan Alamat di KTP',
                        'deskripsi' => 'Mengajukan perubahan alamat pada KTP karena telah pindah domisili.',
                        'status' => 'diproses',
                        'tanggal_dibuat' => date('Y-m-d H:i:s', strtotime('-3 days'))
                    ],
                    [
                        'judul' => 'Pencatatan Akta Kematian',
                        'deskripsi' => 'Proses pencatatan akta kematian untuk anggota keluarga yang telah meninggal.',
                        'status' => 'selesai',
                        'tanggal_dibuat' => date('Y-m-d H:i:s', strtotime('-7 days'))
                    ],
                    [
                        'judul' => 'Pengaduan Antrian Berlebihan',
                        'deskripsi' => 'Keluhan mengenai antrian yang sangat panjang di loket pelayanan KTP.',
                        'status' => 'baru',
                        'tanggal_dibuat' => date('Y-m-d H:i:s')
                    ]
                ];

                // Tampilkan data dummy
                foreach ($dummy_pengaduan as $pengaduan) {
                    $status_class = '';
                    switch ($pengaduan['status']) {
                        case 'diproses':
                            $status_class = 'process';
                            break;
                        case 'selesai':
                            $status_class = 'done';
                            break;
                        default:
                            $status_class = 'new';
                    }
                    
                    echo "
                    <div class='pengaduan-card'>
                        <h3>" . htmlspecialchars($pengaduan['judul']) . "</h3>
                        <div class='date'>
                            <i class='far fa-calendar'></i> " . date('d M Y', strtotime($pengaduan['tanggal_dibuat'])) . "
                        </div>
                        <p>" . substr(htmlspecialchars($pengaduan['deskripsi']), 0, 100) . "...</p>
                        <div class='status {$status_class}'>
                            " . ucfirst($pengaduan['status']) . "
                        </div>
                    </div>";
                }
                ?>
            </div>
        </section>

        <!-- FITUR LAYANAN -->
        <section>
            <div class="section-title">
                <h2>Layanan Kami</h2>
                <p>Berbagai layanan yang tersedia di Dispendukcapil Bangkalan</p>
            </div>

            <div class="features">
                <div class="feature-card">
                    <i class="fas fa-id-card"></i>
                    <h3>KTP Elektronik</h3>
                    <p>Pelayanan pembuatan dan perpanjangan KTP Elektronik</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h3>Kartu Keluarga</h3>
                    <p>Penerbitan dan perubahan Kartu Keluarga</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-baby"></i>
                    <h3>Akta Kelahiran</h3>
                    <p>Pencatatan dan penerbitan Akta Kelahiran</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-book"></i>
                    <h3>Akta Kematian</h3>
                    <p>Pencatatan dan penerbitan Akta Kematian</p>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Kontak Kami</h3>
                <p><i class="fas fa-map-marker-alt"></i> Jl. Raya Bangkalan No. 123</p>
                <p><i class="fas fa-phone"></i> (031) 1234567</p>
                <p><i class="fas fa-envelope"></i> info@dispendukcapil-bangkalan.go.id</p>
            </div>
            
            <div class="footer-section">
                <h3>Link Cepat</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="lapor.php">Buat Pengaduan</a></li>
                    <li><a href="pengaduan.php">Lacak Pengaduan</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Jam Layanan</h3>
                <p>Senin - Kamis: 08.00 - 15.00</p>
                <p>Jumat: 08.00 - 11.00</p>
                <p>Sabtu: 08.00 - 13.00</p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 Dispendukcapil Bangkalan. All rights reserved.</p>
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