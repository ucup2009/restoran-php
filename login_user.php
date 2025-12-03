<?php
session_start();
include 'koneksi.php'; // Pastikan Anda memiliki file koneksi.php yang benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil dan amankan input
    $username_or_email = mysqli_real_escape_string($conn, $_POST['username']); 
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // 2. Hash password yang diinput dengan MD5 (jika DB menggunakan MD5)
    $hashed_password = MD5($password);

    // 3. Query untuk mencari pengguna (user) berdasarkan username ATAU email, dan password
    $query = "SELECT id, username, nama_lengkap FROM user WHERE (username = '$username_or_email' OR email = '$username_or_email') AND password = '$hashed_password'";
    
    $result = mysqli_query($conn, $query); 

    if (mysqli_num_rows($result) == 1) {
        // LOGIN BERHASIL
        $user = mysqli_fetch_assoc($result);
        
        // Atur Sesi Pembeli
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_nama'] = $user['nama_lengkap'];
        $_SESSION['is_logged_in'] = true; // Flag bahwa pembeli sudah login
        
        // Redirect ke halaman menu utama pembeli
        header("Location: index.php"); 
        exit();
    } else {
        // LOGIN GAGAL
        // Supaya user_login.php bisa menampilkan pesan, gunakan keys yang dibaca di user_login.php
        $_SESSION['user_login_error'] = "Username/Email atau password salah. Silakan coba lagi.";
        $_SESSION['user_old_username'] = $_POST['username'];
        
        header("Location: user_login.php");
        exit();
    }
} else {
    // Jika diakses tanpa method POST (langsung diakses via URL)
    header("Location: user_login.php");
    exit();
}
?>