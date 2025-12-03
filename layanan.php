<?php
session_start();

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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Kami - Dapur Azizah</title>
    <link rel="stylesheet" href="css/layanan.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navbar_footer.css">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
<body>
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
        <section class="services-detail-section">
            <h1>Layanan Terbaik Kami Untuk Kebutuhan Kuliner Anda</h1>

            <div class="service-card pesan-antar">
                <div class="card-image">
                    <img src="img/ikon pengiriman.jpg" alt="Ikon Pengiriman">
                </div>
                <div class="card-text-box">
                    <h2>Pesan Antar & Take-Out</h2>
                    <p>Nikmati kelezatan makanan kami tanpa harus meninggalkan rumah atau kantor. Layanan pengiriman kami menyediakan hidangan favorit Anda langsung ke pintu Anda. Pesan sekarang melalui WhatsApp atau aplikasi favorit Anda.</p>
                    <a href="whatsapp_link" class="btn-primary">Pesan Sekarang</a>
                </div>
            </div>

            <div class="service-card katering">
                <div class="card-image">
                    <img src="img/catering-service-icon-vector.jpg" alt="Ikon Katering">
                </div>
                <div class="card-text-box">
                    <h2>Katering</h2>
                    <p>Kami menawarkan layanan katering yang menyediakan hidangan otentik yang disiapkan dengan keahlian oleh tim Kami baik untuk acara pribadi hingga acara korporat. Konsultasikan menu Anda bersama kami.</p>
                    <a href="kontak.html" class="btn-primary">Hubungi untuk Katering</a>
                </div>
            </div>

        </section>

        <footer class="main-footer">
            <div class="footer-content">
                <div class="footer-logo-box">
                    <img src="img/1.png" alt="Dapur Azizah">
                    </div>
                <div class="footer-info">
                    <h3>Jam operasional</h3>
                    <p>Senin-Sabtu: 09:00-17:00</p>
                    <h3>Alamat</h3>
                    <p>Jl. Lintas Gereja Katolik Ling.ngaji</p>
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
    </div>

    </body>
</html>