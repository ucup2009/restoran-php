<?php
session_start();

// Cek apakah ID produk ada di URL dan di Session keranjang
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);

    if (isset($_SESSION['cart'][$product_id])) {
        // Hapus item dari sesi keranjang
        unset($_SESSION['cart'][$product_id]);

        // Opsional: set flash message
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => 'Item berhasil dihapus dari keranjang.'
        ];
    }
}

// Redirect kembali ke halaman keranjang
header("Location: cart.php");
exit();
?>