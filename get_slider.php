<?php
session_start();
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM slider WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $slider = mysqli_fetch_assoc($result);
        echo json_encode($slider);
    } else {
        echo json_encode(['error' => 'Slider tidak ditemukan']);
    }
}
?>