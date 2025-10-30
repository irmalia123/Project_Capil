<?php
// Mengimpor file session dan database untuk mengelola sesi pengguna dan koneksi database
include '../config/session.php';
include '../config/database.php'; // Include your database connection
redirect_if_not_logged_in(); // Redirect pengguna jika tidak terautentikasi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIAK KOTA PAREPARE</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="../assets/css/dashboard.css"> <!-- CSS untuk dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Library untuk grafik -->
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif; /* Mengatur font untuk seluruh halaman */
        }

        :root {
            /* Variabel warna untuk tema */
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --text-color: #1f2937;
            --bg-light: #f3f4f6;
            --transition: all 0.3s ease; /* Transisi untuk efek hover */
        }

        body {
            background-color: var(--bg-light); /* Warna latar belakang */
            min-height: 100vh; /* Memastikan tinggi minimal halaman */
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
            transition: var(--transition); /* Transisi untuk efek hover */
        }

        .sidebar .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Warna latar belakang saat hover */
            color: white; /* Warna teks saat hover */
            transform: translateX(5px); /* Efek geser saat hover */
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
            transition: var(--transition); /* Transisi untuk efek hover */
            display: flex; /* Flexbox untuk penataan */
            align-items: center; /* Pusatkan secara vertikal */
            justify-content: center; /* Pusatkan secara horizontal */
            gap: 10px; /* Jarak antara ikon dan teks */
        }

        .sidebar .logout-btn:hover {
            background: #dc2626; /* Warna latar belakang saat hover */
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

        /* Dashboard Stats Styles */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white; /* Warna latar belakang kartu statistik */
            border-radius: 12px; /* Sudut melengkung */
            padding: 25px; /* Ruang di dalam kartu */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Bayangan kartu */
            text-align: center; /* Pusatkan teks dalam kartu */
            transition: transform 0.3s, box-shadow 0.3s; /* Transisi untuk efek hover */
            border-left: 4px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px); /* Efek angkat saat hover */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15); /* Bayangan lebih dalam saat hover */
        }

        .stat-card h3 {
            margin-bottom: 10px; /* Jarak bawah judul */
            color: #4b5563; /* Warna judul */
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .number {
            font-size: 2.5rem; /* Ukuran font untuk angka */
            font-weight: bold; /* Ketebalan font */
            color: var(--primary-color); /* Warna angka */
            margin-bottom: 10px;
        }

        .stat-card .icon {
            font-size: 2rem; /* Ukuran ikon */
            color: var(--accent-color); /* Warna ikon */
            margin-bottom: 10px;
        }

        /* Charts Container */
        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-box {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .chart-box h2 {
            margin-bottom: 20px;
            color: var(--text-color);
            font-size: 1.3rem;
            font-weight: 600;
        }

        .chart-wrapper {
            height: 300px;
            position: relative;
        }

        /* Recent Activities Styles */
        .recent-activities {
            background-color: white; /* Warna latar belakang aktivitas terkini */
            border-radius: 12px; /* Sudut melengkung */
            padding: 25px; /* Ruang di dalam aktivitas terkini */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Bayangan aktivitas terkini */
        }

        .recent-activities h2 {
            margin-bottom: 20px;
            color: var(--text-color);
            font-size: 1.3rem;
            font-weight: 600;
        }

        .activity-list {
            list-style: none; /* Menghilangkan bullet point */
        }

        .activity-item {
            display: flex; /* Mengatur item aktivitas dengan flexbox */
            align-items: center; /* Pusatkan secara vertikal */
            padding: 15px 0; /* Ruang atas dan bawah item aktivitas */
            border-bottom: 1px solid #e5e7eb; /* Garis bawah item aktivitas */
            transition: background-color 0.2s; /* Transisi untuk efek hover */
        }

        .activity-item:last-child {
            border-bottom: none; /* Menghilangkan garis bawah untuk item terakhir */
        }

        .activity-item:hover {
            background-color: #f8fafc; /* Warna latar belakang saat hover */
        }

        .activity-icon {
            margin-right: 15px; /* Jarak kanan ikon aktivitas */
            color: var(--primary-color); /* Warna ikon aktivitas */
            font-size: 1.2rem;
        }

        /* Footer Styles */
        footer {
            background-color: #2563eb; /* Warna latar belakang footer */
            color: #ecf0f1; /* Warna teks footer */
            text-align: center; /* Pusatkan teks dalam footer */
            padding: 15px 0; /* Ruang di dalam footer */
            margin-top: 40px; /* Jarak atas footer */
            margin-left: 280px; /* Jarak kiri footer */
            border-radius: 8px;
        }

        .footer-content {
            margin: 0 auto; /* Pusatkan konten footer */
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%; /* Lebar penuh pada perangkat kecil */
                height: auto; /* Tinggi otomatis */
                position: relative; /* Posisi relatif */
            }

            .main-content {
                margin-left: 0; /* Menghilangkan margin kiri pada perangkat kecil */
            }

            .dashboard-stats {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            footer {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<!-- SIDEBAR -->  
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

<!-- HALAMAN UTAMA -->
<main class="main-content">
    <div class="header">
        <h1>Dashboard</h1>
    </div>

    <div class="dashboard-stats">
        <?php
        // Query untuk mendapatkan statistik penduduk
        $total_query = "SELECT COUNT(*) as total FROM data_penduduk";
        $male_query = "SELECT COUNT(*) as total FROM data_penduduk WHERE jenis_kelamin = 'L'";
        $female_query = "SELECT COUNT(*) as total FROM data_penduduk WHERE jenis_kelamin = 'P'";

        $total_result = mysqli_query($conn, $total_query);
        $male_result = mysqli_query($conn, $male_query);
        $female_result = mysqli_query($conn, $female_query);

        $total_data = mysqli_fetch_assoc($total_result);
        $male_data = mysqli_fetch_assoc($male_result);
        $female_data = mysqli_fetch_assoc($female_result);
        ?>
        
        <!-- TOTAL PENDUDUK -->
        <div class="stat-card">
            <i class="fas fa-users icon"></i>
            <div class="number"><?php echo number_format($total_data['total']); ?></div>
            <h3>Total Penduduk</h3>
        </div>
        
        <!-- PENDUDUK LAKI LAKI -->
        <div class="stat-card">
            <i class="fas fa-male icon"></i>
            <div class="number"><?php echo number_format($male_data['total']); ?></div>
            <h3>Penduduk Laki-laki</h3>
        </div>
        
        <!-- PENDUDUK PEREMPUAN -->
        <div class="stat-card">
            <i class="fas fa-female icon"></i>
            <div class="number"><?php echo number_format($female_data['total']); ?></div>
            <h3>Penduduk Perempuan</h3>
        </div>

        <!-- Statistik untuk Akta Kelahiran -->
        <?php
        $akta_kelahiran_query = "SELECT COUNT(*) as total FROM akta_kelahiran";
        $akta_kelahiran_result = mysqli_query($conn, $akta_kelahiran_query);
        $akta_kelahiran_data = mysqli_fetch_assoc($akta_kelahiran_result);
        ?>
        <div class="stat-card">
            <i class="fas fa-baby icon"></i>
            <div class="number"><?php echo number_format($akta_kelahiran_data['total']); ?></div>
            <h3>Akta Kelahiran</h3>
        </div>

        <!-- Statistik untuk Pencatatan Perceraian -->
        <?php
        $perceraian_query = "SELECT COUNT(*) as total FROM pencatatan_perceraian";
        $perceraian_result = mysqli_query($conn, $perceraian_query);
        $perceraian_data = mysqli_fetch_assoc($perceraian_result);
        ?>
        <div class="stat-card">
            <i class="fas fa-user-slash icon"></i>
            <div class="number"><?php echo number_format($perceraian_data['total']); ?></div>
            <h3>Pencatatan Perceraian</h3>
        </div>

        <!-- Statistik untuk Pencatatan Perkawinan -->
        <?php
        $perkawinan_query = "SELECT COUNT(*) as total FROM pencatatan_perkawinan";
        $perkawinan_result = mysqli_query($conn, $perkawinan_query);
        $perkawinan_data = mysqli_fetch_assoc($perkawinan_result);
        ?>
        <div class="stat-card">
            <i class="fas fa-ring icon"></i>
            <div class="number"><?php echo number_format($perkawinan_data['total']); ?></div>
            <h3>Pencatatan Perkawinan</h3>
        </div>

        <!-- Statistik untuk Penerbitan KK -->
        <?php
        $kk_query = "SELECT COUNT(*) as total FROM penerbitan_kk";
        $kk_result = mysqli_query($conn, $kk_query);
        $kk_data = mysqli_fetch_assoc($kk_result);
        ?>
        <div class="stat-card">
            <i class="fas fa-id-card icon"></i>
            <div class="number"><?php echo number_format($kk_data['total']); ?></div>
            <h3>Penerbitan KK</h3>
        </div>

        <!-- Statistik untuk Penerbitan KTP -->
        <?php
        $ktp_query = "SELECT COUNT(*) as total FROM penerbitan_ktp";
        $ktp_result = mysqli_query($conn, $ktp_query);
        $ktp_data = mysqli_fetch_assoc($ktp_result);
        ?>
        <div class="stat-card">
            <i class="fas fa-address-card icon"></i>
            <div class="number"><?php echo number_format($ktp_data['total']); ?></div>
            <h3>Penerbitan KTP</h3>
        </div>
    </div>

    <!-- Grafik Statistik -->
    <div class="charts-container">
        <!-- Grafik Utama -->
        <div class="chart-box">
            <h2>Statistik Data Kependudukan</h2>
            <div class="chart-wrapper">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

        <!-- Grafik Pie -->
        <div class="chart-box">
            <h2>Distribusi Jenis Kelamin</h2>
            <div class="chart-wrapper">
                <canvas id="genderChart"></canvas>
            </div>
        </div>
    </div>

    <!-- AKTIVITAS TERKINI -->
    <div class="recent-activities">
        <h2>Aktivitas Terkini</h2>
        <ul class="activity-list">
            <?php
            // Query untuk mendapatkan aktivitas terbaru
            $recent_query = "SELECT * FROM data_penduduk ORDER BY nik DESC LIMIT 5";
            $recent_result = mysqli_query($conn, $recent_query);

            while ($activity = mysqli_fetch_assoc($recent_result)) {
                echo "<li class='activity-item'>
                        <div class='activity-icon'>
                            <i class='fas fa-user-plus'></i>
                        </div>
                        <div>
                            <strong>{$activity['nama']}</strong> telah terdaftar
                            <div style='color: #6b7280; font-size: 0.9rem;'>NIK: {$activity['nik']}</div>
                        </div>
                      </li>";
            }
            ?>
        </ul>
    </div>
</main>

<!-- FOOTER -->
<footer>
    <div class="footer-content">
        <p>&copy; 2024 SIAK Kota Parepare. All rights reserved.</p>
    </div>
</footer>

<script>
    // Data untuk grafik
    const totalPenduduk = <?php echo $total_data['total']; ?>;
    const totalLakiLaki = <?php echo $male_data['total']; ?>;
    const totalPerempuan = <?php echo $female_data['total']; ?>;
    const totalAktaKelahiran = <?php echo $akta_kelahiran_data['total']; ?>;
    const totalPerceraian = <?php echo $perceraian_data['total']; ?>;
    const totalPerkawinan = <?php echo $perkawinan_data['total']; ?>;
    const totalKK = <?php echo $kk_data['total']; ?>;
    const totalKTP = <?php echo $ktp_data['total']; ?>;

    // Grafik Utama (Bar Chart)
    const mainCtx = document.getElementById('mainChart').getContext('2d');
    const mainChart = new Chart(mainCtx, {
        type: 'bar',
        data: {
            labels: ['Akta Kelahiran', 'Perceraian', 'Perkawinan', 'Penerbitan KK', 'Penerbitan KTP'],
            datasets: [{
                label: 'Jumlah Dokumen',
                data: [totalAktaKelahiran, totalPerceraian, totalPerkawinan, totalKK, totalKTP],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(239, 68, 68, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(139, 92, 246, 1)'
                ],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.2)',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        color: '#6b7280'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280'
                    }
                }
            }
        }
    });

    // Grafik Pie (Jenis Kelamin)
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    const genderChart = new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [totalLakiLaki, totalPerempuan],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(236, 72, 153, 1)'
                ],
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#6b7280',
                        font: {
                            size: 12
                        },
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white'
                }
            },
            cutout: '60%'
        }
    });

    // Animasi untuk stat cards
    document.addEventListener('DOMContentLoaded', function() {
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in');
        });
    });
</script>

<style>
    .fade-in {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

</body>
</html>