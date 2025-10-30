<?php
// verifikasi_pengajuan.php - HALAMAN VERIFIKASI PENGAJUAN
include '../config/session.php';
include '../config/database.php';
redirect_if_not_logged_in();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pengajuan - SIAK KOTA PAREPARE</title>
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
        
        .status-badge {
            font-size: 0.75em;
        }
        
        .table-actions {
            white-space: nowrap;
        }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .verification-card {
            border-left: 4px solid #007bff;
            transition: all 0.3s ease;
        }
        
        .verification-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .card-menunggu {
            border-left-color: #ffc107;
        }
        
        .card-diproses {
            border-left-color: #17a2b8;
        }
        
        .card-disetujui {
            border-left-color: #28a745;
        }
        
        .card-ditolak {
            border-left-color: #dc3545;
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
    <a href="data_pengajuan.php" class="menu-item"><i class="fas fa-file-alt"></i> Data Pengajuan</a>
    <a href="verifikasi_pengajuan.php" class="menu-item active"><i class="fas fa-check-circle"></i> Verifikasi Pengajuan</a>
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
        <h1><i class="fas fa-check-circle me-2"></i>Verifikasi Pengajuan</h1>
        <p class="text-muted mb-0">Verifikasi dan kelola status pengajuan dari user</p>
    </div>

    <?php
    // Class untuk mengelola verifikasi pengajuan (Menggunakan MySQLi)
    class VerifikasiManager {
        private $conn;
        
        public function __construct($connection) {
            $this->conn = $connection;
        }
        
        // Ambil data pengajuan berdasarkan status
        public function getPengajuanByStatus($status = null) {
            if ($status) {
                $sql = "SELECT * FROM pengajuan WHERE status = ? ORDER BY tanggal_pengajuan DESC";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $status);
            } else {
                $sql = "SELECT * FROM pengajuan ORDER BY tanggal_pengajuan DESC";
                $stmt = $this->conn->prepare($sql);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
        
        // Update status pengajuan
        public function updateStatus($id, $status, $catatan_verifikasi = null) {
            $sql = "UPDATE pengajuan SET status = ?, keterangan = CONCAT(IFNULL(keterangan, ''), ' | Catatan Verifikasi: ', ?) WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $catatan_verifikasi, $id);
            return $stmt->execute();
        }
        
        // Ambil statistik pengajuan
        public function getStatistikVerifikasi() {
            $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as menunggu,
                    SUM(CASE WHEN status = 'diproses' THEN 1 ELSE 0 END) as diproses,
                    SUM(CASE WHEN status = 'disetujui' THEN 1 ELSE 0 END) as disetujui,
                    SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak
                    FROM pengajuan";
            
            $result = $this->conn->query($sql);
            return $result->fetch_assoc();
        }
        
        // Ambil detail pengajuan by ID
        public function getDetailPengajuan($id) {
            $sql = "SELECT * FROM pengajuan WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        
        // Ambil pengajuan yang perlu diverifikasi (status menunggu dan diproses)
        public function getPengajuanPerluVerifikasi() {
            $sql = "SELECT * FROM pengajuan WHERE status IN ('menunggu', 'diproses') ORDER BY tanggal_pengajuan DESC";
            $result = $this->conn->query($sql);
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    }

    // Inisialisasi object
    $verifikasiManager = new VerifikasiManager($conn);

    // Proses update status
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] == 'update_status') {
            $result = $verifikasiManager->updateStatus(
                $_POST['id'],
                $_POST['status'],
                $_POST['catatan_verifikasi']
            );
            
            if ($result) {
                $success_message = "Status pengajuan berhasil diupdate!";
                echo "<script>setTimeout(function(){ window.location.href = window.location.href; }, 1000);</script>";
            } else {
                $error_message = "Gagal mengupdate status pengajuan!";
            }
        }
    }

    // Filter status
    $filter_status = isset($_GET['status']) ? $_GET['status'] : '';
    $dataPengajuan = $filter_status ? 
        $verifikasiManager->getPengajuanByStatus($filter_status) : 
        $verifikasiManager->getPengajuanPerluVerifikasi();
    
    $statistik = $verifikasiManager->getStatistikVerifikasi();
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

    <!-- Statistik Verifikasi -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-stat bg-primary text-white">
                <div class="card-body text-center py-4">
                    <i class="fas fa-tasks fa-3x mb-3"></i>
                    <h1 class="display-4 fw-bold"><?= $statistik['total'] ?></h1>
                    <h5>TOTAL PENGAJUAN</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-stat bg-warning text-white">
                <div class="card-body text-center py-4">
                    <i class="fas fa-clock fa-3x mb-3"></i>
                    <h1 class="display-4 fw-bold"><?= $statistik['menunggu'] ?></h1>
                    <h5>MENUNGGU</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-stat bg-info text-white">
                <div class="card-body text-center py-4">
                    <i class="fas fa-cog fa-3x mb-3"></i>
                    <h1 class="display-4 fw-bold"><?= $statistik['diproses'] ?></h1>
                    <h5>DIPROSES</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card card-stat bg-success text-white">
                <div class="card-body text-center py-4">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h1 class="display-4 fw-bold"><?= $statistik['disetujui'] + $statistik['ditolak'] ?></h1>
                    <h5>SELESAI</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <h5><i class="fas fa-filter me-2"></i>Filter Pengajuan</h5>
        <div class="row">
            <div class="col-md-8">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status Perlu Verifikasi</option>
                            <option value="menunggu" <?= $filter_status == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                            <option value="diproses" <?= $filter_status == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                            <option value="disetujui" <?= $filter_status == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="ditolak" <?= $filter_status == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <a href="verifikasi_pengajuan.php" class="btn btn-outline-secondary">
                            <i class="fas fa-refresh me-1"></i>Reset Filter
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>Cetak
                    </button>
                    <button class="btn btn-outline-success" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-1"></i>Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pengajuan Perlu Verifikasi -->
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list-check me-2"></i>
                <?= $filter_status ? 'Data Pengajuan - ' . strtoupper($filter_status) : 'Pengajuan Perlu Verifikasi' ?>
            </h5>
            <span class="badge bg-primary"><?= count($dataPengajuan) ?> Data</span>
        </div>
        <div class="card-body">
            <?php if (empty($dataPengajuan)): ?>
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-4x mb-3"></i>
                    <h4>Tidak ada data pengajuan</h4>
                    <p class="mb-0">Semua pengajuan telah diverifikasi</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($dataPengajuan as $data): ?>
                    <div class="col-lg-6 mb-4">
                        <div class="card verification-card card-<?= $data['status'] ?> h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-file-alt me-2"></i>#<?= $data['id'] ?>
                                </h6>
                                <span class="badge bg-<?= 
                                    $data['status'] == 'menunggu' ? 'warning' : 
                                    ($data['status'] == 'diproses' ? 'info' : 
                                    ($data['status'] == 'disetujui' ? 'success' : 'danger')) 
                                ?>">
                                    <?= strtoupper($data['status']) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong><i class="fas fa-user me-2"></i>Pemohon:</strong>
                                    <span class="float-end"><?= htmlspecialchars($data['nama_pemohon']) ?></span>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="fas fa-id-card me-2"></i>User ID:</strong>
                                    <span class="float-end"><?= $data['user_id'] ?></span>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="fas fa-tag me-2"></i>Jenis:</strong>
                                    <span class="float-end"><?= htmlspecialchars($data['jenis_pengajuan']) ?></span>
                                </div>
                                <div class="mb-3">
                                    <strong><i class="fas fa-calendar me-2"></i>Tanggal:</strong>
                                    <span class="float-end"><?= date('d M Y H:i', strtotime($data['tanggal_pengajuan'])) ?></span>
                                </div>
                                <?php if (!empty($data['keterangan'])): ?>
                                <div class="mb-3">
                                    <strong><i class="fas fa-sticky-note me-2"></i>Keterangan:</strong>
                                    <p class="mb-0 mt-1"><?= htmlspecialchars($data['keterangan']) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="btn-group w-100">
                                    <?php if (in_array($data['status'], ['menunggu', 'diproses'])): ?>
                                        <button class="btn btn-outline-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#setujuiModal<?= $data['id'] ?>">
                                            <i class="fas fa-check me-1"></i>Setujui
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#prosesModal<?= $data['id'] ?>">
                                            <i class="fas fa-cog me-1"></i>Proses
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#tolakModal<?= $data['id'] ?>">
                                            <i class="fas fa-times me-1"></i>Tolak
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Pengajuan telah <?= $data['status'] ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Setujui -->
                    <div class="modal fade" id="setujuiModal<?= $data['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                    <input type="hidden" name="status" value="disetujui">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-check-circle me-2"></i>Setujui Pengajuan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menyetujui pengajuan ini?</p>
                                        <div class="mb-3">
                                            <label class="form-label">Catatan Verifikasi (Opsional):</label>
                                            <textarea name="catatan_verifikasi" class="form-control" rows="3" 
                                                      placeholder="Berikan catatan jika diperlukan..."></textarea>
                                        </div>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Detail Pengajuan:</strong><br>
                                            ID: #<?= $data['id'] ?><br>
                                            Pemohon: <?= htmlspecialchars($data['nama_pemohon']) ?><br>
                                            Jenis: <?= htmlspecialchars($data['jenis_pengajuan']) ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>Batal
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-1"></i>Ya, Setujui
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Proses -->
                    <div class="modal fade" id="prosesModal<?= $data['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                    <input type="hidden" name="status" value="diproses">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-cog me-2"></i>Proses Pengajuan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Ubah status pengajuan menjadi "Diproses"?</p>
                                        <div class="mb-3">
                                            <label class="form-label">Catatan Proses:</label>
                                            <textarea name="catatan_verifikasi" class="form-control" rows="3" 
                                                      placeholder="Berikan catatan proses..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>Batal
                                        </button>
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-cog me-1"></i>Ya, Proses
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Tolak -->
                    <div class="modal fade" id="tolakModal<?= $data['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                    <input type="hidden" name="status" value="ditolak">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-times-circle me-2"></i>Tolak Pengajuan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menolak pengajuan ini?</p>
                                        <div class="mb-3">
                                            <label class="form-label">Alasan Penolakan:</label>
                                            <textarea name="catatan_verifikasi" class="form-control" rows="3" 
                                                      placeholder="Berikan alasan penolakan..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>Batal
                                        </button>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times me-1"></i>Ya, Tolak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
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

    // Fungsi export ke Excel (simulasi)
    function exportToExcel() {
        alert('Fitur export ke Excel akan diimplementasikan!');
        // Implementasi export Excel bisa menggunakan library seperti SheetJS
    }

    // Auto refresh setiap 30 detik untuk update real-time
    setInterval(function() {
        // Cek jika tidak ada modal yang terbuka
        if (document.querySelectorAll('.modal.show').length === 0) {
            window.location.reload();
        }
    }, 30000);
</script>
</body>
</html>