<?php
require_once '../config/session.php';
require_once '../config/database.php';

// Cek apakah user sudah login sebagai admin
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     header("Location: login.php");
//     exit();
// }

// Setup tabel (gunakan fungsi yang sama)
function setupPengaduanTable($conn) {
    $create_table_query = "
    CREATE TABLE IF NOT EXISTS pengaduan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nomor_pengaduan VARCHAR(50) UNIQUE NOT NULL,
        nama_pelapor VARCHAR(100) NOT NULL,
        email_pelapor VARCHAR(100) NOT NULL,
        telepon_pelapor VARCHAR(20) NOT NULL,
        jenis_pengaduan VARCHAR(50) NOT NULL,
        subjek TEXT NOT NULL,
        deskripsi TEXT NOT NULL,
        prioritas VARCHAR(20) DEFAULT 'sedang',
        lampiran VARCHAR(255),
        data_tambahan JSON,
        status VARCHAR(20) DEFAULT 'pending',
        catatan_admin TEXT,
        tanggal_verifikasi DATETIME,
        admin_verifikator VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($create_table_query)) {
        die("Error creating table: " . $conn->error);
    }
    
    // Tambahkan kolom jika belum ada
    $columns_to_add = [
        'tanggal_verifikasi' => 'ALTER TABLE pengaduan ADD COLUMN IF NOT EXISTS tanggal_verifikasi DATETIME',
        'admin_verifikator' => 'ALTER TABLE pengaduan ADD COLUMN IF NOT EXISTS admin_verifikator VARCHAR(100)'
    ];
    
    foreach ($columns_to_add as $column_sql) {
        $conn->query($column_sql);
    }
}

// Setup tabel
setupPengaduanTable($conn);

// Fungsi untuk verifikasi pengajuan
if (isset($_POST['verifikasi_pengajuan'])) {
    $id = mysqli_real_escape_string($conn, $_POST['pengaduan_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $catatan_verifikasi = isset($_POST['catatan_verifikasi']) ? mysqli_real_escape_string($conn, $_POST['catatan_verifikasi']) : '';
    $admin_verifikator = $_SESSION['admin_nama'] ?? 'Administrator';
    
    if ($status == 'diproses' || $status == 'selesai') {
        $query = "UPDATE pengaduan SET 
                  status = '$status', 
                  catatan_admin = '$catatan_verifikasi',
                  tanggal_verifikasi = NOW(),
                  admin_verifikator = '$admin_verifikator',
                  updated_at = NOW() 
                  WHERE id = '$id'";
    } else {
        $query = "UPDATE pengaduan SET 
                  status = '$status', 
                  catatan_admin = '$catatan_verifikasi',
                  updated_at = NOW() 
                  WHERE id = '$id'";
    }
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Pengajuan berhasil diverifikasi! Status: " . strtoupper($status);
    } else {
        $_SESSION['error'] = "Gagal memverifikasi pengajuan: " . mysqli_error($conn);
    }
    
    header("Location: verifikasi_pengajuan.php");
    exit();
}

// Fungsi untuk tolak pengajuan
if (isset($_POST['tolak_pengajuan'])) {
    $id = mysqli_real_escape_string($conn, $_POST['pengaduan_id']);
    $alasan_penolakan = mysqli_real_escape_string($conn, $_POST['alasan_penolakan']);
    $admin_verifikator = $_SESSION['admin_nama'] ?? 'Administrator';
    
    $query = "UPDATE pengaduan SET 
              status = 'ditolak', 
              catatan_admin = '$alasan_penolakan',
              tanggal_verifikasi = NOW(),
              admin_verifikator = '$admin_verifikator',
              updated_at = NOW() 
              WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Pengajuan berhasil ditolak!";
    } else {
        $_SESSION['error'] = "Gagal menolak pengajuan: " . mysqli_error($conn);
    }
    
    header("Location: verifikasi_pengajuan.php");
    exit();
}

// Ambil data pengajuan yang perlu diverifikasi (status pending)
$query = "SELECT * FROM pengaduan WHERE status = 'pending' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$total_pending = mysqli_num_rows($result);

