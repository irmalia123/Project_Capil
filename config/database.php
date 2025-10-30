<?php
// config/database.php
$host = "localhost";
$username = "root"; 
$password = "";
$database = "siak_parepare"; // Sesuai dengan error sebelumnya

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");
?>