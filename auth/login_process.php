<?php
session_start();
include '../config/database.php'; // Sesuaikan dengan path database Anda

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Query untuk cek user
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')");
    
    if(mysqli_num_rows($query) == 1){
        $data = mysqli_fetch_array($query);
        
        // Set session
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $data['level'];
        $_SESSION['login'] = true;
        
        // REDIRECT KE HALAMAN ADMIN - INI YANG PERLU DIPERBAIKI
        header("Location: ../admin/index.php");
        exit();
    } else {
        // Login gagal
        $_SESSION['error'] = "Username atau password salah!";
        header("Location: login.php");
        exit();
    }
} else {
    // Jika akses langsung
    header("Location: login.php");
    exit();
}
?>