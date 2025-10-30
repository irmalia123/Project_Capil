<?php
// data_pengajuan.php - DENGAN NAVBAR KONSISTEN
include '../config/session.php';
include '../config/database.php';
redirect_if_not_logged_in();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengajuan - SIAK KOTA PAREPARE</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --text-color: #1f2937;
            --bg-light: #f3f4f6;
            --transition: all 0.3s ease;
        }

        body {
            background-color: var(--bg-light);
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 280px;
            background: var(--primary-color);
            padding: 20px;
            color: white;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 10px;
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .menu-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: var(--transition);
        }

        .sidebar .menu-item:hover,
        .sidebar .menu-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        /* Tombol Logout Styles */
        .sidebar .logout-btn {
            margin-top: 330px;
            background-color: rgba(220, 38, 38, 0.9);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            width: 100%;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar .logout-btn:hover {
            background: #dc2626;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 280px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            color: var(--text-color);
            font-weight: 600;
        }

        /* Card Styles */
        .card-stat {
            border-radius: 10px;
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .notif-item {
            border-left: 4px solid #007bff;
            transition: background-color 0.2s;
        }
        
        .notif-item:hover {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            font-size: 0.75em;
        }
        
        .table-actions {
            white-space: nowrap;
        }
        
        .progress {
            height: 8px;
        }

        /* Footer Styles */
        footer {
            background-color: #2563eb;
            color: #ecf0f1;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: calc(100% - 280px);
            margin-top: 80px;
            margin-left: 280px;
        }

        .footer-content {
            margin: 0 auto;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            footer {
                width: 100%;
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
    <a href="index.php" class="menu-item"><i class="fas fa-home"></i> Dashboard</a>
    <a href="notifikasi.php" class="menu-item"><i class="fas fa-bell"></i> Notifikasi</a>
    <a href="data_pengajuan.php" class="menu-item active"><i class="fas fa-file-alt"></i> Data Pengajuan</a>
    <a href="verifikasi_pengajuan.php" class="menu-item"><i class="fas fa-check-circle"></i> Verifikasi Pengajuan</a>
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
        <h1><i class="fas fa-file-alt me-2"></i>Data Pengajuan</h1>
        <p class="text-muted mb-0">Kelola semua data pengajuan dari user</p>
    </div>

    <?php
    // Class untuk mengelola data pengajuan (Menggunakan MySQLi)
    class PengajuanManager {
        private $conn;
        
        public function __construct($connection) {
            $this->conn = $connection;
        }
        
        public function tambahPengajuan($user_id, $nama_pemohon, $jenis_pengajuan, $keterangan = null) {
            $sql = "INSERT INTO pengajuan (user_id, nama_pemohon, jenis_pengajuan, keterangan) 
                    VALUES (?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isss", $user_id, $nama_pemohon, $jenis_pengajuan, $keterangan);
            return $stmt->execute();
        }
        
        public function getAllPengajuan() {
            $sql = "SELECT * FROM pengajuan ORDER BY tanggal_pengajuan DESC";
            $result = $this->conn->query($sql);
            $data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        }
        
        public function updateStatus($id, $status, $keterangan = null) {
            $sql = "UPDATE pengajuan SET status = ?, keterangan = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $keterangan, $id);
            return $stmt->execute();
        }
        
        public function getPengajuanById($id) {
            $sql = "SELECT * FROM pengajuan WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        
        public function getTotalPengajuan() {
            $sql = "SELECT COUNT(*) as total FROM pengajuan";
            $result = $this->conn->query($sql);
            if ($result) {
                $row = $result->fetch_assoc();
                return $row['total'];
            }
            return 0;
        }
        
        public function getPengajuanMenunggu() {
            $sql = "SELECT COUNT(*) as total FROM pengajuan WHERE status = 'menunggu'";
            $result = $this->conn->query($sql);
            if ($result) {
                $row = $result->fetch_assoc();
                return $row['total'];
            }
            return 0;
        }
        
        public function getPengajuanHariIni() {
            $sql = "SELECT COUNT(*) as total FROM pengajuan WHERE DATE(tanggal_pengajuan) = CURDATE()";
            $result = $this->conn->query($sql);
            if ($result) {
                $row = $result->fetch_assoc();
                return $row['total'];
            }
            return 0;
        }
        
        public function getNotifikasiTerbaru($limit = 10) {
            $sql = "SELECT p.*, 
                           CASE 
                               WHEN p.status = 'menunggu' THEN 'Pengajuan Baru'
                               WHEN p.status = 'diproses' THEN 'Sedang Diproses' 
                               WHEN p.status = 'disetujui' THEN 'Pengajuan Disetujui'
                               WHEN p.status = 'ditolak' THEN 'Pengajuan Ditolak'
                           END as judul,
                           CONCAT('Pengajuan ', p.jenis_pengajuan, ' dari ', p.nama_pemohon, ' - Status: ', p.status) as pesan
                    FROM pengajuan p 
                    ORDER BY p.tanggal_pengajuan DESC 
                    LIMIT ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
        
        public function getStatistikStatus() {
            $sql = "SELECT status, COUNT(*) as total FROM pengajuan GROUP BY status";
            $result = $this->conn->query($sql);
            $data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        }
        
        public function hapusPengajuan($id) {
            $sql = "DELETE FROM pengajuan WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
    }

    // Inisialisasi object
    $pengajuanManager = new PengajuanManager($conn);

    // Proses form tambah data
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] == 'tambah_pengajuan') {
            $result = $pengajuanManager->tambahPengajuan(
                $_POST['user_id'],
                $_POST['nama_pemohon'],
                $_POST['jenis_pengajuan'],
                $_POST['keterangan']
            );
            
            if ($result) {
                $success_message = "Pengajuan berhasil ditambahkan!";
                echo "<script>setTimeout(function(){ window.location.href = window.location.href; }, 1000);</script>";
            } else {
                $error_message = "Gagal menambahkan pengajuan!";
            }
        }
        
        if ($_POST['action'] == 'update_status') {
            $result = $pengajuanManager->updateStatus(
                $_POST['id'],
                $_POST['status'],
                $_POST['keterangan']
            );
            
            if ($result) {
                $success_message = "Status berhasil diupdate!";
                echo "<script>setTimeout(function(){ window.location.href = window.location.href; }, 1000);</script>";
            } else {
                $error_message = "Gagal mengupdate status!";
            }
        }
    }

    // Proses hapus data
    if (isset($_GET['hapus'])) {
        $result = $pengajuanManager->hapusPengajuan($_GET['hapus']);
        if ($result) {
            $success_message = "Data berhasil dihapus!";
            echo "<script>setTimeout(function(){ window.location.href = window.location.href.split('?')[0]; }, 1000);</script>";
        } else {
            $error_message = "Gagal menghapus data!";
        }
    }

    // Ambil data untuk ditampilkan
    $totalNotifikasi = $pengajuanManager->getTotalPengajuan();
    $belumDibaca = $pengajuanManager->getPengajuanMenunggu();
    $hariIni = $pengajuanManager->getPengajuanHariIni();
    $notifikasi = $pengajuanManager->getNotifikasiTerbaru();
    $dataPengajuan = $pengajuanManager->getAllPengajuan();
    $statistik = $pengajuanManager->getStatistikStatus();
    ?>

    <!-- Pesan Notifikasi -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistik Notifikasi -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card card-stat bg-primary text-white">
                <div class="card-body text-center py-4">
                    <i class="fas fa-envelope fa-3x mb-3"></i>
                    <h1 class="display-4 fw-bold"><?= $totalNotifikasi ?></h1>
                    <h5>TOTAL PENGAJUAN</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card card-stat bg-warning text-white">
                <div class="card-body text-center py-4">
                    <i class="fas fa-clock fa-3x mb-3"></i>
                    <h1 class="display-4 fw-bold"><?= $belumDibaca ?></h1>
                    <h5>MENUNGGU RESPON</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card card-stat bg-success text-white">
                <div class="card-body text-center py-4">
                    <i class="fas fa-calendar-day fa-3x mb-3"></i>
                    <h1 class="display-4 fw-bold"><?= $hariIni ?></h1>
                    <h5>HARI INI</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Daftar Notifikasi -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>Notifikasi Terbaru
                    </h5>
                    <span class="badge bg-primary"><?= count($notifikasi) ?></span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($notifikasi)): ?>
                        <div class="text-center p-5 text-muted">
                            <i class="fas fa-bell-slash fa-4x mb-3"></i>
                            <h5>Tidak ada notifikasi</h5>
                            <p class="mb-0">Semua pengajuan telah diproses</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($notifikasi as $notif): ?>
                            <div class="list-group-item notif-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 text-primary"><?= htmlspecialchars($notif['judul']) ?></h6>
                                    <small class="text-muted">
                                        <?= date('d M Y H:i', strtotime($notif['tanggal_pengajuan'])) ?>
                                    </small>
                                </div>
                                <p class="mb-1"><?= htmlspecialchars($notif['pesan']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge status-badge bg-<?= 
                                        $notif['status'] == 'menunggu' ? 'warning' : 
                                        ($notif['status'] == 'diproses' ? 'info' : 
                                        ($notif['status'] == 'disetujui' ? 'success' : 'danger')) 
                                    ?>">
                                        <i class="fas fa-<?= 
                                            $notif['status'] == 'menunggu' ? 'clock' : 
                                            ($notif['status'] == 'diproses' ? 'cog' : 
                                            ($notif['status'] == 'disetujui' ? 'check' : 'times')) 
                                        ?> me-1"></i>
                                        <?= strtoupper($notif['status']) ?>
                                    </span>
                                    <small class="text-muted">ID: <?= $notif['id'] ?></small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Form Pengajuan Baru & Statistik -->
        <div class="col-lg-6">
            <!-- Form Pengajuan Baru -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Form Pengajuan Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="tambah_pengajuan">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">User ID</label>
                                    <input type="number" name="user_id" class="form-control" required 
                                           placeholder="Masukkan ID user">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Pengajuan</label>
                                    <select name="jenis_pengajuan" class="form-control" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="Pengajuan Cuti">Pengajuan Cuti</option>
                                        <option value="Pengajuan Izin">Pengajuan Izin</option>
                                        <option value="Pengajuan Dana">Pengajuan Dana</option>
                                        <option value="Pengajuan Barang">Pengajuan Barang</option>
                                        <option value="Pengajuan Training">Pengajuan Training</option>
                                        <option value="Pengajuan Lainnya">Pengajuan Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Pemohon</label>
                            <input type="text" name="nama_pemohon" class="form-control" required
                                   placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" 
                                      placeholder="Jelaskan detail pengajuan..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-paper-plane me-2"></i>Submit Pengajuan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistik Status -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Statistik Status Pengajuan
                    </h5>
                </div>
                <div class="card-body">
                    <?php foreach ($statistik as $stat): ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-capitalize fw-medium">
                                <i class="fas fa-<?= 
                                    $stat['status'] == 'menunggu' ? 'clock text-warning' : 
                                    ($stat['status'] == 'diproses' ? 'cog text-info' : 
                                    ($stat['status'] == 'disetujui' ? 'check text-success' : 'times text-danger')) 
                                ?> me-1"></i>
                                <?= $stat['status'] ?>
                            </span>
                            <span class="badge bg-dark"><?= $stat['total'] ?></span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-<?= 
                                $stat['status'] == 'menunggu' ? 'warning' : 
                                ($stat['status'] == 'diproses' ? 'info' : 
                                ($stat['status'] == 'disetujui' ? 'success' : 'danger')) 
                            ?>" style="width: <?= ($stat['total'] / max($totalNotifikasi, 1)) * 100 ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Lengkap -->
    <div class="card mt-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>Data Pengajuan Lengkap
            </h5>
            <div>
                <span class="badge bg-primary me-2">Total: <?= $totalNotifikasi ?> Data</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($dataPengajuan)): ?>
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-4x mb-3"></i>
                    <h4>Belum ada data pengajuan</h4>
                    <p class="mb-0">Gunakan form di atas untuk menambahkan pengajuan pertama</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Nama Pemohon</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataPengajuan as $data): ?>
                            <tr>
                                <td><strong>#<?= $data['id'] ?></strong></td>
                                <td><?= $data['user_id'] ?></td>
                                <td><?= htmlspecialchars($data['nama_pemohon']) ?></td>
                                <td><?= htmlspecialchars($data['jenis_pengajuan']) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $data['status'] == 'menunggu' ? 'warning' : 
                                        ($data['status'] == 'diproses' ? 'info' : 
                                        ($data['status'] == 'disetujui' ? 'success' : 'danger')) 
                                    ?>">
                                        <i class="fas fa-<?= 
                                            $data['status'] == 'menunggu' ? 'clock' : 
                                            ($data['status'] == 'diproses' ? 'cog' : 
                                            ($data['status'] == 'disetujui' ? 'check' : 'times')) 
                                        ?> me-1"></i>
                                        <?= strtoupper($data['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?= date('d M Y', strtotime($data['tanggal_pengajuan'])) ?></small>
                                    <br>
                                    <small class="text-muted"><?= date('H:i', strtotime($data['tanggal_pengajuan'])) ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($data['keterangan'])): ?>
                                        <span title="<?= htmlspecialchars($data['keterangan']) ?>">
                                            <?= strlen($data['keterangan']) > 50 ? substr($data['keterangan'], 0, 50) . '...' : $data['keterangan'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-actions text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal<?= $data['id'] ?>"
                                                title="Edit Status">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?hapus=<?= $data['id'] ?>" 
                                           class="btn btn-outline-danger" 
                                           onclick="return confirm('Yakin ingin menghapus pengajuan #<?= $data['id'] ?>?')"
                                           title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal<?= $data['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit me-2"></i>Update Status Pengajuan
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="menunggu" <?= $data['status'] == 'menunggu' ? 'selected' : '' ?>>🕒 Menunggu</option>
                                                        <option value="diproses" <?= $data['status'] == 'diproses' ? 'selected' : '' ?>>⚙️ Diproses</option>
                                                        <option value="disetujui" <?= $data['status'] == 'disetujui' ? 'selected' : '' ?>>✅ Disetujui</option>
                                                        <option value="ditolak" <?= $data['status'] == 'ditolak' ? 'selected' : '' ?>>❌ Ditolak</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="4" 
                                                              placeholder="Tambahkan keterangan jika diperlukan..."><?= htmlspecialchars($data['keterangan']) ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i>Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i>Update Status
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- FOOTER -->
<footer>
    <div class="footer-content">
        <p>&copy; 2024 SIAK Kota Parepare. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto close alert setelah 5 detik
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
</body>
</html>