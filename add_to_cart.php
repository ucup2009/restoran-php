<?php
// KRITIS 1: Selalu mulai session di awal setiap file PHP
session_start();

// KRITIS 2: Sertakan koneksi database
include 'koneksi.php'; // Pastikan path ini benar

// =========================================================
// KRITIS 3: Cek apakah user sudah login sebelum menambahkan ke keranjang
if (!isset($_SESSION['user_id'])) {
    // Set pesan flash agar user tahu mengapa mereka diarahkan ke halaman login
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'message' => 'Anda harus login untuk mulai memesan.'
    ];
    
    // Arahkan ke halaman login user dan HENTIKAN eksekusi script
    header("Location: user_login.php"); 
    exit();
}
// =========================================================

// 1. Ambil ID Produk dari URL (Hanya dijalankan jika user sudah login)
if (isset($_GET['id']) && $_GET['id'] !== '') {
    // Pastikan ID integer
    $product_id = intval($_GET['id']);
    
    if ($product_id <= 0) {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'message' => 'ID produk tidak valid.'
        ];
        header("Location: menu_dine_in.php"); 
        exit();
    }

    $user_id = $_SESSION['user_id'];
    
    // 2. Ambil detail produk dari database (untuk harga)
    $query = "SELECT id, nama_makanan, harga_makanan FROM produk WHERE id = '$product_id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // 3. Logika penambahan/update keranjang
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Gunakan integer key untuk konsistensi
        $key = (int)$product['id'];

        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$key] = [
                'id' => (int)$product['id'],
                'name' => $product['nama_makanan'],
                'price' => (float)$product['harga_makanan'],
                'quantity' => 1
            ];
        }
        
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => "Produk \"" . $product['nama_makanan'] . "\" berhasil ditambahkan ke keranjang!"
        ];

    } else {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'message' => 'Produk tidak ditemukan!'
        ];
    }
} else {
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'message' => 'ID produk tidak valid.'
    ];
}

// 5. Redirect kembali ke halaman menu
header("Location: menu_dine_in.php"); 
exit();
?>