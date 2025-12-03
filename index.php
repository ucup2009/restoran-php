<?php
session_start();
// Cek status login
$is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

// Tentukan tautan dan teks navigasi
if ($is_logged_in) {
    // Jika sudah login, tombol header menjadi LOGOUT
    $nav_link_url = "user_logout.php"; 
    $nav_link_text = "Logout (" . htmlspecialchars($_SESSION['user_username'] ?? 'Pembeli') . ")"; 
} else {
    // Jika belum login, tombol header tetap LOG IN
    $nav_link_url = "#"; // Menggunakan '#' untuk memicu modal JavaScript
    $nav_link_text = "Log in";
}

// Catatan: Pastikan file 'login_user.php' sudah diubah agar sukses redirect ke 'index.php'.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Dapur Azizah</title>

    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navbar_footer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <style>
        .login-modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.7); display: flex; justify-content: center;
            align-items: center; z-index: 1000;
        }
        .login-modal-content {
            background: white; padding: 30px; border-radius: 10px;
            text-align: center; width: 300px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .modal-buttons a {
            display: block; margin: 10px 0; padding: 10px;
            text-decoration: none; color: white; border-radius: 5px; font-weight: bold;
        }
        .btn-admin { background-color: #343a40; }
        .btn-customer { background-color: #c90000; }
    </style>
</head>
<body>
    
    <div id="loginModal" class="login-modal-overlay" style="display: none;">
        <div class="login-modal-content">
            <div class="modal-header">
                <h2>login sebagai</h2>
                <img src="img/1.png" alt="Dapur Azizah Logo" style="width: 80px; margin-top: 10px;">
            </div>
            
            <div class="modal-buttons">
                <a href="admin_login.php" class="btn-role btn-admin">admin</a>
                <a href="user_login.php" class="btn-role btn-customer">pembeli</a>
            </div>
        </div>
    </div>
    
    <div class="website-container">
        <header class="main-header">
            <div class="logo">
                <img src="img/logo.png" alt="Dapur Azizah Logo">
            </div>
             <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="layanan.php" class="active">Layanan</a></li>
                    <li><a href="menu_dine_in.php">Menu Dine-In</a></li>
                    <li><a href="nasi_box.html">Nasi Box</a></li>
                    
                    <li><a href="cart.php" class="cart-icon" title="Keranjang Belanja"><i class="fas fa-shopping-bag"></i></a></li>
                    
                    <li><a href="<?php echo $nav_link_url; ?>" class="btn-login"><?php echo $nav_link_text; ?></a></li>
                </ul>
            </nav>
        </header>

        <section class="services-hero">
            <div class="service-content">
                <p>Kami dengan bangga menawarkan beragam layanan untuk memenuhi kebutuhan kuliner Anda. layanan pesta acara, pengiriman, dan layanan katering.</p>
            </div>
            <div class="service-images">
                <img src="img/produk/4b94680222487b212836a9e2e5e3a541 (1).jpg" alt="Layanan Pesta Acara">
                <img src="img/produk/2f1c464817c86b700fe116f04081aa9f.jpg" alt="Telur Balado">
            </div>
        </section>

        <section class="menu-overview">
            <div class="menu-text-box">
                <h1>Beragam hidangan untuk memuaskan berbagai selera</h1>
                <p>Kami bangga menyajikan hidangan-hidangan berkualitas. Setiap hidangan menggunakan bahan-bahan segar dan lokal yang terbaik. Menu kami meliputi berbagai masakan tradisional Indonesia, seperti Nasi Goreng, Sate, dan Gado-gado, serta makanan khas daerah jarang Anda temui seperti Ayam Tangkap dan Gurame Sari Honje.</p>
            </div>
            <div class="menu-image-grid">
                <div class="grid-item large-item">
                    <img src="img/produk/9482ab2e248d249e7daa7fd6924c8d3b.jpg" alt="Nasi Goreng">
                </div>
                <div class="grid-item small-item"><img src="img/produk/90b6a95fa0ab4bbb09869445257de771.jpg" alt="Gado-gado"  ></div>
                <div class="grid-item small-item"><img src="img/produk/a68ec592ddcfad79a8480821a5ab6320.jpg" alt="Sate"></div>
                <div class="grid-item small-item"><img src="img/produk/kwetiau.jpg" alt="Kwetiau"></div>
            </div>
        </section>

    </div>
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

    <script>
        // Status login dari PHP
        const IS_LOGGED_IN = <?php echo $is_logged_in ? 'true' : 'false'; ?>;

        document.addEventListener('DOMContentLoaded', () => {
            const loginLink = document.querySelector('.main-nav .btn-login'); 
            const modal = document.getElementById('loginModal');
            const overlay = document.querySelector('.login-modal-overlay');

            // Logika Klik Tombol Login/Logout
            if (loginLink && modal) {
                loginLink.addEventListener('click', (e) => {
                    // HANYA tampilkan modal jika user BELUM login
                    if (!IS_LOGGED_IN) {
                        e.preventDefault(); 
                        modal.style.display = 'flex';
                    }
                    // Jika user SUDAH login, link akan mengarah langsung ke user_logout.php
                });
            }

            // Logika Menghilangkan Modal
            if (overlay) {
                overlay.addEventListener('click', (e) => {
                    if (e.target.classList.contains('login-modal-overlay')) {
                        modal.style.display = 'none';
                    }
                });
            }
        });
    </script>
    
</body>
</html>