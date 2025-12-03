<?php
session_start();
// Ambil pesan error dan bersihkan sesi (jika ada)
$login_error = isset($_SESSION['user_login_error']) ? $_SESSION['user_login_error'] : '';
$old_username = isset($_SESSION['user_old_username']) ? $_SESSION['user_old_username'] : '';
unset($_SESSION['user_login_error'], $_SESSION['user_old_username']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pembeli - Dapur Azizah</title>
    <link rel="stylesheet" href="css/admin_login.css"> 
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo-area">
                <img src="img/1.png" alt="Logo Dapur Azizah">
                <h1>Pembeli Login</h1>
            </div>

            <?php if (!empty($login_error)): ?>
                <div class="error-message" style="color: #9b1c1c; font-weight:600; margin-bottom:12px;">
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>

            <form action="login_user.php" method="POST">
                <div class="input-group">
                    <label for="username">Email atau Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan Email/Username Anda" required value="<?php echo htmlspecialchars($old_username); ?>">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan Password Anda" required>
                </div>
                
                <button type="submit" class="btn-login" style="background-color: black;">MASUK SEBAGAI PEMBELI</button>
            </form>
            
           <p style="text-align: center; margin-top: 15px;">Belum punya akun? <a href="register.php" style="color: #c90000; text-decoration: none;">Daftar di sini</a></p>

            <div class="back-link">
                <a href="index.html">← Kembali ke Pilihan Login</a>
            </div>
        </div>
    </div>
</body>
</html>