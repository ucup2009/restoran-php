<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menggunakan variabel $conn yang benar dari koneksi.php
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Menggunakan MD5 sesuai penyimpanan di database
    $query = "SELECT * FROM admin WHERE username='$username' AND password=MD5('$password')";
    $result = mysqli_query($conn, $query); // Menggunakan $conn

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Meneruskan pesan error melalui Sesi ke admin_login.php
        $_SESSION['login_error'] = "Username atau password salah!";
        // Meneruskan username yang salah agar tetap terisi di form
        $_SESSION['old_username'] = $_POST['username'];
        // Mengarahkan ke halaman login PHP
        echo "<script>window.location='admin_login.php';</script>";
    }
    
}
?>