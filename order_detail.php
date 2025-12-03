<?php
session_start();
include 'koneksi.php'; 

if (!isset($_SESSION['username']) || !isset($_GET['id'])) {
    header("Location: admin_login.php");
    exit();
}

$order_id = (int)$_GET['id'];

// 1. Ambil Detail Pesanan Utama
$query_order_detail = "SELECT * FROM orders WHERE id = $order_id";
$result_order_detail = mysqli_query($conn, $query_order_detail);
$order = mysqli_fetch_assoc($result_order_detail);

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

// 2. Ambil Detail Pelanggan (Nama Pemesan dan Alamat) dari tabel 'user'
$customer_data = ['nama_lengkap' => 'N/A', 'alamat' => 'N/A']; // Nilai default

$customer_id = $order['user_id'] ?? 0; // Asumsi kolom penghubung adalah 'user_id'

if ($customer_id > 0) {
    // Ambil nama dan alamat user. Asumsi kolom di tabel 'user' adalah 'nama_lengkap' dan 'alamat'.
    $query_customer = "SELECT nama_lengkap, alamat FROM user WHERE id = $customer_id";
    $result_customer = mysqli_query($conn, $query_customer);
    
    if ($result_customer && $data = mysqli_fetch_assoc($result_customer)) {
        $customer_data = $data;
    }
}

// 3. Ambil Detail Item Pesanan (PERBAIKAN KRITIS: Menggunakan nama_makanan dan order_details)
$result_items = null; // Nilai default aman

// ******* PERBAIKAN FATAL ERROR: Ganti nama kolom 'p.nama' menjadi 'p.nama_makanan' *******
// ******* PERBAIKAN TABEL: Ganti 'order_items' menjadi 'order_details' (sesuai struktur umum) *******
$query_items = "SELECT od.*, p.nama_makanan AS nama_produk, od.price_satuan AS price 
                FROM order_details od 
                JOIN produk p ON od.produk_id = p.id
                WHERE od.order_id = $order_id";

$result_items = mysqli_query($conn, $query_items);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
    @media print {
        /* Sembunyikan tombol kembali dan tombol cetak saat mencetak */
        .kembali-link,
        .btn-print {
            display: none !important;
        }
        
        /* Hilangkan margin dan padding utama untuk nota bersih */
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Atur margin halaman cetak */
        @page {
            margin: 1cm;
        }
        
        /* Pastikan elemen cetak tidak terpotong */
        h2 {
            page-break-after: avoid;
        }
        .orders-table {
            page-break-inside: avoid;
        }
    }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="dashboard-area" style="padding: 20px;">
            <a href="admin_orders.php" class="kembali-link" style="text-decoration: none; color: #c90000; font-weight: bold;">← Kembali ke Daftar Pesanan</a>
            
            <h2 style="margin-top: 15px;">Detail Pesanan #<?php echo $order['id']; ?></h2>
            
            <div style="margin-top: 20px; text-align: right; margin-bottom: 20px;">
                <button onclick="window.print()" class="btn-print" 
                        style="background-color: #28a745; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px;">
                    <i class="fas fa-print"></i> Cetak Nota
                </button>
            </div>
            
            <div style="background-color: white; padding: 20px; margin-top: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h3>Informasi Pesanan Utama</h3>
                
                <p><strong>Tanggal:</strong> <?php echo date('d M Y H:i', strtotime($order['tanggal_order'] ?? 'N/A')); ?></p>
                <p><strong>Total Bayar:</strong> Rp <?php echo number_format($order['total_bayar'] ?? 0, 0, ',', '.'); ?></p>
                <p><strong>Status:</strong> <span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order['status_order'] ?? 'N/A')); ?>"><?php echo htmlspecialchars($order['status_order'] ?? 'N/A'); ?></span></p>
            </div>

            <div style="background-color: white; padding: 20px; margin-top: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h3>Informasi Pelanggan</h3>
                <p><strong>Nama Pemesan:</strong> <?php echo htmlspecialchars($customer_data['nama_lengkap'] ?? 'N/A'); ?></p>
                <p><strong>Alamat:</strong> <?php echo htmlspecialchars($customer_data['alamat'] ?? 'N/A'); ?></p>
            </div>
            
            <h3 style="margin-top: 30px;">Item Pesanan</h3>
            <table class="orders-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Perluas pengecekan untuk menangani kasus $result_items bernilai null (jika query gagal)
                    if ($result_items && mysqli_num_rows($result_items) > 0): 
                    ?>
                        <?php while ($item = mysqli_fetch_assoc($result_items)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>Rp <?php echo number_format($item['price'] ?? 0, 0, ',', '.'); ?></td>
                                <td>Rp <?php echo number_format(($item['quantity'] * $item['price']) ?? 0, 0, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center; font-style: italic; padding: 15px;">Detail item pesanan tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        </div>
    </div>
</body>
</html>