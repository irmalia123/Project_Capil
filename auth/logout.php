<?php
session_start(); // Memulai sesi

// Hapus semua variabel sesi
$_SESSION = array();

// Hapus sesi
session_destroy();

// Arahkan pengguna kembali ke halaman login atau halaman lain
header("Location: ../login.php"); // Ganti dengan halaman login Anda
exit;
?>
