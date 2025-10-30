<?php
// setup_database.php
include 'config/database.php';

echo "<h2>Setup Database Siakpare</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; } 
    .success { color: green; } 
    .error { color: red; } 
    .warning { color: orange; }
    table { border-collapse: collapse; margin: 10px 0; }
    td, th { border: 1px solid #ddd; padding: 8px; }
</style>";

// Hapus tabel users jika sudah ada (opsional)
// $conn->query("DROP TABLE IF EXISTS users");

// Buat tabel users dengan struktur yang benar
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_users) === TRUE) {
    echo "<p class='success'>✓ Tabel 'users' berhasil dibuat/ditemukan</p>";
    
    // Tampilkan struktur tabel
    echo "<h3>Struktur Tabel Users:</h3>";
    $result = $conn->query("DESCRIBE users");
    if ($result) {
        echo "<table>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p class='error'>✗ Error membuat tabel 'users': " . $conn->error . "</p>";
}

// Cek apakah kolom 'nama' sudah ada, jika belum tambahkan
$check_column = $conn->query("SHOW COLUMNS FROM users LIKE 'nama'");
if ($check_column->num_rows == 0) {
    // Tambahkan kolom nama
    $alter_sql = "ALTER TABLE users ADD COLUMN nama VARCHAR(100) NOT NULL AFTER password";
    if ($conn->query($alter_sql) === TRUE) {
        echo "<p class='success'>✓ Kolom 'nama' berhasil ditambahkan</p>";
    } else {
        echo "<p class='error'>✗ Error menambahkan kolom 'nama': " . $conn->error . "</p>";
    }
} else {
    echo "<p class='success'>✓ Kolom 'nama' sudah ada</p>";
}

// Sekarang insert data
echo "<h3>Menambahkan Data Sample:</h3>";

$insert_data = "INSERT INTO users (username, password, nama, email, role) VALUES 
    ('admin', MD5('admin123'), 'Administrator Sistem', 'admin@siakpare.com', 'admin'),
    ('user1', MD5('user123'), 'Budi Santoso', 'budi@email.com', 'user')";

if ($conn->query($insert_data) === TRUE) {
    echo "<p class='success'>✓ Data users berhasil ditambahkan</p>";
    
    // Tampilkan data yang berhasil diinsert
    echo "<h3>Data Users:</h3>";
    $result = $conn->query("SELECT id, username, nama, email, role FROM users");
    if ($result) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Username</th><th>Nama</th><th>Email</th><th>Role</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p class='error'>✗ Error menambahkan data: " . $conn->error . "</p>";
    
    // Coba insert satu per satu
    echo "<p class='warning'>Mencoba insert satu per satu...</p>";
    
    $users = [
        ['admin', 'admin123', 'Administrator Sistem', 'admin@siakpare.com', 'admin'],
        ['user1', 'user123', 'Budi Santoso', 'budi@email.com', 'user']
    ];
    
    foreach ($users as $user) {
        $sql = "INSERT INTO users (username, password, nama, email, role) VALUES (
            '{$user[0]}', MD5('{$user[1]}'), '{$user[2]}', '{$user[3]}', '{$user[4]}'
        )";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p class='success'>✓ User {$user[0]} berhasil ditambahkan</p>";
        } else {
            echo "<p class='error'>✗ Error menambahkan {$user[0]}: " . $conn->error . "</p>";
        }
    }
}

echo "<hr><h3 class='success'>Setup selesai!</h3>";
echo "<p><a href='auth/login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login ke Sistem</a></p>";
echo "<p><strong>Default Login:</strong><br>Username: admin<br>Password: admin123</p>";

$conn->close();
?>