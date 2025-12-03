<?php
session_start();
include 'koneksi.php'; // PASTIKAN FILE INI ADA DI LOKASI YANG BENAR
$query_terlaris = "
    SELECT 
        p.nama_makanan, 
        SUM(od.quantity) AS total_terjual 
    FROM 
        order_details od
    JOIN 
        produk p ON od.produk_id = p.id
    GROUP BY 
        p.nama_makanan 
    ORDER BY 
        total_terjual DESC 
    LIMIT 5
";

$result_terlaris = mysqli_query($conn, $query_terlaris);

// Cek error jika perlu
if (!$result_terlaris) {
    die("Query Terlaris Error: " . mysqli_error($conn));
}

// Memastikan pengguna sudah login (cek di awal agar tidak menjalankan query jika belum login)
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}
// 1. Hitung Total Pelanggan (dari tabel 'user')
$query_count_pelanggan = "SELECT COUNT(id) AS total FROM user";
$result_count_pelanggan = mysqli_query($conn, $query_count_pelanggan);

if ($result_count_pelanggan) {
    $data_pelanggan = mysqli_fetch_assoc($result_count_pelanggan);
    // Simpan nilai total ke variabel yang Anda gunakan di baris 127
    $total_pelanggan = $data_pelanggan['total']; 
} else {
    // Jika query gagal, atur nilai default agar tidak error
    $total_pelanggan = 0; 
}

// ------------------------------------------------
// LOGIKA NOTIFIKASI PESANAN BARU
// ------------------------------------------------
$count_notif = 0; // Default
$query_notif = "SELECT COUNT(id) AS total_notif FROM orders WHERE status_order = 'Menunggu Pembayaran'";
$result_notif = mysqli_query($conn, $query_notif);

if ($result_notif) {
    $notif = mysqli_fetch_assoc($result_notif);
    $count_notif = (int)($notif['total_notif'] ?? 0);
}

// ------------------------------------------------
// LOGIKA MENGHITUNG STATISTIK (UNTUK CARD)
// ------------------------------------------------

// Total Order
$query_total_order = "SELECT COUNT(id) AS total FROM orders";
$res_total_order = mysqli_query($conn, $query_total_order);
$total_order = mysqli_fetch_assoc($res_total_order)['total'] ?? 0;

// Total Produk (Asumsi tabel produk Anda bernama 'produk')
$query_total_produk = "SELECT COUNT(id) AS total FROM produk";
$res_total_produk = mysqli_query($conn, $query_total_produk);
$total_produk = mysqli_fetch_assoc($res_total_produk)['total'] ?? 0;

// Total Stok (placeholder, sesuaikan jika ada kolom stok)
$total_stock = $total_produk;

// Total Pelanggan (Asumsi tabel user Anda bernama 'user' atau 'pelanggan')
$query_total_customer = "SELECT COUNT(id) AS total FROM user";
$res_total_customer = mysqli_query($conn, $query_total_customer);
$total_customer = mysqli_fetch_assoc($res_total_customer)['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Dapur Azizah</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/global.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      <link rel="stylesheet" href="css/theme.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="img/yusuf.JPG" alt="Foto Admin" class="profile-img">
            <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
        </div>
        
        <nav class="nav-menu">
    <ul>
        <li><a href="admin_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="#"><i class="fas fa-box"></i> Inventory</a></li>
        
        <li>
            <a href="admin_orders.php" class="nav-order">
                <i class="fas fa-clipboard-list"></i> Order 
                <?php if ($count_notif > 0): ?>
                    <span class="badge badge-order-notif"><?php echo $count_notif; ?></span> 
                <?php endif; ?>
            </a>
        </li>
        <li><a href="manajemen_produk.php"><i class="fas fa-utensils"></i> Manajemen Produk</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>
    </div>

    <div class="main-content">
        <header class="top-bar">
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Cari data, produk, atau pesanan..." class="hidden-search">
    </div>

    <div class="top-icons">
       
        <a href="admin_orders.php" class="notif-icon-link">
            <i class="fas fa-bell"></i>
            <?php if ($count_notif > 0): ?>
                <span class="badge top-notif-badge"><?php echo $count_notif; ?></span> 
            <?php endif; ?>
        </a>
    </div>
</header>

        <div class="dashboard-area">
            <div class="stats-product-laris" style="margin-bottom: 30px; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h3 style="margin-bottom: 20px; color: #c90000; border-bottom: 2px solid #eee; padding-bottom: 10px;"><i class="fas fa-chart-bar"></i> Top 5 Produk Paling Laris</h3>
    
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f8f8f8;">
                    <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">No</th>
                    <th style="padding: 10px; text-align: left; border: 1px solid #ddd;">Nama Produk</th>
                    <th style="padding: 10px; text-align: center; border: 1px solid #ddd;">Total Terjual</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php if (mysqli_num_rows($result_terlaris) > 0): ?>
                    <?php while ($produk_laris = mysqli_fetch_assoc($result_terlaris)): ?>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;"><?php echo $rank++; ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($produk_laris['nama_makanan']); ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center; color: #28a745; font-weight: bold;"><?php echo htmlspecialchars($produk_laris['total_terjual']); ?> Pcs</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="padding: 15px; text-align: center; color: #777;">Belum ada data penjualan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
            <div class="stats-cards">
    <div class="card order">
        <div class="card-text">
            <h4><?php echo $total_order; ?></h4> <p>Order</p>
        </div>
        <div class="card-icon"><i class="fas fa-file-invoice" style="color:#d63384;"></i></div>
    </div>

    <div class="card stock">
        <div class="card-text">
            <h4><?php echo $total_stock; ?></h4> <p>Total Stock</p>
        </div>
        <div class="card-icon"><i class="fas fa-box-open" style="color:#ffc107;"></i></div>
    </div>

    <div class="card product">
        <div class="card-text">
            <h4><?php echo $total_produk; ?></h4> <p>Total Produk</p>
        </div>
        <div class="card-icon"><i class="fas fa-cubes" style="color:#28a745;"></i></div>
    </div>
</div>

<div class="bottom-row">
   <div class="card-pelanggan">
    <a href="manajemen_pelanggan.php"> 
        <div class="info-text">
            <h2><?php echo $total_pelanggan; ?></h2>
            <p>Total Pelanggan</p>
        </div>
    </a>
</div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchIcon = document.getElementById('searchIcon');
        const searchInput = document.getElementById('searchInput');

        // Pastikan elemen ada sebelum mendaftarkan event listener
        if (searchIcon && searchInput) {
            searchIcon.addEventListener('click', function() {
                // Toggle class 'active' pada input field
                searchInput.classList.toggle('active');

                // Jika aktif, fokuskan kursor ke input
                if (searchInput.classList.contains('active')) {
                    searchInput.focus();
                } else {
                    // Opsional: Hapus isi input saat ditutup
                    searchInput.value = '';
                }
            });
        }
    });
</script>
</body>
</html>