<?php
require_once '../config/session.php';
require_once '../config/database.php';

// Cek apakah user sudah login sebagai admin
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     header("Location: login.php");
//     exit();
// }

// Fungsi untuk create/update tabel pengaduan
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
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($create_table_query)) {
        die("Error creating table: " . $conn->error);
    }
    
    // Tambahkan kolom jika belum ada (untuk tabel yang sudah ada)
    $columns_to_add = [
        'catatan_admin' => 'ALTER TABLE pengaduan ADD COLUMN IF NOT EXISTS catatan_admin TEXT',
        'status' => 'ALTER TABLE pengaduan ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT "pending"',
        'updated_at' => 'ALTER TABLE pengaduan ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        'data_tambahan' => 'ALTER TABLE pengaduan ADD COLUMN IF NOT EXISTS data_tambahan JSON'
    ];
    
    foreach ($columns_to_add as $column_sql) {
        $conn->query($column_sql);
    }
}

// Setup tabel
setupPengaduanTable($conn);

// Fungsi untuk update status pengajuan
if (isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['pengaduan_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $catatan_admin = isset($_POST['catatan_admin']) ? mysqli_real_escape_string($conn, $_POST['catatan_admin']) : '';
    
    $query = "UPDATE pengaduan SET status = '$status', catatan_admin = '$catatan_admin', updated_at = NOW() WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Status pengajuan berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate status: " . mysqli_error($conn);
    }
    
    header("Location: data_pengajuan.php");
    exit();
}

// Ambil semua data pengajuan dengan filter
$where_conditions = [];
$filter_jenis = $_GET['jenis_pengaduan'] ?? '';
$filter_status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

if (!empty($filter_jenis)) {
    $where_conditions[] = "jenis_pengaduan = '$filter_jenis'";
}

if (!empty($filter_status)) {
    $where_conditions[] = "status = '$filter_status'";
}

if (!empty($search)) {
    $where_conditions[] = "(nomor_pengaduan LIKE '%$search%' OR nama_pelapor LIKE '%$search%' OR subjek LIKE '%$search%')";
}

$where_sql = '';
if (!empty($where_conditions)) {
    $where_sql = "WHERE " . implode(' AND ', $where_conditions);
}