// Juga ambil data yang sudah diverifikasi untuk riwayat
$query_verified = "SELECT * FROM pengaduan WHERE status IN ('diproses', 'selesai', 'ditolak') ORDER BY updated_at DESC LIMIT 50";
$result_verified = mysqli_query($conn, $query_verified);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pengajuan - Admin Disdukcapil Parepare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }
        
        .sidebar {
            background: var(--primary-color);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: white;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: var(--secondary-color);
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .card-custom {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
        }
        
        .card-header-custom {
            background: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-diproses { background: #cce7ff; color: #004085; }
        .status-selesai { background: #d4edda; color: #155724; }
        .status-ditolak { background: #f8d7da; color: #721c24; }
        
        .data-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid var(--secondary-color);
        }
        
        .data-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .navbar-custom {
            background: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .btn-verify {
            background: var(--success-color);
            color: white;
            border: none;
        }
        
        .btn-reject {
            background: var(--accent-color);
            color: white;
            border: none;
        }
        
        .btn-process {
            background: var(--secondary-color);
            color: white;
            border: none;
        }
        
        .verification-badge {
            background: var(--success-color);
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.7em;
        }
        
        .count-badge {
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7em;
            margin-left: 5px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-landmark me-2"></i>
                DISDUKCAPIL Parepare - Admin
            </a>
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-md-block collapse d-md-block" id="mobileSidebar">
                <div class="p-3 text-center border-bottom">
                    <h5 class="mb-0">Admin Panel</h5>
                    <small>Disdukcapil Parepare</small>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="index.php.php">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a class="nav-link active" href="data_pengajuan.php">
                        <i class="fas fa-file-alt me-2"></i>Data Pengajuan
                    </a>
                    <a class="nav-link" href="verifikasi_pengajuan.php">
                        <i class="fas fa-chart-bar me-2"></i>Verifikasi Pengajuan
                    </a>
                    <a class="nav-link" href="pengaturan.php">
                        <i class="fas fa-cog me-2"></i>Pengaturan
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h3 mb-0"><i class="fas fa-check-circle me-2"></i>Verifikasi Pengajuan</h2>
                        <p class="text-muted mb-0">Verifikasi dan proses pengajuan dari masyarakat</p>
                    </div>
                    <div class="text-muted">
                        Pending: <strong><?php echo $total_pending; ?></strong> Pengajuan
                    </div>
                </div>

                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Pengajuan Pending -->
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Pengajuan Menunggu Verifikasi
                            <?php if($total_pending > 0): ?>
                                <span class="badge bg-warning ms-2"><?php echo $total_pending; ?> PENDING</span>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($result) > 0): ?>
                            <div class="row">
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 border-warning">
                                            <div class="card-header bg-warning text-dark">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong><?php echo $row['nomor_pengaduan']; ?></strong>
                                                    <span class="status-badge status-pending">PENDING</span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h6 class="card-title"><?php echo $row['jenis_pengaduan']; ?></h6>
                                                <p class="card-text">
                                                    <strong>Pelapor:</strong> <?php echo $row['nama_pelapor']; ?><br>
                                                    <strong>Tanggal:</strong> <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?><br>
                                                    <strong>Prioritas:</strong> 
                                                    <span class="badge bg-<?php echo $row['prioritas'] == 'tinggi' ? 'danger' : ($row['prioritas'] == 'sedang' ? 'warning' : 'secondary'); ?>">
                                                        <?php echo strtoupper($row['prioritas']); ?>
                                                    </span>
                                                </p>
                                                <div class="mb-2">
                                                    <strong>Subjek:</strong><br>
                                                    <small><?php echo $row['subjek']; ?></small>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="d-grid gap-2">
                                                    <button type="button" class="btn btn-sm btn-verify" data-bs-toggle="modal" data-bs-target="#verifyModal<?php echo $row['id']; ?>">
                                                        <i class="fas fa-check me-1"></i>Verifikasi
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo $row['id']; ?>">
                                                        <i class="fas fa-times me-1"></i>Tolak
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $row['id']; ?>">
                                                        <i class="fas fa-eye me-1"></i>Detail
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Verifikasi -->
                                    <div class="modal fade" id="verifyModal<?php echo $row['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Verifikasi Pengajuan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <p>Verifikasi pengajuan: <strong><?php echo $row['nomor_pengaduan']; ?></strong></p>
                                                        <div class="mb-3">
                                                            <label class="form-label">Tindakan</label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="diproses">Setujui & Proses</option>
                                                                <option value="selesai">Setujui & Selesaikan</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan Verifikasi</label>
                                                            <textarea name="catatan_verifikasi" class="form-control" rows="3" placeholder="Berikan catatan verifikasi..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="pengaduan_id" value="<?php echo $row['id']; ?>">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="verifikasi_pengajuan" class="btn btn-success">
                                                            <i class="fas fa-check me-1"></i>Verifikasi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Tolak -->
                                    <div class="modal fade" id="rejectModal<?php echo $row['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Pengajuan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <p>Tolak pengajuan: <strong><?php echo $row['nomor_pengaduan']; ?></strong></p>
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Pastikan alasan penolakan jelas dan dapat dipahami oleh pelapor.
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan Penolakan</label>
                                                            <textarea name="alasan_penolakan" class="form-control" rows="4" placeholder="Jelaskan alasan penolakan pengajuan..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="pengaduan_id" value="<?php echo $row['id']; ?>">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="tolak_pengajuan" class="btn btn-danger">
                                                            <i class="fas fa-times me-1"></i>Tolak Pengajuan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Detail (sama seperti di data_pengajuan.php) -->
                                    <div class="modal fade" id="detailModal<?php echo $row['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Pengajuan - <?php echo $row['nomor_pengaduan']; ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Data Pelapor -->
                                                    <div class="data-section">
                                                        <h6 class="data-label"><i class="fas fa-user me-2"></i>Data Pelapor</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Nama:</strong> <?php echo $row['nama_pelapor']; ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Email:</strong> <?php echo $row['email_pelapor']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">
                                                                <strong>Telepon:</strong> <?php echo $row['telepon_pelapor']; ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Prioritas:</strong> 
                                                                <span class="badge bg-<?php echo $row['prioritas'] == 'tinggi' ? 'danger' : ($row['prioritas'] == 'sedang' ? 'warning' : 'secondary'); ?>">
                                                                    <?php echo strtoupper($row['prioritas']); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Data Pengajuan -->
                                                    <div class="data-section">
                                                        <h6 class="data-label"><i class="fas fa-clipboard-list me-2"></i>Data Pengajuan</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Jenis Pengajuan:</strong> 
                                                                <span class="badge bg-info"><?php echo $row['jenis_pengaduan']; ?></span>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Subjek:</strong> <?php echo $row['subjek']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-12">
                                                                <strong>Deskripsi:</strong><br>
                                                                <?php echo nl2br($row['deskripsi']); ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Data Tambahan -->
                                                    <?php 
                                                    if(isset($row['data_tambahan']) && !empty(trim($row['data_tambahan']))): 
                                                        $data_tambahan = json_decode($row['data_tambahan'], true);
                                                        if(json_last_error() === JSON_ERROR_NONE && is_array($data_tambahan) && !empty($data_tambahan)): 
                                                    ?>
                                                            <div class="data-section">
                                                                <h6 class="data-label"><i class="fas fa-info-circle me-2"></i>Data Tambahan</h6>
                                                                <div class="row">
                                                                    <?php 
                                                                    foreach($data_tambahan as $key => $value): 
                                                                        if(!empty($value) || $value === 0 || $value === '0'): 
                                                                    ?>
                                                                            <div class="col-md-6 mb-2">
                                                                                <strong><?php 
                                                                                    $label = str_replace(['_', '-'], ' ', $key);
                                                                                    echo ucwords($label); 
                                                                                ?>:</strong> 
                                                                                <span class="ms-1"><?php echo htmlspecialchars($value); ?></span>
                                                                            </div>
                                                                    <?php 
                                                                        endif;
                                                                    endforeach; 
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <!-- Lampiran -->
                                                    <?php if(isset($row['lampiran']) && !empty($row['lampiran'])): ?>
                                                        <div class="data-section">
                                                            <h6 class="data-label"><i class="fas fa-paperclip me-2"></i>Lampiran</h6>
                                                            <a href="../uploads/<?php echo $row['lampiran']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-download me-1"></i>Download File
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5 class="text-success">Tidak ada pengajuan yang menunggu verifikasi</h5>
                                <p class="text-muted">Semua pengajuan sudah diproses</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Riwayat Verifikasi -->
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Riwayat Verifikasi Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($result_verified) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor Pengajuan</th>
                                            <th>Jenis</th>
                                            <th>Pelapor</th>
                                            <th>Status</th>
                                            <th>Tanggal Verifikasi</th>
                                            <th>Admin</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no_hist = 1; while($row_hist = mysqli_fetch_assoc($result_verified)): ?>
                                            <tr>
                                                <td><?php echo $no_hist++; ?></td>
                                                <td><strong><?php echo $row_hist['nomor_pengaduan']; ?></strong></td>
                                                <td><span class="badge bg-info"><?php echo $row_hist['jenis_pengaduan']; ?></span></td>
                                                <td><?php echo $row_hist['nama_pelapor']; ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = '';
                                                    switch($row_hist['status']) {
                                                        case 'diproses': $status_class = 'status-diproses'; break;
                                                        case 'selesai': $status_class = 'status-selesai'; break;
                                                        case 'ditolak': $status_class = 'status-ditolak'; break;
                                                        default: $status_class = 'status-pending';
                                                    }
                                                    ?>
                                                    <span class="status-badge <?php echo $status_class; ?>">
                                                        <?php echo strtoupper($row_hist['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if(!empty($row_hist['tanggal_verifikasi'])): ?>
                                                        <?php echo date('d/m/Y H:i', strtotime($row_hist['tanggal_verifikasi'])); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if(!empty($row_hist['admin_verifikator'])): ?>
                                                        <span class="verification-badge"><?php echo $row_hist['admin_verifikator']; ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailHistModal<?php echo $row_hist['id']; ?>">
                                                        <i class="fas fa-eye me-1"></i>Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada riwayat verifikasi</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh setiap 60 detik untuk update real-time
        setTimeout(function() {
            window.location.reload();
        }, 60000);

        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const sidebar = document.querySelector('.sidebar');
            
            if (navbarToggler) {
                navbarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });

        // Highlight required fields
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = this.querySelectorAll('[required]');
                let valid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        valid = false;
                        field.style.borderColor = 'red';
                    } else {
                        field.style.borderColor = '';
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    alert('Harap lengkapi semua field yang wajib diisi!');
                }
            });
        });
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>