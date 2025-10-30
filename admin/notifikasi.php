<?php
session_start();
include '../config/database.php';
require_once '../includes/notification_functions.php';

// UNTUK TESTING - COMMENT CEK LOGIN DULU
// Cek login dan role admin
/*
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
*/

// Untuk admin = 1
$untuk_admin = 1;

// Inisialisasi variabel dengan nilai default
$stats = [
    'total' => 0,
    'unread' => 0,
    'today' => 0
];

$notifications = [];

try {
    // Ambil data notifikasi
    $notifications = get_notifications($untuk_admin, 50);
    
    // Ambil statistik
    $stats = get_notification_stats($untuk_admin);
    
} catch (Exception $e) {
    error_log("Error loading notifications: " . $e->getMessage());
    $_SESSION['error'] = "Terjadi kesalahan saat memuat notifikasi";
}

// Proses aksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_all_read'])) {
        if (mark_all_as_read($untuk_admin)) {
            $_SESSION['success'] = "Semua notifikasi telah ditandai sebagai dibaca";
        } else {
            $_SESSION['error'] = "Gagal menandai notifikasi sebagai dibaca";
        }
        header("Location: notifikasi.php");
        exit();
    }
}

if (isset($_GET['action'])) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id > 0) {
        switch ($_GET['action']) {
            case 'mark_read':
                if (mark_as_read($id)) {
                    $_SESSION['success'] = "Notifikasi ditandai sebagai dibaca";
                } else {
                    $_SESSION['error'] = "Gagal menandai notifikasi sebagai dibaca";
                }
                break;
                
            case 'delete':
                if (delete_notification($id)) {
                    $_SESSION['success'] = "Notifikasi berhasil dihapus";
                } else {
                    $_SESSION['error'] = "Gagal menghapus notifikasi";
                }
                break;
        }
    }
    
    header("Location: notifikasi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - SIAK Parepare</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --success: #27ae60;
            --warning: #f39c12;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
        }

        body {
            background-color: #f5f6fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .nav-container {
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

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 1.5rem;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background-color: var(--accent);
        }

        /* Page Title */
        .page-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .page-title h1 {
            font-size: 2.5rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .page-title p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Stats Container */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Notifications Container */
        .notifications-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .notifications-header {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notifications-header h2 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        /* Button Styles */
        .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--secondary);
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #219a52;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--accent);
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background: #e67e22;
            transform: translateY(-2px);
        }

        /* Notifications List */
        .notifications-list {
            max-height: 600px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transition: all 0.3s ease;
            position: relative;
        }

        .notification-item:hover {
            background-color: var(--light);
        }

        .notification-item.unread {
            background-color: #e3f2fd;
            border-left: 4px solid var(--secondary);
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        .notification-icon.new {
            background: linear-gradient(135deg, var(--warning), #e67e22);
            color: white;
        }

        .notification-icon.approved {
            background: linear-gradient(135deg, var(--success), #27ae60);
            color: white;
        }

        .notification-icon.rejected {
            background: linear-gradient(135deg, var(--accent), #c0392b);
            color: white;
        }

        .notification-icon.info {
            background: linear-gradient(135deg, var(--info), #138496);
            color: white;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .notification-message {
            color: #666;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .notification-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: #888;
        }

        .notification-time {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .notification-actions {
            display: flex;
            gap: 8px;
        }

        .badge {
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-new {
            background: var(--accent);
            color: white;
        }

        .badge-read {
            background: #6c757d;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            margin-bottom: 0.5rem;
            color: #495057;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: var(--success);
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border-color: var(--accent);
            color: #721c24;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .notifications-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .header-actions {
                flex-direction: column;
                width: 100%;
            }

            .header-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .notification-item {
                flex-direction: column;
                gap: 1rem;
                padding: 1.5rem;
            }

            .notification-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .notification-actions {
                width: 100%;
                justify-content: center;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-item {
            animation: slideIn 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="nav-container">
            <div class="logo">
                <i class="fas fa-landmark"></i>
                <span>SIAK Parepare</span>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="notifikasi.php" class="active">Notifikasi</a></li>
                <li><a href="data_pengajuan.php">Data Pengajuan</a></li>
                <li><a href="verifikasi_pengajuan.php">Verifikasi</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <!-- Page Title -->
        <div class="page-title">
            <h1><i class="fas fa-bell"></i> Notifikasi Sistem</h1>
            <p>Kelola semua notifikasi pengajuan dari user</p>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number" style="color: var(--secondary);"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total Notifikasi</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: var(--accent);"><?php echo $stats['unread']; ?></div>
                <div class="stat-label">Belum Dibaca</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: var(--success);"><?php echo $stats['today']; ?></div>
                <div class="stat-label">Hari Ini</div>
            </div>
        </div>

        <!-- Notifications Container -->
        <div class="notifications-container">
            <div class="notifications-header">
                <h2><i class="fas fa-list"></i> Daftar Notifikasi</h2>
                <div class="header-actions">
                    <form method="POST" style="display: inline;">
                        <button type="submit" name="mark_all_read" class="btn btn-success">
                            <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                    <a href="notifikasi.php" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </a>
                </div>
            </div>

            <div class="notifications-list">
                <?php if (empty($notifications)): ?>
                    <div class="empty-state">
                        <i class="fas fa-bell-slash"></i>
                        <h3>Tidak Ada Notifikasi</h3>
                        <p>Belum ada notifikasi yang tersedia</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notif): ?>
                        <div class="notification-item <?php echo $notif['dibaca'] == 0 ? 'unread pulse' : ''; ?>">
                            <!-- Notification Icon -->
                            <div class="notification-icon 
                                <?php 
                                $judul_lower = strtolower($notif['judul']);
                                if (strpos($judul_lower, 'baru') !== false) echo 'new';
                                elseif (strpos($judul_lower, 'disetujui') !== false) echo 'approved';
                                elseif (strpos($judul_lower, 'ditolak') !== false) echo 'rejected';
                                else echo 'info';
                                ?>">
                                <i class="fas 
                                    <?php 
                                    if (strpos($judul_lower, 'baru') !== false) echo 'fa-exclamation';
                                    elseif (strpos($judul_lower, 'disetujui') !== false) echo 'fa-check';
                                    elseif (strpos($judul_lower, 'ditolak') !== false) echo 'fa-times';
                                    else echo 'fa-info-circle';
                                    ?>">
                                </i>
                            </div>
                            
                            <!-- Notification Content -->
                            <div class="notification-content">
                                <div class="notification-title">
                                    <?php echo htmlspecialchars($notif['judul']); ?>
                                    <span class="badge <?php echo $notif['dibaca'] == 0 ? 'badge-new' : 'badge-read'; ?>">
                                        <?php echo $notif['dibaca'] == 0 ? 'BARU' : 'DIBACA'; ?>
                                    </span>
                                </div>
                                <div class="notification-message">
                                    <?php echo nl2br(htmlspecialchars($notif['pesan'])); ?>
                                </div>
                                <div class="notification-meta">
                                    <span class="notification-time">
                                        <i class="far fa-clock"></i>
                                        <?php echo date('d M Y H:i', strtotime($notif['created_at'])); ?>
                                    </span>
                                    <span>
                                        Tipe: <?php echo $notif['untuk_admin'] == 1 ? 'Admin' : 'User'; ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Notification Actions -->
                            <div class="notification-actions">
                                <?php if ($notif['dibaca'] == 0): ?>
                                    <a href="notifikasi.php?action=mark_read&id=<?php echo $notif['id']; ?>" 
                                       class="btn btn-primary" title="Tandai sebagai dibaca">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="notifikasi.php?action=delete&id=<?php echo $notif['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')"
                                   title="Hapus notifikasi">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh setiap 30 detik
        setInterval(function() {
            location.reload();
        }, 30000);

        // Konfirmasi hapus
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-danger');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
                        e.preventDefault();
                    }
                });
            });
        });

        // Smooth scroll untuk notifikasi baru
        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
        if (unreadNotifications.length > 0) {
            unreadNotifications[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    </script>
</body>
</html>