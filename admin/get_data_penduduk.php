<?php
include '../config/config.php'; // Sesuaikan dengan jalur file config Anda
header('Content-Type: application/json');

//koneksi dengan database
try {
    $query = $pdo->query("SELECT nik, nama, alamat, status FROM penduduk ORDER BY id DESC LIMIT 10");
    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database Error: ' . $e->getMessage()]);
}
?>
