<?php
session_start();

// Hanya izinkan akses melalui metode POST dari form keranjang
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

if (isset($_POST['qty']) && is_array($_POST['qty'])) {
    foreach ($_POST['qty'] as $id => $quantity) {
        // Sanitasi dan konversi ke integer
        $id = intval($id);
        $quantity = intval($quantity);
        
        // Pastikan ID produk ada di keranjang
        if (array_key_exists($id, $_SESSION['cart'])) {
            if ($quantity <= 0) {
                // Jika kuantitas 0 atau kurang, hapus item
                unset($_SESSION['cart'][$id]);
            } else {
                // Update kuantitas
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            }
        }
    }
}

// Redirect kembali ke halaman keranjang
header("Location: cart.php");
exit();
?>