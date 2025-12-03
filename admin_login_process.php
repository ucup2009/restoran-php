<?php
session_start();
// Pastikan path ke koneksi.php benar (keluar dari folder 'admin')
include 'koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil input dari form dan lindungi dari SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hashing password dengan MD5 (disesuaikan dengan metode yang digunakan saat registrasi admin)
    // Jika password di database Anda tidak di-hash (sangat tidak disarankan), hapus fungsi MD5().
    $hashed_password = MD5($password); 

    // Query untuk mencari Admin yang sesuai
    // Sesuaikan nama kolom tabel 'admin' jika berbeda dari 'username' dan 'password'
    $query = "SELECT id, username, nama_lengkap FROM admin WHERE username = '$username' AND password = '$hashed_password'";
    
    $result = mysqli_query($conn, $query); 

    if (mysqli_num_rows($result) == 1) {
        // --- LOGIN BERHASIL ---
        $admin = mysqli_fetch_assoc($result);
        
        // Atur Sesi Admin
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['is_admin_logged_in'] = true; // FLAG PENTING untuk keamanan Dashboard
        
        // Arahkan ke Dashboard
        header("Location: admin_dashboard.php"); 
        exit();
    } else {
        // --- LOGIN GAGAL ---
        // Simpan pesan error ke sesi flash (untuk ditampilkan di admin_login.php)
        $_SESSION['flash_message'] = 'Username atau password Admin salah. Coba lagi.';
        header("Location: admin_login.php");
        exit();
    }
} else {
    // Jika diakses tanpa metode POST, redirect kembali ke halaman login
    header("Location: admin_login.php");
    exit();
}
?>