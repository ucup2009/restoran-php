<?php
session_start();
include 'koneksi.php';

// ... (Cek sesi admin) ...

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produk = mysqli_real_escape_string($conn, $_GET['id']);

    // KRITIS: MATIKAN FOREIGN KEY CHECK SEMENTARA
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

    // Lakukan dua DELETE tanpa transaksi (lebih sederhana)
    $delete_details_query = "DELETE FROM order_details WHERE produk_id = '$id_produk'";
    $delete_produk_query = "DELETE FROM produk WHERE id = '$id_produk'";
    
    $details_deleted = mysqli_query($conn, $delete_details_query);
    $produk_deleted = mysqli_query($conn, $delete_produk_query);

    // KRITIS: NYALAKAN KEMBALI FOREIGN KEY CHECK
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

    if ($details_deleted && $produk_deleted) {
        echo "<script>alert('Produk berhasil dihapus!'); window.location='manajemen_produk.php';</script>";
    } else {
        $error_message = mysqli_error($conn);
        echo "<script>alert('Error saat menghapus produk: " . $error_message . "'); window.location='manajemen_produk.php';</script>";
    }
} 
// ... (Bagian else) ...
?>