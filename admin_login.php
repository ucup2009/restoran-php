<?php
session_start();
// If there's an error message from the login process, capture and clear it
$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
$old_username = isset($_SESSION['old_username']) ? $_SESSION['old_username'] : '';
// Clear session values used for flash messaging
unset($_SESSION['login_error'], $_SESSION['old_username']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Dapur Azizah</title>
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
                <h1>Admin Login</h1>
            </div>

            <?php if (!empty($login_error)): ?>
                <div class="error-message" style="color: #9b1c1c; font-weight:600; margin-bottom:12px;">
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>

            <form action="login_admin.php" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan Username Anda" required value="<?php echo htmlspecialchars($old_username); ?>">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan Password Anda" required>
                </div>

                <button type="submit" class="btn-login">MASUK KE DASHBOARD</button>
            </form>

            <div class="back-link">
                <a href="index.php">← Kembali ke Pilihan Login</a>
            </div>
        </div>
    </div>
</body>
</html>