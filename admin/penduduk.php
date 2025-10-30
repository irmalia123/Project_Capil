<?php
// Konfigurasi Database
$host = 'localhost'; // Host database
$dbname = 'penduduk_app'; // Nama database
$username = 'root'; // Username database
$password = ''; // Password database

try {
    // Membuat koneksi ke database menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mengatur mode error
} catch(PDOException $e) {
    // Menangani kesalahan koneksi
    echo "Koneksi gagal: " . $e->getMessage();
}

// Fungsi untuk mengambil data penduduk
function getPenduduk($pdo) {
    // Mengambil semua data penduduk dari tabel dan mengurutkannya berdasarkan tanggal dibuat
    $stmt = $pdo->query("SELECT * FROM penduduk ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengembalikan data sebagai array asosiatif
}

// Menangani permintaan POST untuk tambah/edit/hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) { // Memeriksa apakah ada aksi yang diminta
        switch ($_POST['action']) {
            case 'add':
                // Menyiapkan pernyataan SQL untuk menambahkan data penduduk
                $stmt = $pdo->prepare("INSERT INTO penduduk (nik, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, agama, status_perkawinan, pekerjaan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                // Menjalankan pernyataan dengan data dari formulir
                $stmt->execute([$_POST['nik'], $_POST['nama'], $_POST['tempat_lahir'], $_POST['tanggal_lahir'], $_POST['jenis_kelamin'], $_POST['alamat'], $_POST['agama'], $_POST['status_perkawinan'], $_POST['pekerjaan']]);
                break;
            case 'edit':
                // Menyiapkan pernyataan SQL untuk memperbarui data penduduk
                $stmt = $pdo->prepare("UPDATE penduduk SET nama=?, tempat_lahir=?, tanggal_lahir=?, jenis_kelamin=?, alamat=?, agama=?, status_perkawinan=?, pekerjaan=? WHERE nik=?");
                // Menjalankan pernyataan dengan data dari formulir
                $stmt->execute([$_POST['nama'], $_POST['tempat_lahir'], $_POST['tanggal_lahir'], $_POST['jenis_kelamin'], $_POST['alamat'], $_POST['agama'], $_POST['status_perkawinan'], $_POST['pekerjaan'], $_POST['nik']]);
                break;
            case 'delete':
                // Menyiapkan pernyataan SQL untuk menghapus data penduduk
                $stmt = $pdo->prepare("DELETE FROM penduduk WHERE nik=?");
                // Menjalankan pernyataan dengan NIK yang diberikan
                $stmt->execute([$_POST['nik']]);
                break;
        }
        // Redirect ke halaman penduduk setelah melakukan aksi
        header('Location: penduduk.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penduduk - SisPenduk</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet"> <!-- CSS untuk DataTables -->
    <style>
        /* Menggunakan style yang sama dari dashboard sebelumnya */
        * {
            margin: 0; /* Menghilangkan margin default */
            padding: 0; /* Menghilangkan padding default */
            box-sizing: border-box; /* Mengatur box-sizing untuk semua elemen */
            font-family: Arial, sans-serif; /* Mengatur font untuk seluruh halaman */
        }

        :root {
            /* Variabel warna untuk tema */
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --text-color: #1f2937;
            --bg-light: #f3f4f6;
        }

        body {
            background-color: var(--bg-light); /* Warna latar belakang */
        }

        /* Style untuk modal */
        .modal {
            display: none; /* Modal tidak ditampilkan secara default */
            position: fixed; /* Modal tetap di posisi tetap */
            top: 0; /* Menempel di bagian atas */
            left: 0; /* Menempel di sisi kiri */
            width: 100%; /* Lebar penuh */
            height: 100%; /* Tinggi penuh */
            background-color: rgba(0,0,0,0.5); /* Latar belakang gelap dengan transparansi */
            z-index: 1000; /* Menempatkan modal di atas elemen lainnya */
        }

        .modal-content {
            position: relative; /* Posisi relatif untuk konten modal */
            background-color: white; /* Warna latar belakang konten modal */
            margin: 5% auto; /* Margin otomatis untuk pusatkan konten */
            padding: 20px; /* Ruang di dalam konten modal */
            width: 80%; /* Lebar konten modal */
            max-width: 600px; /* Lebar maksimum konten modal */
            border-radius: 8px; /* Sudut melengkung konten modal */
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); /* Bayangan konten modal */
        }

        .close {
            position: absolute; /* Posisi absolut untuk tombol tutup */
            right: 20px; /* Jarak kanan tombol tutup */
            top: 15px; /* Jarak atas tombol tutup */
            font-size: 24px; /* Ukuran font tombol tutup */
            cursor: pointer; /* Kursor tangan saat hover */
        }

        .form-group {
            margin-bottom: 15px; /* Jarak bawah untuk setiap grup formulir */
        }

        .form-group label {
            display: block; /* Tampilkan label sebagai blok */
            margin-bottom: 5px; /* Jarak bawah label */
            color: var(--text-color); /* Warna teks label */
        }

        .form-group input, .form-group select {
            width: 100%; /* Lebar penuh untuk input dan select */
            padding: 8px; /* Ruang di dalam input dan select */
            border: 1px solid #ddd; /* Garis batas input dan select */
            border-radius: 4px; /* Sudut melengkung */
        }

        .btn {
            padding: 8px 15px; /* Ruang di dalam tombol */
            border: none; /* Tanpa garis batas */
            border-radius: 4px; /* Sudut melengkung */
            cursor: pointer; /* Kursor tangan saat hover */
            font-weight: bold; /* Ketebalan font tombol */
        }

        .btn-primary {
            background-color: var(--primary-color); /* Warna latar belakang tombol utama */
            color: white; /* Warna teks tombol utama */
        }

        .btn-danger {
            background-color: #dc2626; /* Warna latar belakang tombol hapus */
            color: white; /* Warna teks tombol hapus */
        }

        /* DataTables Custom Styling */
        .dataTables_wrapper {
            padding: 20px; /* Ruang di dalam wrapper DataTables */
            background: white; /* Warna latar belakang wrapper */
            border-radius: 8px; /* Sudut melengkung wrapper */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Bayangan wrapper */
        }

        .dataTables_filter input {
            padding: 5px; /* Ruang di dalam input pencarian */
            border: 1px solid #ddd; /* Garis batas input pencarian */
            border-radius: 4px; /* Sudut melengkung input pencarian */
            margin-left: 5px; /* Jarak kiri input pencarian */
        }

        .action-buttons {
            display: flex; /* Mengatur tombol aksi dengan flexbox */
            gap: 5px; /* Jarak antar tombol aksi */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%; /* Lebar konten modal pada perangkat kecil */
                margin: 2% auto; /* Margin otomatis untuk pusatkan konten */
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar dari dashboard sebelumnya -->
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
            <button class="btn btn-primary" onclick="showAddModal()">
                <i class="fas fa-plus"></i> Tambah Penduduk
            </button>
        </div>

        <!-- Tabel Data Penduduk -->
        <div class="data-container">
            <table id="pendudukTable" class="display">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>Agama</th>
                        <th>Status</th>
                        <th>Pekerjaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mengambil data penduduk dari database
                    $penduduk = getPenduduk($pdo);
                    foreach($penduduk as $data) {
                        echo "<tr>";
                        echo "<td>{$data['nik']}</td>";
                        echo "<td>{$data['nama']}</td>";
                        echo "<td>{$data['tempat_lahir']}</td>";
                        echo "<td>{$data['tanggal_lahir']}</td>";
                        echo "<td>{$data['jenis_kelamin']}</td>";
                        echo "<td>{$data['alamat']}</td>";
                        echo "<td>{$data['agama']}</td>";
                        echo "<td>{$data['status_perkawinan']}</td>";
                        echo "<td>{$data['pekerjaan']}</td>";
                        echo "<td class='action-buttons'>
                                <button class='btn btn-primary' onclick='showEditModal(\"{$data['nik']}\")'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                <button class='btn btn-danger' onclick='deletePenduduk(\"{$data['nik']}\")'>
                                    <i class='fas fa-trash'></i>
                                </button>
                            </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal Form -->
    <div id="pendudukModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Tambah Penduduk</h2>
            <form id="pendudukForm" method="POST">
                <input type="hidden" name="action" id="formAction" value="add">
                
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" id="nik" name="nik" required pattern="[0-9]{16}" maxlength="16">
                </div>
                
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                
                <div class="form-group">
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" required>
                </div>
                
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>
                
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" required>
                </div>
                
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <select id="agama" name="agama" required>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status_perkawinan">Status Perkawinan</label>
                    <select id="status_perkawinan" name="status_perkawinan" required>
                        <option value="Belum Kawin">Belum Kawin</option>
                        <option value="Kawin">Kawin</option>
                        <option value="Cerai Hidup">Cerai Hidup</option>
                        <option value="Cerai Mati">Cerai Mati</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="pekerjaan">Pekerjaan</label>
                    <input type="text" id="pekerjaan" name="pekerjaan" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        // DataTables initialization
        $(document).ready(function() {
            $('#pendudukTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json' // Mengatur bahasa DataTables
                }
            });
        });

        // Modal functions
        const modal = document.getElementById('pendudukModal');
        const form = document.getElementById('pendudukForm');
        
        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Penduduk'; // Judul modal
            document.getElementById('formAction').value = 'add'; // Set aksi ke 'add'
            form.reset(); // Reset form
            modal.style.display = 'block'; // Tampilkan modal
        }

        function showEditModal(nik) {
            document.getElementById('modalTitle').textContent = 'Edit Penduduk'; // Judul modal
            document.getElementById('formAction').value = 'edit'; // Set aksi ke 'edit'
            
            // Ambil data dari baris tabel
            const row = document.querySelector(`tr:has(td:first-child:contains('${nik}'))`);
            const cells = row.cells;
            
            // Isi form dengan data
            document.getElementById('nik').value = cells[0].textContent;
            document.getElementById('nama').value = cells[1].textContent;
            document.getElementById('tempat_lahir').value = cells[2].textContent;
            document.getElementById('tanggal_lahir').value = cells[3].textContent;
            document.getElementById('jenis_kelamin').value = cells[4].textContent;
            document.getElementById('alamat').value = cells[5].textContent;
            document.getElementById('agama').value = cells[6].textContent;
            document.getElementById('status_perkawinan').value = cells[7].textContent;
            document.getElementById('pekerjaan').value = cells[8].textContent;
            
            modal.style.display = 'block'; // Tampilkan modal
        }

        function closeModal() {
            modal.style.display = 'none'; // Sembunyikan modal
        }

        function deletePenduduk(nik) {
            // Konfirmasi penghapusan data
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                const form = document.createElement('form'); // Buat form untuk menghapus
                form.method = 'POST'; // Set metode ke POST
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete"> <!-- Aksi hapus -->
                    <input type="hidden" name="nik" value="${nik}"> <!-- NIK yang akan dihapus -->
                `;
                document.body.appendChild(form); // Tambahkan form ke body
                form.submit(); // Kirim form
            }
        }

        // Tutup modal saat mengklik di luar modal
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal(); // Tutup modal
            }
        }
    </script>
</body>
</html>
