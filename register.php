<?php
session_start();
include 'koneksi.php'; // Menggunakan koneksi database yang sudah ada

$register_error = '';
$register_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Amankan dan ambil data dari form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    
    // 1. Cek apakah username atau email sudah terdaftar
    $check_query = "SELECT * FROM user WHERE username = '$username' OR email = '$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $register_error = "Username atau Email sudah terdaftar. Silakan gunakan yang lain.";
    } else {
        // 2. Hash password (menggunakan MD5)
        $hashed_password = MD5($password);
        
        // 3. Query INSERT data user baru
        $insert_query = "INSERT INTO user (username, email, password, nama_lengkap) 
                         VALUES ('$username', '$email', '$hashed_password', '$nama_lengkap')";
        
        if (mysqli_query($conn, $insert_query)) {
            $register_success = "Pendaftaran berhasil!";
            // Kosongkan form setelah sukses
            $_POST = array(); 
        } else {
            $register_error = "Pendaftaran gagal: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembeli - Dapur Azizah</title>
    <link rel="stylesheet" href="admin_login.css"> 
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <style>
        
        .login-box {
            width: 450px; 
            padding: 30px;
        }
        .success-message {
            color: #28a745; 
            font-weight: 600; 
            margin-bottom: 15px; 
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo-area">
                <img src="img/1.png" alt="Logo Dapur Azizah">
                <h1>Daftar Akun Pembeli</h1>
            </div>

            <?php if (!empty($register_error)): ?>
                <div class="error-message" style="color: #9b1c1c; font-weight:600; margin-bottom:12px; text-align: center;">
                    <?php echo htmlspecialchars($register_error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($register_success)): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($register_success); ?>
                    <br><a href="user_login.php" style="color: #c90000; font-weight: bold; text-decoration: none;">Klik di sini untuk Login</a>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                
                <div class="input-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap Anda" required value="<?php echo htmlspecialchars($_POST['nama_lengkap'] ?? ''); ?>">
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Alamat Email (Contoh: user@mail.com)" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username unik" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
                </div>
                
                <button type="submit" class="btn-login" style="background-color: #007bff;">DAFTAR SEKARANG</button>
            </form>
            
            <p style="text-align: center; margin-top: 15px;">Sudah punya akun? <a href="user_login.php" style="color: #c90000; text-decoration: none;">Login di sini</a></p>

            <div class="back-link">
                <a href="index.html">← Kembali ke Pilihan Login</a>
            </div>
        </div>
    </div>
</body>
</html>