<?php
session_start();
// Pastikan koneksi ke database di-include
include 'koneksi.php'; 

// 1. Periksa keamanan Admin
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// 2. Pastikan ID pelanggan diterima
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manajemen_pelanggan.php");
    exit();
}

// 3. Ambil dan bersihkan ID pelanggan
// Penting: Gunakan intval() jika ID selalu berupa angka, atau mysqli_real_escape_string()
$id = mysqli_real_escape_string($conn, $_GET['id']);

// 4. Query untuk menghapus data pelanggan secara PERMANEN
// Pastikan nama tabel adalah 'user'
$query_delete = "DELETE FROM user WHERE id = '$id'";

// 5. Eksekusi Query
$result = mysqli_query($conn, $query_delete);

if ($result) {
    // Jika penghapusan berhasil, kembali ke halaman manajemen pelanggan
    header("Location: manajemen_pelanggan.php?status=delete_success");
    exit();
} else {
    // Jika gagal, tampilkan pesan error SQL
    die("Gagal menghapus pelanggan: " . mysqli_error($conn));
}
?>