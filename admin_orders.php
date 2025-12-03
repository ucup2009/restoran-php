<?php
session_start();
include 'koneksi.php'; // Pastikan koneksi.php tersedia

// Memastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
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
// LOGIKA PENCARIAN PESANAN
// ------------------------------------------------
$search = '';
$where_clause = '';

// Cek apakah ada input pencarian dari URL
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Tentukan klausa WHERE untuk mencari berdasarkan ID Pesanan atau status
    $where_clause = " WHERE id LIKE '%$search%' OR status_order LIKE '%$search%'";
}

// Query untuk mengambil data pesanan (dengan klausa pencarian jika ada)
$query_orders = "SELECT * FROM orders" . $where_clause . " ORDER BY tanggal_order DESC";

$result_orders = mysqli_query($conn, $query_orders);
$total_orders = mysqli_num_rows($result_orders); // Variabel yang hilang kini terdefinisi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Dapur Azizah</title>
    <link rel="stylesheet" href="css/dashboard.css"> 
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/global.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    </head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="img/yusuf.JPG" alt="Foto Admin" class="profile-img">
            <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
        </div>
        
        <nav class="nav-menu">
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-box"></i> Inventory</a></li>
                
                <li>
                    <a href="admin_orders.php" class="active nav-order">
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
                <input type="text" id="searchInput" placeholder="Cari pesanan..." class="hidden-search">
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

        <div class="dashboard-area" style="padding: 20px;">
            <h2>Daftar Pesanan (<?php echo $total_orders; ?>)</h2>
            
            <div style="margin-bottom: 20px;">
                <form action="admin_orders.php" method="GET" style="display:inline;">
                    <input type="text" name="search" placeholder="Cari ID/Status Pesanan..." 
                        value="<?php echo htmlspecialchars($search); ?>" 
                        style="border: 1px solid #ccc; padding: 8px; border-radius: 4px; width: 300px;">
                
                    <button type="submit" style="background-color: #f1f1f1; border: 1px solid #ccc; padding: 8px 12px; cursor: pointer; border-radius: 4px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="admin_orders.php" style="margin-left: 10px; color: #c90000; text-decoration: none;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="orders-table-container">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total (Rp)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result_orders) > 0): ?>
                            <?php while ($order = mysqli_fetch_assoc($result_orders)): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($order['tanggal_order'])); ?></td>
                                    <td><?php echo number_format($order['total_bayar'], 0, ',', '.'); ?></td>
                                    <td><span class="status-badge status-<?php echo strtolower(str_replace(' ', '-', $order['status_order'])); ?>"><?php echo htmlspecialchars($order['status_order']); ?></span></td>
                                    <td>
                                        <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn-detail">Lihat Detail</a>
                                        </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px;">
                                    <?php echo !empty($search) ? "Tidak ada pesanan ditemukan untuk pencarian '{$search}'." : "Belum ada pesanan yang masuk."; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>