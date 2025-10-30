<?php
include '../config/session.php';
include '../config/database.php';
redirect_if_not_logged_in();

// Mengambil data penduduk berdasarkan NIK
if (isset($_GET['nik'])) {
    $nik = $_GET['nik'];
    $query = "SELECT * FROM data_penduduk WHERE nik = '$nik'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    
    if (!$data) {
        header('Location: data_penduduk.php');
        exit;
    }
} else {
    header('Location: data_penduduk.php');
    exit;
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $agama = mysqli_real_escape_string($conn, $_POST['agama']);
    $pendidikan = mysqli_real_escape_string($conn, $_POST['pendidikan']);
    $golongan_darah = mysqli_real_escape_string($conn, $_POST['golongan_darah']);
    $status_warga = mysqli_real_escape_string($conn, $_POST['status_warga']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);

    $update_query = "UPDATE data_penduduk SET 
                    nama = '$nama',
                    alamat = '$alamat',
                    tempat_lahir = '$tempat_lahir',
                    tanggal_lahir = '$tanggal_lahir',
                    agama = '$agama',
                    pendidikan = '$pendidikan',
                    golongan_darah = '$golongan_darah',
                    status_warga = '$status_warga',
                    jenis_kelamin = '$jenis_kelamin'
                    WHERE nik = '$nik'";

    if (mysqli_query($conn, $update_query)) {
        header('Location: data_penduduk.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Penduduk</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Reset dan Global */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; /* Mengatur box-sizing untuk semua elemen */
            font-family: 'Poppins', sans-serif; /* Mengatur font untuk seluruh halaman */
        }

        body {
            background-color: #f3f4f6; /* Warna latar belakang */
            min-height: 100vh; /* Memastikan tinggi minimal halaman */
            display: flex; /* Mengatur tampilan flex */
            justify-content: center; /* Pusatkan konten secara horizontal */
            align-items: center; /* Pusatkan konten secara vertikal */
            padding: 20px; /* Ruang di dalam body */
        }

        .container {
            max-width: 800px; /* Lebar maksimum kontainer */
            background: white; /* Warna latar belakang kontainer */
            padding: 30px; /* Ruang di dalam kontainer */
            border-radius: 15px; /* Sudut melengkung */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Bayangan kontainer */
        }

        h1 {
            color: #2c3e50; /* Warna teks judul */
            text-align: center; /* Pusatkan teks judul */
            margin-bottom: 30px; /* Jarak bawah judul */
            font-size: 2.2em; /* Ukuran font judul */
            position: relative; /* Posisi relatif untuk efek garis bawah */
            padding-bottom: 10px; /* Ruang bawah judul */
        }

        h1::after {
            content: ''; /* Garis bawah judul */
            position: absolute; /* Posisi absolut untuk garis */
            bottom: 0; /* Menempel di bawah judul */
            left: 50%; /* Pusatkan garis */
            transform: translateX(-50%); /* Pusatkan garis */
            width: 100px; /* Lebar garis */
            height: 4px; /* Tinggi garis */
            background: linear-gradient(90deg, #3498db, #2ecc71); /* Warna garis */
            border-radius: 2px; /* Sudut melengkung garis */
        }

        .form-group {
            margin-bottom: 20px; /* Jarak bawah untuk setiap grup formulir */
        }

        label {
            display: block; /* Tampilkan label sebagai blok */
            margin-bottom: 8px; /* Jarak bawah label */
            color: #495057; /* Warna teks label */
            font-weight: 500; /* Ketebalan font label */
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%; /* Lebar penuh untuk input dan select */
            padding: 12px; /* Ruang di dalam input dan select */
            border: 2px solid #e0e0e0; /* Garis batas input dan select */
            border-radius: 8px; /* Sudut melengkung */
            font-size: 14px; /* Ukuran font */
            transition: border-color 0.3s, box-shadow 0.3s; /* Transisi untuk efek fokus */
        }

        input:disabled {
            background-color: #e9ecef; /* Warna latar belakang untuk input yang dinonaktifkan */
        }

        input:focus,
        select:focus {
            border-color: #3498db; /* Warna batas saat fokus */
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.3); /* Bayangan saat fokus */
            outline: none; /* Menghilangkan outline default */
        }

        .btn-submit {
            background: linear-gradient(90deg, #3498db, #2ecc71); /* Warna latar belakang tombol submit */
            color: white; /* Warna teks tombol submit */
            border: none; /* Tanpa garis batas */
            padding: 15px; /* Ruang di dalam tombol */
            font-size: 1.1em; /* Ukuran font tombol */
            cursor: pointer; /* Kursor tangan saat hover */
            transition: transform 0.2s ease; /* Transisi untuk efek hover */
            display: block; /* Tampilkan tombol sebagai blok */
            margin: 0 auto; /* Pusatkan tombol */
            border-radius: 5px; /* Sudut melengkung tombol */
        }

        .btn-submit:hover {
            transform: translateY(-2px); /* Efek angkat saat hover */
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3); /* Bayangan saat hover */
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px; /* Ruang di dalam kontainer pada perangkat kecil */
            }

            h1 {
                font-size: 1.8em; /* Ukuran font judul pada perangkat kecil */
            }

            input, select {
                padding: 10px 12px; /* Ruang di dalam input dan select pada perangkat kecil */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Data Penduduk</h1>
        <form method="POST">
            <div class="form-group">
                <label>NIK</label>
                <input type="text" value="<?php echo htmlspecialchars($data['nik'], ENT_QUOTES); ?>" disabled> <!-- NIK tidak dapat diedit -->
            </div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama'], ENT_QUOTES); ?>" required> <!-- Input untuk nama -->
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" value="<?php echo htmlspecialchars($data['alamat'], ENT_QUOTES); ?>" required> <!-- Input untuk alamat -->
            </div>

            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="<?php echo htmlspecialchars($data['tempat_lahir'], ENT_QUOTES); ?>" required> <!-- Input untuk tempat lahir -->
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($data['tanggal_lahir'], ENT_QUOTES); ?>" required> <!-- Input untuk tanggal lahir -->
            </div>

            <div class="form-group">
                <label>Agama</label>
                <select name="agama" required> <!-- Dropdown untuk agama -->
                    <option value="Islam" <?php echo $data['agama'] == 'Islam' ? 'selected' : ''; ?>>Islam</option>
                    <option value="Kristen" <?php echo $data['agama'] == 'Kristen' ? 'selected' : ''; ?>>Kristen</option>
                    <option value="Katolik" <?php echo $data['agama'] == 'Katolik' ? 'selected' : ''; ?>>Katolik</option>
                    <option value="Hindu" <?php echo $data['agama'] == 'Hindu' ? 'selected' : ''; ?>>Hindu</option>
                    <option value="Buddha" <?php echo $data['agama'] == 'Buddha' ? 'selected' : ''; ?>>Buddha</option>
                    <option value="Konghucu" <?php echo $data['agama'] == 'Konghucu' ? 'selected' : ''; ?>>Konghucu</option>
                </select>
            </div>

            <div class="form-group">
                <label>Pendidikan</label>
                <input type="text" name="pendidikan" value="<?php echo htmlspecialchars($data['pendidikan'], ENT_QUOTES); ?>" required> <!-- Input untuk pendidikan -->
            </div>

            <div class="form-group">
                <label>Golongan Darah</label>
                <select name="golongan_darah" required> <!-- Dropdown untuk golongan darah -->
                    <option value="A" <?php echo $data['golongan_darah'] == 'A' ? 'selected' : ''; ?>>A</option>
                    <option value="B" <?php echo $data['golongan_darah'] == 'B' ? 'selected' : ''; ?>>B</option>
                    <option value="AB" <?php echo $data['golongan_darah'] == 'AB' ? 'selected' : ''; ?>>AB</option>
                    <option value="O" <?php echo $data['golongan_darah'] == 'O' ? 'selected' : ''; ?>>O</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status Warga</label>
                <select name="status_warga" required> <!-- Dropdown untuk status warga -->
                    <option value="Aktif" <?php echo $data['status_warga'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Tidak Aktif" <?php echo $data['status_warga'] == 'Tidak Aktif' ? 'selected' : ''; ?>>Tidak Aktif</option>
                </select>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" required> <!-- Dropdown untuk jenis kelamin -->
                    <option value="L" <?php echo $data['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="P" <?php echo $data['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button> <!-- Tombol untuk menyimpan perubahan -->
        </form>
    </div>
</body>
</html>