$query = "SELECT * FROM pengaduan $where_sql ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Hitung total pengajuan
$total_pengajuan = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengajuan - Admin Disdukcapil Parepare</title>
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
        
        .table-responsive {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
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
        
        .filter-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .modal-lg {
            max-width: 900px;
        }
        
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
        
        .table th {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 15px 12px;
        }
        
        .table td {
            padding: 12px;
            vertical-align: middle;
        }
        
        .btn-action {
            padding: 5px 10px;
            font-size: 0.8em;
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
            
            .sidebar.collapsed {
                display: none;
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
                        <h2 class="h3 mb-0"><i class="fas fa-file-alt me-2"></i>Data Pengajuan</h2>
                        <p class="text-muted mb-0">Kelola semua pengajuan dari masyarakat</p>
                    </div>
                    <div class="text-muted">
                        Total: <strong><?php echo $total_pengajuan; ?></strong> Pengajuan
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

                <!-- Filter Section -->
                <div class="filter-card">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Jenis Pengaduan</label>
                            <select name="jenis_pengaduan" class="form-select">
                                <option value="">Semua Jenis</option>
                                <option value="KARTU KELUARGA" <?php echo $filter_jenis == 'KARTU KELUARGA' ? 'selected' : ''; ?>>KARTU KELUARGA</option>
                                <option value="KARTU TANDA PENGENAL" <?php echo $filter_jenis == 'KARTU TANDA PENGENAL' ? 'selected' : ''; ?>>KARTU TANDA PENGENAL</option>
                                <option value="AKTA LAHIR" <?php echo $filter_jenis == 'AKTA LAHIR' ? 'selected' : ''; ?>>AKTA LAHIR</option>
                                <option value="AKTA KEMATIAN" <?php echo $filter_jenis == 'AKTA KEMATIAN' ? 'selected' : ''; ?>>AKTA KEMATIAN</option>
                                <option value="KARTU IDENTITAS ANAK" <?php echo $filter_jenis == 'KARTU IDENTITAS ANAK' ? 'selected' : ''; ?>>KARTU IDENTITAS ANAK</option>
                                <option value="SURAT KETERANGAN PINDAH WARGA NEGARA INDONESIA" <?php echo $filter_jenis == 'SURAT KETERANGAN PINDAH WARGA NEGARA INDONESIA' ? 'selected' : ''; ?>>SURAT KETERANGAN PINDAH</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" <?php echo $filter_status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="diproses" <?php echo $filter_status == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                                <option value="selesai" <?php echo $filter_status == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                <option value="ditolak" <?php echo $filter_status == 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pencarian</label>
                            <input type="text" name="search" class="form-control" placeholder="Cari nomor pengaduan, nama pelapor, atau subjek..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nomor Pengajuan</th>
                                <th>Tanggal</th>
                                <th>Pelapor</th>
                                <th>Jenis Pengajuan</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result) > 0): ?>
                                <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <strong><?php echo $row['nomor_pengaduan']; ?></strong>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($row['created_at'])); ?><br>
                                            <small class="text-muted"><?php echo date('H:i', strtotime($row['created_at'])); ?></small>
                                        </td>
                                        <td>
                                            <strong><?php echo $row['nama_pelapor']; ?></strong><br>
                                            <small class="text-muted"><?php echo $row['email_pelapor']; ?></small><br>
                                            <small class="text-muted"><?php echo $row['telepon_pelapor']; ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo $row['jenis_pengaduan']; ?></span>
                                        </td>
                                        <td><?php echo $row['subjek']; ?></td>
                                        <td>
                                            <?php
                                            $status_class = '';
                                            switch($row['status']) {
                                                case 'pending': $status_class = 'status-pending'; break;
                                                case 'diproses': $status_class = 'status-diproses'; break;
                                                case 'selesai': $status_class = 'status-selesai'; break;
                                                case 'ditolak': $status_class = 'status-ditolak'; break;
                                                default: $status_class = 'status-pending';
                                            }
                                            ?>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php echo strtoupper($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $row['id']; ?>">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail -->
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

                                                    <!-- Data Tambahan (JSON) -->
                                                    <?php 
                                                    if(isset($row['data_tambahan']) && !empty(trim($row['data_tambahan']))): 
                                                        $data_tambahan = json_decode($row['data_tambahan'], true);
                                                        
                                                        if(json_last_error() === JSON_ERROR_NONE && is_array($data_tambahan) && !empty($data_tambahan)): 
                                                    ?>
                                                            <div class="data-section">
                                                                <h6 class="data-label"><i class="fas fa-info-circle me-2"></i>Data Tambahan</h6>
                                                                <div class="row">
                                                                    <?php 
                                                                    $displayed_fields = 0;
                                                                    foreach($data_tambahan as $key => $value): 
                                                                        if(!empty($value) || $value === 0 || $value === '0'): 
                                                                            $displayed_fields++;
                                                                    ?>
                                                                            <div class="col-md-6 mb-2">
                                                                                <strong><?php 
                                                                                    $label = str_replace(['_', '-'], ' ', $key);
                                                                                    $label = ucwords($label);
                                                                                    echo $label; 
                                                                                ?>:</strong> 
                                                                                <span class="ms-1">
                                                                                    <?php 
                                                                                    if(is_array($value)) {
                                                                                        echo implode(', ', array_filter($value));
                                                                                    } elseif(is_bool($value)) {
                                                                                        echo $value ? 'Ya' : 'Tidak';
                                                                                    } else {
                                                                                        echo htmlspecialchars($value);
                                                                                    }
                                                                                    ?>
                                                                                </span>
                                                                            </div>
                                                                    <?php 
                                                                        endif;
                                                                    endforeach; 
                                                                    
                                                                    if($displayed_fields === 0): 
                                                                    ?>
                                                                        <div class="col-12">
                                                                            <p class="text-muted mb-0"><i>Tidak ada data tambahan yang diisi</i></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="data-section">
                                                                <h6 class="data-label"><i class="fas fa-info-circle me-2"></i>Data Tambahan</h6>
                                                                <p class="text-muted mb-0">Data tambahan tidak tersedia atau format tidak valid</p>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <div class="data-section">
                                                            <h6 class="data-label"><i class="fas fa-info-circle me-2"></i>Data Tambahan</h6>
                                                            <p class="text-muted mb-0">Tidak ada data tambahan</p>
                                                        </div>
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

                                                    <!-- Catatan Admin -->
                                                    <div class="data-section">
                                                        <h6 class="data-label"><i class="fas fa-sticky-note me-2"></i>Catatan Admin</h6>
                                                        <?php if(isset($row['catatan_admin']) && !empty($row['catatan_admin'])): ?>
                                                            <div class="alert alert-info">
                                                                <?php echo nl2br($row['catatan_admin']); ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <p class="text-muted">Belum ada catatan</p>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Form Update Status -->
                                                    <form method="POST">
                                                        <input type="hidden" name="pengaduan_id" value="<?php echo $row['id']; ?>">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Update Status</label>
                                                                <select name="status" class="form-select" required>
                                                                    <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                                    <option value="diproses" <?php echo $row['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                                                                    <option value="selesai" <?php echo $row['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                                                    <option value="ditolak" <?php echo $row['status'] == 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Catatan Admin</label>
                                                                <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Berikan catatan untuk pelapor..."><?php echo isset($row['catatan_admin']) ? $row['catatan_admin'] : ''; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                            <button type="submit" name="update_status" class="btn btn-success">
                                                                <i class="fas fa-save me-1"></i>Update Status
                                                            </button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                <i class="fas fa-times me-1"></i>Tutup
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada data pengajuan</p>
                                        <?php if(!empty($filter_jenis) || !empty($filter_status) || !empty($search)): ?>
                                            <a href="data_pengajuan.php" class="btn btn-primary mt-2">
                                                <i class="fas fa-times me-1"></i>Hapus Filter
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh setiap 30 detik untuk update status real-time
        setTimeout(function() {
            window.location.reload();
        }, 30000);

        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            // Handle mobile sidebar
            const navbarToggler = document.querySelector('.navbar-toggler');
            const sidebar = document.querySelector('.sidebar');
            
            if (navbarToggler) {
                navbarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 768) {
                    if (!sidebar.contains(event.target) && !navbarToggler.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>