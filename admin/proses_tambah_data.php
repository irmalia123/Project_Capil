<?php
include '../config/database.php'; // Hubungkan ke database Anda

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $agama = mysqli_real_escape_string($conn, $_POST['agama']);
    $pendidikan = mysqli_real_escape_string($conn, $_POST['pendidikan']);
    $golongan_darah = mysqli_real_escape_string($conn, $_POST['golongan_darah']);
    $status_warga = mysqli_real_escape_string($conn, $_POST['status_warga']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);

    // Memasukkan data ke dalam database
    $query = "INSERT INTO data_penduduk (nik, nama, alamat, tempat_lahir, tanggal_lahir, agama, pendidikan, golongan_darah, status_warga, jenis_kelamin) VALUES ('$nik', '$nama', '$alamat', '$tempat_lahir', '$tanggal_lahir', '$agama', '$pendidikan', '$golongan_darah', '$status_warga', '$jenis_kelamin')";

    if (mysqli_query($conn, $query)) {
        header('Location: data_penduduk.php'); // Redirect kembali ke halaman data penduduk saat berhasil
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn); // Tampilkan pesan kesalahan jika terjadi masalah
    }
}

mysqli_close($conn); // Tutup koneksi
?>
