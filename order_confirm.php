<?php
session_start();
include 'koneksi.php';

// Fungsi format harga
function format_rupiah($angka) {
    return 'Rp' . number_format($angka, 0, ',', '.');
}

// Ambil ID order terakhir yang baru diproses dari sesi
$order_id = $_SESSION['last_order_id'] ?? null; 

if (!$order_id) {
    // Jika tidak ada ID order terakhir, arahkan user kembali ke beranda
    header("Location: index.php");
    exit();
}

// 1. Ambil detail order utama dari tabel orders
$query_order = "SELECT * FROM orders WHERE id = '$order_id'";
$result_order = mysqli_query($conn, $query_order);
$order = mysqli_fetch_assoc($result_order);

// 2. Ambil item-item yang termasuk dalam order tersebut dari order_details
$query_details = "SELECT od.*, p.nama_makanan FROM order_details od 
                  JOIN produk p ON od.produk_id = p.id 
                  WHERE od.order_id = '$order_id'";
$result_details = mysqli_query($conn, $query_details);
$details = mysqli_fetch_all($result_details, MYSQLI_ASSOC);

// Bersihkan last_order_id dari sesi setelah berhasil ditampilkan
unset($_SESSION['last_order_id']); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Order - Dapur Azizah</title>
    <link rel="stylesheet" href="layanan.css"> 
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/style.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .confirm-container { padding: 50px 10%; background-color: #f7f3e8; min-height: 80vh; text-align: center; }
        .success-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 0 auto 30px;
        }
        .success-box i { font-size: 4rem; color: #43a047; margin-bottom: 20px; }
        .success-box h1 { color: #43a047; margin-bottom: 10px; }
        .success-box p { font-size: 1.1rem; color: #555; }
        
        /* Detail Order */
        .order-details-summary { text-align: left; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
        .order-details-summary h3 { color: #c90000; }
        .order-details-summary table { width: 100%; margin-top: 15px; border-collapse: collapse; }
        .order-details-summary th, .order-details-summary td { padding: 8px 0; border-bottom: 1px dashed #eee; }
        .total-row { font-weight: bold; font-size: 1.2rem; }
    </style>
</head>
<body>
    <main class="confirm-container">

        <div class="success-box">
            <i class="fas fa-check-circle"></i>
            <h1>PESANAN BERHASIL DIBUAT!</h1>
            <p>Terima kasih, order Anda dengan nomor <strong>#<?php echo htmlspecialchars($order['id'] ?? 'N/A'); ?></strong> telah kami terima.</p>
            <p>Order Anda saat ini berstatus: <strong><?php echo htmlspecialchars($order['status_order'] ?? 'Proses'); ?></strong>.</p>

            <div class="order-details-summary">
                <h3>Detail Order Anda</h3>
                <p>Tanggal Order: <strong><?php echo htmlspecialchars($order['tanggal_order'] ?? 'N/A'); ?></strong></p>
                <p>Metode Pembayaran: <strong><?php echo htmlspecialchars($order['metode_pembayaran'] ?? 'N/A'); ?></strong></p>
                
                <table>
                    <thead>
                        <tr><th>Produk</th><th>Qty</th><th>Harga Satuan</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($details)): ?>
                        <?php foreach ($details as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nama_makanan']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td><?php echo format_rupiah($item['price_satuan']); ?></td>
                            <td><?php echo format_rupiah($item['subtotal']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="3">GRAND TOTAL:</td>
                            <td><?php echo format_rupiah($order['total_bayar'] ?? 0); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <p><a href="index.php">Kembali ke Beranda</a></p>
    </main>
</body>
</html>