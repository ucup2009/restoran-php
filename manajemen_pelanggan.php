<?php
session_start();
include 'koneksi.php'; 

// Periksa keamanan Admin
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// ------------------------------------------------
// LOGIKA PENCARIAN PELANGGAN (Sudah ada)
// ------------------------------------------------
$search = '';
$where_clause = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Mencari berdasarkan Nama Lengkap, Email, atau Username
    $where_clause = " WHERE nama_lengkap LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%'";
}

// Query untuk mengambil semua data user/pelanggan (Disesuaikan dengan pencarian)
$query_pelanggan = "SELECT * FROM user" . $where_clause . " ORDER BY id DESC";
$result_pelanggan = mysqli_query($conn, $query_pelanggan);

// Hitung total pelanggan
$total_pelanggan = mysqli_num_rows($result_pelanggan ?? 0); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pelanggan - Dapur Azizah</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/manajemen_produk.css"> 
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="img/yusuf.jpg" alt="Foto Admin" class="profile-img">
            <h3><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></h3>
        </div>
        <nav class="nav-menu">
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-box"></i> Inventory</a></li>
                <li><a href="admin_orders.php"><i class="fas fa-clipboard-list"></i> Order</a></li>
                <li><a href="manajemen_produk.php"><i class="fas fa-utensils"></i> Manajemen Produk</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <header class="top-bar">...</header>

        <div class="dashboard-area">
            <h2>Manajemen Data Pelanggan (Total: <?php echo $total_pelanggan; ?>)</h2>
            
            <div style="margin-bottom: 20px;">
                <form action="manajemen_pelanggan.php" method="GET" style="display:inline;">
                    <input type="text" name="search" placeholder="Cari Nama/Email/Username..." 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           style="border: 1px solid #ccc; padding: 8px; border-radius: 4px; width: 300px;">
                
                    <button type="submit" style="background-color: #f1f1f1; border: 1px solid #ccc; padding: 8px 12px; cursor: pointer; border-radius: 4px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    
                    <?php if (!empty($search)): ?>
                        <a href="manajemen_pelanggan.php" style="margin-left: 10px; color: #c90000; text-decoration: none;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Username</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php 
                        // Periksa apakah query berhasil dieksekusi sebelum loop
                        if ($result_pelanggan && mysqli_num_rows($result_pelanggan) > 0): 
                            while ($pelanggan = mysqli_fetch_assoc($result_pelanggan)): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($pelanggan['nama_lengkap'] ?? 'N/A'); ?></td> 
                            <td><?php echo htmlspecialchars($pelanggan['email'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($pelanggan['alamat'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($pelanggan['username'] ?? 'N/A'); ?></td>
                        
                            <td>
                                <a href="hapus_pelanggan.php?id=<?php echo $pelanggan['id']; ?>" 
                                    class="btn-action btn-hapus" 
                                    onclick="return confirm('Yakin ingin menghapus pelanggan <?php echo htmlspecialchars($pelanggan['nama_lengkap']); ?>?')" 
                                    title="Hapus Pelanggan">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                            echo '<tr><td colspan="6" style="text-align: center; padding: 15px;">Belum ada data pelanggan atau terjadi kesalahan saat memuat data.</td></tr>';
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>