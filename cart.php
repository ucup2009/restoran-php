<?php
session_start();
include 'koneksi.php'; 

// Fungsi format harga
function format_rupiah($angka) {
    return 'Rp' . number_format($angka, 0, ',', '.');
}

// Inisialisasi variabel keranjang
$cart_items = $_SESSION['cart'] ?? [];
$grand_total = 0;

// Cek status login (contoh sederhana, asumsikan ada $_SESSION['user_id'] jika sudah login)
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Dapur Azizah</title>
    <link rel="stylesheet" href="layanan.css"> 
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .cart-container {
            padding: 50px 10%;
            background-color: #f7f3e8;
            min-height: 80vh;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        .cart-table th, .cart-table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .cart-table th {
            background-color: #c90000;
            color: white;
            font-weight: 500;
        }
        .cart-summary {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            max-width: 400px;
            margin-left: auto;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .summary-total {
            font-size: 1.5rem;
            font-weight: bold;
            color: #c90000;
            padding-top: 10px;
            border-top: 2px dashed #ddd;
            margin-top: 10px;
        }
        .btn-checkout {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #43a047; /* Hijau untuk Checkout */
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn-remove {
            color: #c90000;
            cursor: pointer;
            text-decoration: none;
        }
        /* Style untuk tombol Login/Logout di navbar */
        .btn-login, .btn-logout {
            padding: 8px 15px;
            border-radius: 5px;
            background-color: #c90000;
            color: white !important;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-logout {
            background-color: #ff3333; /* Warna berbeda untuk Logout */
        }
        .btn-login:hover {
            background-color: #a00000;
        }
        .btn-logout:hover {
            background-color: #cc0000;
        }
        /* Style untuk tombol quantity */
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-quantity {
            width: 30px;
            height: 30px;
            border: 1px solid #c90000;
            background-color: #c90000;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn-quantity:hover {
            background-color: #a00000;
        }
        .quantity-value {
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }
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
                <li><a href="menu_dine_in.php">Menu Dine-In</a></li>
                <li><a href="cart.php" class="active"><i class="fas fa-shopping-bag"></i> Keranjang</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="logout.php" class="btn-logout">Logout</a></li> 
                <?php else: ?>
                    <li><a href="login.php" class="btn-login">Log in</a></li> 
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="cart-container">
        <h1>Keranjang Belanja Anda</h1>

        <?php if (!empty($cart_items)): ?>
        
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $id => $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $grand_total += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo format_rupiah($item['price']); ?></td>
                    <td>
                        <div class="quantity-controls">
                            <a href="update_quantity.php?id=<?php echo $id; ?>&action=decrease" class="btn-quantity">-</a>
                            <span class="quantity-value"><?php echo htmlspecialchars($item['quantity']); ?></span>
                            <a href="update_quantity.php?id=<?php echo $id; ?>&action=increase" class="btn-quantity">+</a>
                        </div>
                    </td>
                    <td><?php echo format_rupiah($subtotal); ?></td>
                    <td><a href="remove_from_cart.php?id=<?php echo $id; ?>" class="btn-remove"><i class="fas fa-trash"></i></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <h2>Ringkasan Pesanan</h2>
            <div class="summary-row summary-total">
                <span>Total Belanja:</span>
                <span><?php echo format_rupiah($grand_total); ?></span>
            </div>
            <a href="checkout.php" class="btn-checkout">Lanjutkan ke Pembayaran (Checkout)</a>
        </div>

        <?php else: ?>
            <p style="text-align: center; margin-top: 50px;">Keranjang belanja Anda kosong. <a href="menu_dine_in.php">Yuk, mulai pesan!</a></p>
        <?php endif; ?>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
</body>
</html>