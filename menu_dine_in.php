<?php
session_start();
include 'koneksi.php'; 

// Cek status login
$is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

// Tentukan tautan dan teks navigasi
if ($is_logged_in) {
    $nav_link_url = "user_logout.php"; 
    $nav_link_text = "Logout (" . htmlspecialchars($_SESSION['user_username'] ?? 'Pembeli') . ")"; 
} else {
    $nav_link_url = "user_login.php"; 
    $nav_link_text = "Log in";
}
// Jika foto kosong, null, atau placeholder, gunakan logo
// Fungsi untuk menampilkan foto produk pada menu
function get_foto_produk($foto) {
    // Jika belum diupload/gambar kosong, null, atau placeholder, pakai logo default
    if (empty($foto) || $foto === "placeholder_foto.jpg" || !file_exists(__DIR__ . "/img/produk/" . $foto)) {
        return "img/logo-dapurazizah.png";
    } else {
        return "img/produk/" . htmlspecialchars($foto);
    }
}

// Perbaikan: $foto tidak didefinisikan di sini (harusnya hanya saat menampilkan produk, bukan di bagian atas file ini)
// Hapus seluruh blok if (empty($foto) ... ) karena variabel $foto belum ada; proses tampil foto produk dilakukan per produk pada saat looping.



// Fungsi format harga
function format_rupiah($angka) {
    return 'Rp' . number_format($angka, 0, ',', '.');
}

// Query dan pengelompokan produk
$query_produk = "SELECT id, nama_makanan, harga_makanan, foto, kategori FROM produk ORDER BY kategori, nama_makanan";
$result_produk = mysqli_query($conn, $query_produk);
$produk_by_kategori = [];
if ($result_produk && mysqli_num_rows($result_produk) > 0) {
    while ($row = mysqli_fetch_assoc($result_produk)) {
        $produk_by_kategori[$row['kategori']][] = $row;
    }
}

// Helper untuk menampilkan flash message (mendukung string atau array)
function show_flash() {
    if (!empty($_SESSION['flash_message'])) {
        $fm = $_SESSION['flash_message'];
        if (is_array($fm) && isset($fm['message'])) {
            $type = htmlspecialchars($fm['type'] ?? 'info');
            echo '<div class="flash ' . $type . '" style="padding:12px; margin:10px auto; max-width:1100px; text-align:center; background:#fff8c4; border:1px solid #f0e6a6;">' . htmlspecialchars($fm['message']) . '</div>';
        } else {
            echo '<div class="flash" style="padding:12px; margin:10px auto; max-width:1100px; text-align:center; background:#e7f7e7; border:1px solid #cfe8cf;">' . htmlspecialchars((string)$fm) . '</div>';
        }
        unset($_SESSION['flash_message']);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Dine-In - Dapur Aziza</title>
    <link rel="stylesheet" href="css/menu_dine_in.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navbar_footer.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* CSS khusus menu tetap sama */
        .flash.success { background: #e6ffed; border-color: #bfe8c9; }
        .flash.error { background: #ffe6e6; border-color: #f1c0c0; color: #6a1b1b; }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <img src="img/logo.png" alt="Dapur Azizah Logo">
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="layanan.php">Layanan</a></li>
                <li><a href="menu_dine_in.php" class="active">Menu Dine-In</a></li>
                <li><a href="nasi_box.html">Nasi Box</a></li>
                
                <li><a href="cart.php" class="cart-icon" title="Keranjang Belanja"><i class="fas fa-shopping-bag"></i></a></li>

                <li><a href="<?php echo $nav_link_url; ?>" class="btn-login"><?php echo $nav_link_text; ?></a></li> 
            </ul>
        </nav>
    </header>
    
    <?php show_flash(); ?>

    <main class="menu-content">
        <?php if (!empty($produk_by_kategori)): ?>
            <?php foreach ($produk_by_kategori as $kategori => $products): ?>
                
                <h2><?php echo htmlspecialchars($kategori); ?></h2>
                
                <section class="menu-grid">
                    <?php foreach ($products as $produk): ?>
                        <div class="product-card">
                            <img src="img/produk/<?php echo htmlspecialchars($produk['foto']); ?>" alt="<?php echo htmlspecialchars($produk['nama_makanan']); ?>">
                            
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($produk['nama_makanan']); ?></h3>
                            </div>
                            
                            <div class="product-actions">
                                <a href="add_to_cart.php?id=<?php echo $produk['id']; ?>" class="btn-pesan">Pesan</a>
                                
                                <span class="product-price"><?php echo format_rupiah($produk['harga_makanan']); ?></span>
                                
                                <a href="add_to_cart.php?id=<?php echo $produk['id']; ?>" class="cart-icon-product" title="Tambah ke keranjang">
                                    <i class="fas fa-shopping-bag"></i> 
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; margin-top: 50px; font-size: 1.2rem;">Maaf, menu belum tersedia saat ini.</p>
        <?php endif; ?>
    </main>
     <footer class="main-footer">
            <div class="footer-content">
                <div class="footer-logo-box">
                    <img src="img/1.png" alt="Dapur Azizah">
                    </div>
                <div class="footer-info">
                    <h3>Jam operasional</h3>
                    <p>Senin-Sabtu: 09:00-17:00</p>
                    <h3>Alamat</h3>
                    <p>Jl. Ende-Bajawa km. 21</p>
                </div>
                <div class="footer-contact">
                    <h3>Hubungi Kami</h3>
                    <p>081337470942</p>
                    <p>08765432134</p>
                    <p>dapur421@gmailo.com</p>
                    <button class="btn-contact">Hubungi Kami</button>
                </div>
            </div>
        </footer>
</body>
        </html>