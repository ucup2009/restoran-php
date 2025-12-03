<?php
session_start();
include 'koneksi.php';

// Pastikan hanya diakses melalui metode POST dari form checkout
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: checkout.php");
    exit();
}

// Cek apakah user sudah login dan keranjang tidak kosong
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    $_SESSION['flash_message'] = "Sesi Anda telah berakhir atau keranjang kosong. Silakan login kembali.";
    header("Location: user_login.php");
    exit();
}

// ----------------------------------------------------
// 1. AMBIL DATA DARI SESSION DAN FORM
// ----------------------------------------------------
$user_id = $_SESSION['user_id'];
$cart_items = $_SESSION['cart'];
$grand_total = 0;

// Hitung Grand Total dari keranjang
foreach ($cart_items as $item) {
    $grand_total += ($item['price'] * $item['quantity']);
}

// Ambil data dari form checkout.php (Lindungi dari SQL Injection)
$nama_penerima = mysqli_real_escape_string($conn, $_POST['nama_penerima']);
$email_penerima = mysqli_real_escape_string($conn, $_POST['email_penerima']);
$telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);

// Status order default: Menunggu Pembayaran
$status_order = "Menunggu Pembayaran";
$tanggal_order = date('Y-m-d H:i:s');

// ----------------------------------------------------
// 2. MULAI TRANSAKSI DATABASE (KRITIS)
// ----------------------------------------------------
$success = true;

mysqli_begin_transaction($conn);

try {
    // A. MASUKKAN DATA KE TABEL orders
    $query_order = "INSERT INTO orders (
        user_id, total_bayar, tanggal_order, status_order, 
        nama_penerima, email_penerima, telepon, alamat, metode_pembayaran
    ) VALUES (
        '$user_id', '$grand_total', '$tanggal_order', '$status_order',
        '$nama_penerima', '$email_penerima', '$telepon', '$alamat', '$metode_pembayaran'
    )";
    
    if (!mysqli_query($conn, $query_order)) {
        throw new Exception("ERROR ORDERS: Gagal menyimpan data order utama. SQL Error: " . mysqli_error($conn));
    }
    
    $order_id = mysqli_insert_id($conn);

    // B. MASUKKAN DATA ITEM KE TABEL order_details
    foreach ($cart_items as $item) {
        $product_id = mysqli_real_escape_string($conn, $item['id']);
        $quantity = (int)$item['quantity'];
        $price_satuan = (float)$item['price'];
        $subtotal = $quantity * $price_satuan;

        $query_detail = "INSERT INTO order_details (
            order_id, produk_id, quantity, price_satuan, subtotal
        ) VALUES (
            '$order_id', '$product_id', '$quantity', '$price_satuan', '$subtotal'
        )";
        
        if (!mysqli_query($conn, $query_detail)) {
            throw new Exception("ERROR DETAILS: Gagal menyimpan item keranjang. Produk ID: " . $product_id . ". SQL Error: " . mysqli_error($conn));
        }
    }

    // ----------------------------------------------------
    // 3. SELESAI DAN BERSIHKAN
    // ----------------------------------------------------
    mysqli_commit($conn);

    // Simpan order id ke session agar order_confirm bisa menampilkan detail
    $_SESSION['last_order_id'] = $order_id;

    // Kosongkan keranjang pembeli
    unset($_SESSION['cart']);

    // Redirect ke halaman konfirmasi order
    header("Location: order_confirm.php");
    exit();

} catch (Exception $e) {
    // JIKA ADA ERROR SQL, ROLLBACK
    mysqli_rollback($conn);
    
    echo "<h1>PROSES CHECKOUT GAGAL!</h1>";
    echo "<p>Penyebab: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Silakan periksa struktur tabel database Anda (orders dan order_details).</p>";
    // Untuk debugging lokal, tampilkan error, tapi di production gunakan logging
    die();
}

mysqli_close($conn);
?>