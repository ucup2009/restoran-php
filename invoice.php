<?php
session_start();
include 'koneksi.php';

// Pastikan ada ID pesanan yang dikirimkan melalui URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Jika ID tidak valid, arahkan kembali ke menu atau halaman lain
    header("Location: index.php");
    exit();
}

$order_id = mysqli_real_escape_string($conn, $_GET['id']);

// 1. Ambil Data Pesanan Induk (Tabel 'orders')
$query_order = "SELECT * FROM orders WHERE id = '$order_id'";
$result_order = mysqli_query($conn, $query_order);

if (mysqli_num_rows($result_order) == 0) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}
$order = mysqli_fetch_assoc($result_order);

// 2. Ambil Data Detail Pesanan (Tabel 'order_details')
$query_details = "
    SELECT od.*, p.nama_makanan, p.harga_makanan 
    FROM order_details od
    JOIN produk p ON od.produk_id = p.id
    WHERE od.order_id = '$order_id'";

$result_details = mysqli_query($conn, $query_details);

// Format Tanggal dan Waktu
$tanggal = date('d-m-Y H:i:s', strtotime($order['tanggal_pesan']));

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pesanan #<?php echo htmlspecialchars($order_id); ?></title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="css/global.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
      <link rel="stylesheet" href="css/theme.css">
    <style>
        
        .invoice-container { max-width: 800px; margin: 50px auto; padding: 30px; border: 1px solid #ddd; background-color: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .invoice-header { text-align: center; margin-bottom: 20px; }
        .detail-group { margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .detail-group p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .grand-total { font-size: 1.2em; font-weight: bold; text-align: right; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h2>INVOICE PESANAN</h2>
            <h1>#<?php echo htmlspecialchars($order_id); ?></h1>
        </div>

        <div class="detail-group">
            <h3>📝 Data Pemesan</h3>
            <p><strong>Penerima:</strong> <?php echo htmlspecialchars($order['nama_penerima']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
            <p><strong>Telepon:</strong> <?php echo htmlspecialchars($order['nomor_telepon']); ?></p>
            <p><strong>Alamat:</strong> <?php echo htmlspecialchars($order['alamat']); ?></p>
        </div>

        <div class="detail-group">
            <h3>💳 Detail Pembayaran</h3>
            <p><strong>Tanggal Pesan:</strong> <?php echo $tanggal; ?></p>
            <p><strong>Metode Pembayaran:</strong> <?php echo htmlspecialchars($order['metode_pembayaran']); ?></p>
            <p><strong>Status:</strong> Menunggu Pembayaran</p>
        </div>

        <h3>รายการ Produk</h3>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $grand_total = 0; ?>
                <?php while ($detail = mysqli_fetch_assoc($result_details)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($detail['nama_makanan']); ?></td>
                    <td>Rp<?php echo number_format($detail['harga_satuan'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($detail['jumlah']); ?></td>
                    <td>Rp<?php echo number_format($detail['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php 
                    $grand_total += $detail['subtotal'];
                    // Perlu diperhatikan: jika kolom di tabel order Anda bernama 'grand_total', 
                    // Anda bisa langsung pakai $order['grand_total'] tanpa hitung ulang.
                ?>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="grand-total">
            Total Akhir: Rp<?php 
                // Gunakan kolom Grand Total dari tabel order jika ada (misal: $order['grand_total'])
                // Jika tidak ada, gunakan hasil hitungan di loop ($grand_total)
                echo number_format($order['grand_total'] ?? $grand_total, 0, ',', '.'); 
            ?>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <p>Terima kasih atas pesanan Anda di Dapur Azizah.</p>
            <a href="menu_dine_in.php">Kembali ke Menu</a>
        </div>
    </div>
</body>
</html>