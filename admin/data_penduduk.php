<?php
// Mengimpor file session dan database untuk mengelola sesi pengguna dan koneksi database
include '../config/session.php';
include '../config/database.php'; // Include database connection
redirect_if_not_logged_in(); // Redirect pengguna jika tidak terautentikasi

// Menangani permintaan penghapusan data
if (isset($_GET['delete_nik'])) {
    $delete_nik = mysqli_real_escape_string($conn, $_GET['delete_nik']); // Mengamankan input NIK
    $delete_query = "DELETE FROM data_penduduk WHERE nik = '$delete_nik'"; // Query untuk menghapus data
    if (mysqli_query($conn, $delete_query)) {
        // Jika berhasil menghapus, redirect dengan pesan sukses
        header('Location: data_penduduk.php?message=Data berhasil dihapus');
        exit;
    } else {
        // Jika gagal menghapus, redirect dengan pesan gagal
        header('Location: data_penduduk.php?message=Gagal menghapus data');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penduduk</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome untuk ikon -->
    <style>
        /* Reset dan Global */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; /* Mengatur box-sizing untuk semua elemen */
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
            flex-grow: 1; /* Konten utama mengambil ruang yang tersisa */
            padding: 20px; /* Ruang di dalam konten utama */
            display: flex; /* Mengatur konten utama dengan flexbox */
            flex-direction: column; /* Mengatur arah konten menjadi kolom */
            margin-left: 280px; /* Jarak kiri untuk konten utama */
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

        .main-content .header {
            display: flex; /* Mengatur header dengan flexbox */
            justify-content: space-between; /* Jarak antar elemen dalam header */
            align-items: center; /* Pusatkan secara vertikal */
            margin-bottom: 20px; /* Jarak bawah header */
        }

        .button-tambah {
            padding: 10px 15px; /* Ruang di dalam tombol tambah */
            background-color: #3498db; /* Warna latar belakang tombol tambah */
            color: white; /* Warna teks tombol tambah */
            border: none; /* Tanpa garis batas */
            border-radius: 5px; /* Sudut melengkung */
            cursor: pointer; /* Kursor tangan saat hover */
            font-size: 1rem; /* Ukuran font */
            transition: background-color 0.3s, transform 0.3s; /* Transisi untuk efek hover */
        }

        .button-tambah:hover {
            background-color: #2980b9; /* Warna latar belakang saat hover */
            transform: translateY(-2px); /* Efek angkat saat hover */
        }

        /* Table styling */
        .table-container {
            background-color: white; /* Warna latar belakang tabel */
            border-radius: 8px; /* Sudut melengkung */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Bayangan tabel */
            overflow-x: auto; /* Mengizinkan scroll horizontal jika diperlukan */
        }

        table {
            width: 100%; /* Lebar tabel penuh */
            border-collapse: collapse; /* Menghilangkan jarak antara sel */
        }

        table thead {
            background-color: #1e40af; /* Warna latar belakang header tabel */
            color: white; /* Warna teks header tabel */
        }

        table thead tr th {
            padding: 10px 15px; /* Ruang di dalam header tabel */
            text-align: left; /* Rata kiri untuk teks header */
            font-size: 0.9rem; /* Ukuran font header */
        }

        table tbody tr td {
            padding: 10px 15px; /* Ruang di dalam sel tabel */
            border-bottom: 1px solid #e0e0e0; /* Garis bawah sel tabel */
            font-size: 0.9rem; /* Ukuran font sel tabel */
        }

        table tbody tr:hover {
            background-color: #f9f9f9; /* Warna latar belakang saat hover pada baris tabel */
        }

        /* Status Styles */
        .status {
            display: inline-block; /* Menampilkan status sebagai blok inline */
            padding: 5px 10px; /* Ruang di dalam status */
            border-radius: 4px; /* Sudut melengkung */
            font-size: 0.8rem; /* Ukuran font status */
            font-weight: bold; /* Ketebalan font status */
        }

        .status.active {
            background-color: #2ecc71; /* Warna latar belakang untuk status aktif */
            color: white; /* Warna teks untuk status aktif */
        }

        .status.inactive {
            background-color: #e74c3c; /* Warna latar belakang untuk status tidak aktif */
            color: white; /* Warna teks untuk status tidak aktif */
        }

        /* Action buttons */
        .action-buttons {
            display: flex; /* Mengatur tombol aksi dengan flexbox */
            gap: 10px; /* Jarak antar tombol aksi */
        }

        .btn {
            padding: 5px 10px; /* Ruang di dalam tombol */
            border: none; /* Tanpa garis batas */
            border-radius: 4px; /* Sudut melengkung */
            cursor: pointer; /* Kursor tangan saat hover */
            font-size: 0.9rem; /* Ukuran font tombol */
            display: flex; /* Mengatur tombol dengan flexbox */
            align-items: center; /* Pusatkan secara vertikal */
            gap: 5px; /* Jarak antara ikon dan teks tombol */
        }

        .btn-edit {
            background-color: #3498db; /* Warna latar belakang tombol edit */
            color: white; /* Warna teks tombol edit */
        }

        .btn-edit:hover {
            background-color: #2980b9; /* Warna latar belakang saat hover tombol edit */
        }

        .btn-delete {
            background-color: #e74c3c; /* Warna latar belakang tombol hapus */
            color: white; /* Warna teks tombol hapus */
        }

        .btn-delete:hover {
            background-color: #c0392b; /* Warna latar belakang saat hover tombol hapus */
        }

        /* Alert Styles */
        .alert {
            background-color: #2ecc71; /* Warna latar belakang untuk pesan sukses */
            color: white; /* Warna teks untuk pesan sukses */
            padding: 10px 15px; /* Ruang di dalam pesan */
            border-radius: 5px; /* Sudut melengkung */
            margin-bottom: 20px; /* Jarak bawah pesan */
            font-size: 0.9rem; /* Ukuran font pesan */
        }

        /* Footer Styles */
        footer {
            background-color: #1e40af; /* Warna latar belakang footer */
            color: white; /* Warna teks footer */
            text-align: center; /* Pusatkan teks dalam footer */
            padding: 10px 20px; /* Ruang di dalam footer */
            position: relative; /* Posisi footer */
            bottom: 0; /* Menempel di bagian bawah */
            width: 100%; /* Lebar penuh */
        }

        .footer-content p {
            margin: 0; /* Menghilangkan margin untuk paragraf */
        }

        .footer-links {
            margin-top: 10px; /* Jarak atas untuk tautan footer */
        }

        .footer-links a {
            margin: 0 10px; /* Jarak horizontal antar tautan */
            color: white; /* Warna tautan footer */
            font-size: 0.9rem; /* Ukuran font tautan footer */
            transition: color 0.3s; /* Transisi untuk efek hover pada tautan */
        }

        .footer-links a:hover {
            color: #3498db; /* Warna tautan saat hover */
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
        <h1>Data Penduduk</h1>
        <button class="button-tambah" onclick="window.location.href='tambah_data_penduduk.php'">Tambah Data Penduduk</button>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert"><?php echo htmlspecialchars($_GET['message']); ?></div> <!-- Menampilkan pesan jika ada -->
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Agama</th>
                    <th>Pendidikan</th>
                    <th>Golongan Darah</th>
                    <th>Status Warga</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mengambil data dari database
                $query = "SELECT * FROM data_penduduk";
                $result = mysqli_query($conn, $query);

                while ($data = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$data['nik']}</td>
                            <td>{$data['nama']}</td>
                            <td>{$data['alamat']}</td>
                            <td>{$data['tempat_lahir']}</td>
                            <td>{$data['tanggal_lahir']}</td>
                            <td>{$data['agama']}</td>
                            <td>{$data['pendidikan']}</td>
                            <td>{$data['golongan_darah']}</td>
                            <td><span class='status " . ($data['status_warga'] === 'Aktif' ? 'active' : 'inactive') . "'>{$data['status_warga']}</span></td>
                            <td>" . ($data['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan') . "</td>
                            <td>
                                <div class='action-buttons'>
                                    <button class='btn btn-edit' onclick='window.location.href=\"edit_penduduk.php?nik={$data['nik']}\"'>
                                        <i class='fas fa-edit'></i> Edit
                                    </button>
                                    <button class='btn btn-delete' onclick='confirmDelete(\"{$data['nik']}\")'>
                                        <i class='fas fa-trash'></i> Hapus
                                    </button>
                                </div>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function confirmDelete(nik) {
    // Konfirmasi penghapusan data
    if (confirm("Apakah Anda yakin ingin menghapus data dengan NIK: " + nik + "?")) {
        window.location.href = "?delete_nik=" + nik; // Redirect ke aksi hapus
    }
}
</script>

</body>
</html>
