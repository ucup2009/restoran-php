<?php
session_start();
include 'koneksi.php'; 


// Periksa keamanan: jika belum login, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// Hitung notifikasi (supaya badge tersedia)
$count_notif = 0;
$query_notif = "SELECT COUNT(id) AS total_notif FROM orders WHERE status_order = 'Menunggu Pembayaran'";
$result_notif = mysqli_query($conn, $query_notif);
if ($result_notif) {
    $notif = mysqli_fetch_assoc($result_notif);
    $count_notif = (int)($notif['total_notif'] ?? 0);
}

// ----------------------------------------------------
// LOGIKA PENCARIAN PRODUK
// ----------------------------------------------------
$search = '';
$where_clause = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Mencari berdasarkan ID Produk atau Nama Makanan
    $where_clause = " WHERE id LIKE '%$search%' OR nama_makanan LIKE '%$search%'";
}

// 1. Ambil data produk dari database (DENGAN KLAUSA WHERE PENCARIAN)
$query = "SELECT id, nama_makanan, harga_makanan, foto, kategori FROM produk" . $where_clause . " ORDER BY id ASC";
$result = mysqli_query($conn, $query); 
$total_produk = mysqli_num_rows($result); // Hitung total produk setelah filter
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Dapur Azizah</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/manajemen_produk.css"> 
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Gaya tambahan untuk gambar di tabel agar rapi */
        .product-img-thumb {
            width: 80px; /* Ukuran thumbnail */
            height: 80px;
            object-fit: cover; /* Memastikan gambar tidak terdistorsi */
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="profile-area">
            <img src="img/yusuf.jpg" alt="Foto Admin" class="profile-img">
            <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
        </div>
        
        <nav class="nav-menu">
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-box"></i> Inventory</a></li>
                <li><a href="admin_orders.php"><i class="fas fa-clipboard-list"></i> Order</a></li>
                <li><a href="manajemen_produk.php" class="active"><i class="fas fa-utensils"></i> Manajemen Produk</a></li>
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
            
            <h2 style="margin-bottom: 15px;">Manajemen Produk (<?php echo $total_produk; ?>)</h2> 

            <div style="margin-bottom: 20px;">
                <form action="manajemen_produk.php" method="GET" style="display:inline;">
                    <input type="text" name="search" placeholder="Cari ID/Nama Makanan..." 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           style="border: 1px solid #ccc; padding: 8px; border-radius: 4px; width: 300px;">
                
                    <button type="submit" style="background-color: #f1f1f1; border: 1px solid #ccc; padding: 8px 12px; cursor: pointer; border-radius: 4px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    
                    <?php if (!empty($search)): ?>
                        <a href="manajemen_produk.php" style="margin-left: 10px; color: #c90000; text-decoration: none;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="action-buttons">
                <a href="tambah_produk.php" class="btn-action btn-tambah">Tambah</a>
                
            </div>

            <div class="product-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Makanan</th>
                            <th>Harga Makanan</th>
                            <th>Foto</th> 
                            <th>Kategori</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)): 
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama_makanan']); ?></td>
                                <td><?php echo "Rp." . number_format($row['harga_makanan'], 0, ',', '.'); ?></td>
                                
                                <!-- PERBAIKAN: Mengganti teks dengan tag IMG -->
                                <td>
                                    <img src="<?php echo htmlspecialchars($row['foto']); ?>" 
                                         alt="<?php echo htmlspecialchars($row['nama_makanan']); ?>" 
                                         class="product-img-thumb"
                                         onerror="this.onerror=null; this.src='img/placeholder.png';"> <!-- Fallback jika gambar rusak/hilang -->
                                </td>
                                
                                <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                <td class="action-icons">
                             
                                    <a href="edit_produk.php?id=<?php echo $row['id']; ?>">
                                        <i class="fas fa-edit edit-icon"></i>
                                    </a>
                                    
                                    <!-- Hapus fungsi confirm() yang tidak disarankan, ganti dengan komentar petunjuk -->
                                    <a href="hapus_produk.php?id=<?php echo $row['id']; ?>" 
                                       title="Hapus <?php echo htmlspecialchars($row['nama_makanan']); ?> (Tindakan ini memerlukan konfirmasi UI)">
                                        <i class="fas fa-trash-alt delete-icon"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        } else {
                            echo '<tr><td colspan="5" class="no-data">Tidak ada produk ditemukan.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchIcon = document.getElementById('searchIcon');
        const searchInput = document.getElementById('searchInput');

        if (searchIcon && searchInput) {
            searchIcon.addEventListener('click', function() {
                // Toggle class 'active' pada input field
                searchInput.classList.toggle('active');

                if (searchInput.classList.contains('active')) {
                    searchInput.focus();
                } else {
                    searchInput.value = '';
                }
            });
            
            // Tambahkan event listener untuk submit form ketika menekan Enter pada input.
            // Ini akan memastikan ikon search di top-bar tidak menimpa form pencarian utama.
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    // Logika pencarian utama sudah ditangani oleh form di dashboard-area.
                    // Tambahkan pengiriman form jika Anda ingin ini berfungsi sebagai pencarian sekunder
                }
            });
        }
    });
</script>
</body>
</html>