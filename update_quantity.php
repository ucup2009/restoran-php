<?php
session_start();

// Hanya izinkan akses jika ada cart dan parameter yang valid
if (!isset($_SESSION['cart']) || !isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: cart.php");
    exit();
}

$id = intval($_GET['id']);
$action = $_GET['action']; // 'increase' or 'decrease'

// Pastikan ID produk ada di keranjang
if (array_key_exists($id, $_SESSION['cart'])) {
    if ($action === 'increase') {
        // Tambah kuantitas
        $_SESSION['cart'][$id]['quantity']++;
    } elseif ($action === 'decrease') {
        // Kurangi kuantitas
        $_SESSION['cart'][$id]['quantity']--;
        
        // Jika kuantitas menjadi 0 atau kurang, hapus item
        if ($_SESSION['cart'][$id]['quantity'] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
}

// Redirect kembali ke halaman keranjang
header("Location: cart.php");
exit();
?>





