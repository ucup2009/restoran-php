<?php
session_start();
include 'koneksi.php'; 

// Cek apakah keranjang kosong
if (empty($_SESSION['cart'])) {
    $_SESSION['flash_message'] = "Keranjang Anda kosong, tidak dapat melanjutkan ke Checkout.";
    header("Location: menu_dine_in.php");
    exit();
}

// KRITIS: Cek user login (agar kita tahu siapa yang memesan)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_message'] = "Anda harus login untuk menyelesaikan pemesanan.";
    header("Location: user_login.php"); 
    exit();
}

// Ambil data user yang sedang login untuk mengisi form
$user_id = $_SESSION['user_id'];
$query_user = "SELECT * FROM user WHERE id = '$user_id' LIMIT 1";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);

// Fungsi format harga
function format_rupiah($angka) {
    return 'Rp' . number_format($angka, 0, ',', '.');
}

$cart_items = $_SESSION['cart'];
$grand_total = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Dapur Azizah</title>
    <link rel="stylesheet" href="layanan.css"> 
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Gaya dasar sama dengan cart.php */
        .checkout-container { padding: 50px 10%; background-color: #f7f3e8; min-height: 80vh; display: flex; gap: 40px; }
        .checkout-left, .checkout-right { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); }
        .checkout-left { flex: 2; } /* Form Kontak */
        .checkout-right { flex: 1; } /* Ringkasan Order */

        /* Form Styling */
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { 
            width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px; 
        }
        /* Tambahan Styling untuk Tombol Kembali */
        .checkout-actions {
            display: flex;
            justify-content: space-between; /* Pisahkan kedua tombol */
            gap: 15px;
            margin-bottom: 20px; 
            margin-top: -5px; /* Sedikit naikkan agar dekat form */
        }
        
        .btn-secondary {
            flex-grow: 1; /* Agar kedua tombol memiliki lebar yang sama */
            padding: 10px;
            background-color: #f0f0f0; /* Warna abu-abu terang */
            color: #333;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 0.95rem;
            transition: background-color 0.2s;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
        }

        /* Order Summary Styling */
        .order-summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .order-summary-table td { padding: 8px 0; border-bottom: 1px dashed #eee; }
        .order-summary-table .total { font-weight: bold; font-size: 1.2rem; color: #c90000; }
        
        .btn-pay {
            display: block;
            width
            padding: 15px;
            background-color: #c90000;
            color: white;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
        }
        .btn-pay:hover { background-color: #a00000; }
    </style>
</head>
<body>
    <main class="checkout-container">

        <div class="checkout-left">
            <h2><i class="fas fa-user-circle"></i> Data Pemesanan</h2>
            
            <form action="process_checkout.php" method="POST">
                
                <h3>Detail Kontak</h3>
                <div class="form-group">
                    <label for="nama_penerima">Nama Penerima</label>
                    <input type="text" id="nama_penerima" name="nama_penerima" 
                        value="<?php echo htmlspecialchars($user_data['nama'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email_penerima">Email</label>
                    <input type="email" id="email_penerima" name="email_penerima" 
                        value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input type="tel" id="telepon" name="telepon" 
                        value="<?php echo htmlspecialchars($user_data['telepon'] ?? ''); ?>" required>
                </div>

                <h3>Alamat Pengambilan / Pengiriman</h3>
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap Anda atau tulis 'Dine-In'" required>
                        <?php echo htmlspecialchars($user_data['alamat'] ?? ''); ?>
                    </textarea>
                </div>
                
                <h3>Metode Pembayaran</h3>
                <div class="form-group">
                    <select name="metode_pembayaran" required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="CASH">Bayar di Tempat (CASH)</option>
                        <option value="TRANSFER">Transfer Bank (BCA/Mandiri)</option>
                    </select>
                </div>
                <div class="checkout-actions">
                    <a href="cart.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Keranjang</a>
                    <a href="menu_dine_in.php" class="btn-secondary"><i class="fas fa-store"></i> Lanjutkan Belanja</a>
                </div>

                <button type="submit" class="btn-pay"><i class="fas fa-credit-card"></i> Bayar Sekarang</button>
            </form>
        </div>

                
            </form>
        </div>
        

        <div class="checkout-right">
            <h2><i class="fas fa-clipboard-list"></i> Ringkasan Order</h2>
            
            <table class="order-summary-table">
                <?php foreach ($cart_items as $id => $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $grand_total += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</td>
                    <td style="text-align: right;"><?php echo format_rupiah($subtotal); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2"><hr style="border: 0; border-top: 1px dashed #ddd;"></td>
                </tr>
                <tr>
                    <td class="total">Grand Total</td>
                    <td class="total" style="text-align: right;"><?php echo format_rupiah($grand_total); ?></td>
                </tr>
            </table>
            
            <p style="margin-top: 40px; font-size: 0.9rem; color: #555;">
                Dengan mengklik "Bayar Sekarang", Anda menyetujui syarat dan ketentuan pemesanan Dapur Azizah.
            </p>
        </div>
    </main>
    </body>
</html>