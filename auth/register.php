<?php
session_start();

// Cek apakah file database.php ada
$database_file = '../config/database.php';
if (file_exists($database_file)) {
    include $database_file;
} else {
    die("File konfigurasi database tidak ditemukan.");
}

// Proses registrasi jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil dan membersihkan input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $nik = trim($_POST['nik']);

    // Validasi input
    $errors = [];

    if (empty($username) || empty($password) || empty($confirm_password) || empty($nama) || empty($email) || empty($nik)) {
        $errors[] = "Semua field harus diisi.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Password dan konfirmasi password tidak cocok.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter.";
    }

    if (strlen($nik) != 16 || !is_numeric($nik)) {
        $errors[] = "NIK harus 16 digit angka.";
    }

    // Cek apakah username sudah ada
    if (empty($errors)) {
        $check_username = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_username);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "Username sudah digunakan.";
        }
        $stmt->close();
    }

    // Cek apakah email sudah ada
    if (empty($errors)) {
        $check_email = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_email);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "Email sudah digunakan.";
        }
        $stmt->close();
    }

    // Cek apakah NIK sudah ada
    if (empty($errors)) {
        $check_nik = "SELECT id FROM users WHERE nik = ?";
        $stmt = $conn->prepare($check_nik);
        $stmt->bind_param("s", $nik);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "NIK sudah terdaftar.";
        }
        $stmt->close();
    }

    // Jika tidak ada error, lakukan registrasi
    if (empty($errors)) {
        // Enkripsi password dengan MD5
        $hashed_password = md5($password);
        
        // Default role adalah 'user'
        $role = 'user';

        // Query untuk insert user baru
        $sql = "INSERT INTO users (username, password, nama, email, nik, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssssss", $username, $hashed_password, $nama, $email, $nik, $role);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Terjadi kesalahan saat registrasi: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Terjadi kesalahan dalam query database.";
        }
    }
    
    // Jika ada errors, simpan di session untuk ditampilkan
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem Informasi Kependudukan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            max-width: 500px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.2em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #4a4f54;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
            outline: none;
        }

        button {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            color: red;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background-color: #ffe6e6;
            border: 1px solid #ffcccc;
            border-radius: 5px;
        }

        .success {
            color: green;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background-color: #e6ffe6;
            border: 1px solid #ccffcc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registrasi Akun</h2>
        
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert">
                <?php 
                foreach ($_SESSION['errors'] as $error) {
                    echo htmlspecialchars($error) . "<br>";
                }
                unset($_SESSION['errors']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success">
                <?php 
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <?php 
            $old_username = $_SESSION['old_input']['username'] ?? '';
            $old_nama = $_SESSION['old_input']['nama'] ?? '';
            $old_email = $_SESSION['old_input']['email'] ?? '';
            $old_nik = $_SESSION['old_input']['nik'] ?? '';
            unset($_SESSION['old_input']);
            ?>
            
            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($old_nama); ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($old_username); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($old_email); ?>" required>
            </div>

            <div class="form-group">
                <label for="nik">NIK (16 digit):</label>
                <input type="text" id="nik" name="nik" value="<?php echo htmlspecialchars($old_nik); ?>" maxlength="16" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit">Daftar</button>
        </form>
        
        <div class="login-link">
            Sudah memiliki akun? <a href="login.php">Login di sini</a>
        </div>
    </div>

    <script>
        // Validasi NIK harus angka
        document.getElementById('nik').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Validasi password match
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        function validatePassword() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Password tidak cocok");
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        password.addEventListener('change', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);
    </script>
</body>
</html>