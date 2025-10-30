<?php
// Mengimpor file session untuk mengelola sesi pengguna
include '../config/session.php';
// Mengimpor file koneksi database
include '../config/database.php'; // Sertakan file koneksi database Anda
// Redirect pengguna jika tidak terautentikasi
redirect_if_not_logged_in();

// Proses data jika formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data suami dari formulir
    $nik_suami = $_POST['nik_suami'];
    $kk_suami = $_POST['kk_suami'];
    $paspor_suami = $_POST['paspor_suami'];
    $nama_suami = $_POST['nama_suami'];
    $tempat_lahir_suami = $_POST['tempat_lahir_suami'];
    $tanggal_lahir_suami = $_POST['tanggal_lahir_suami'];
    $alamat_suami = $_POST['alamat_suami'];
    $pendidikan_terakhir_suami = $_POST['pendidikan_terakhir_suami'];
    $agama_suami = $_POST['agama_suami'];
    $pekerjaan_suami = $_POST['pekerjaan_suami'];
    $anak_ke_suami = $_POST['anak_ke_suami'];
    $status_perkawinan_suami = $_POST['status_perkawinan_suami'];
    $perkawinan_ke_suami = $_POST['perkawinan_ke_suami'];
    $istri_ke_suami = $_POST['istri_ke_suami'];
    $kewarganegaraan_suami = $_POST['kewarganegaraan_suami'];
    $kebangsaan_suami = $_POST['kebangsaan_suami'];

    // Mengambil data ayah dari formulir
    $nik_ayah_suami = $_POST['nik_ayah_suami'];
    $nama_ayah_suami = $_POST['nama_ayah_suami'];
    $agama_ayah_suami = $_POST['agama_ayah_suami'];
    $tempat_lahir_ayah_suami = $_POST['tempat_lahir_ayah_suami'];
    $tanggal_lahir_ayah_suami = $_POST['tanggal_lahir_ayah_suami'];
    $alamat_ayah_suami = $_POST['alamat_ayah_suami'];
    $pekerjaan_ayah_suami = $_POST['pekerjaan_ayah_suami'];

    // Mengambil data ibu dari formulir
    $nik_ibu_suami = $_POST['nik_ibu_suami'];
    $nama_ibu_suami = $_POST['nama_ibu_suami'];
    $agama_ibu_suami = $_POST['agama_ibu_suami'];
    $tempat_lahir_ibu_suami = $_POST['tempat_lahir_ibu_suami'];
    $tanggal_lahir_ibu_suami = $_POST['tanggal_lahir_ibu_suami'];
    $alamat_ibu_suami = $_POST['alamat_ibu_suami'];
    $pekerjaan_ibu_suami = $_POST['pekerjaan_ibu_suami'];

    // Siapkan pernyataan SQL untuk menyimpan data ke dalam tabel pencatatan_perkawinan
    $stmt = $conn->prepare("INSERT INTO pencatatan_perkawinan (
        nik_suami, 
        kk_suami, 
        paspor_suami, 
        nama_suami, 
        tempat_lahir_suami, 
        tanggal_lahir_suami, 
        alamat_suami, 
        pendidikan_terakhir_suami, 
        agama_suami, 
        pekerjaan_suami, 
        anak_ke_suami, 
        status_perkawinan_suami, 
        perkawinan_ke_suami, 
        istri_ke_suami, 
        kewarganegaraan_suami, 
        kebangsaan_suami, 
        nik_ayah_suami, 
        nama_ayah_suami, 
        agama_ayah_suami, 
        tempat_lahir_ayah_suami, 
        tanggal_lahir_ayah_suami, 
        alamat_ayah_suami, 
        pekerjaan_ayah_suami, 
        nik_ibu_suami, 
        nama_ibu_suami, 
        agama_ibu_suami, 
        tempat_lahir_ibu_suami, 
        tanggal_lahir_ibu_suami, 
        alamat_ibu_suami, 
        pekerjaan_ibu_suami
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameter untuk pernyataan SQL
    $stmt->bind_param("ssssssssiiissssssssssssssssssss", 
        $nik_suami, 
        $kk_suami, 
        $paspor_suami, 
        $nama_suami, 
        $tempat_lahir_suami, 
        $tanggal_lahir_suami, 
        $alamat_suami, 
        $pendidikan_terakhir_suami, 
        $agama_suami, 
        $pekerjaan_suami, 
        $anak_ke_suami, 
        $status_perkawinan_suami, 
        $perkawinan_ke_suami, 
        $istri_ke_suami, 
        $kewarganegaraan_suami, 
        $kebangsaan_suami, 
        $nik_ayah_suami, 
        $nama_ayah_suami, 
        $agama_ayah_suami, 
        $tempat_lahir_ayah_suami, 
        $tanggal_lahir_ayah_suami, 
        $alamat_ayah_suami, 
        $pekerjaan_ayah_suami, 
        $nik_ibu_suami, 
        $nama_ibu_suami, 
        $agama_ibu_suami, 
        $tempat_lahir_ibu_suami, 
        $tanggal_lahir_ibu_suami, 
        $alamat_ibu_suami, 
        $pekerjaan_ibu_suami
    );

    // Eksekusi pernyataan
    if ($stmt->execute()) {
        // Jika berhasil, tampilkan pesan sukses
        echo "<script>alert('Pencatatan perkawinan berhasil!');</script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Menutup pernyataan dan koneksi
    $stmt->close();
    $conn->close();
}
?>
