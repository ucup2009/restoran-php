<?php
session_start();
include 'koneksi.php';

// Cek keamanan
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// 1. Ambil ID Produk dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Jika ID tidak valid, redirect kembali
    header("Location: manajemen_produk.php");
    exit();
}
$id_produk = mysqli_real_escape_string($conn, $_GET['id']);

// 2. LOGIKA UPDATE DATA (saat form disubmit)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_makanan = mysqli_real_escape_string($conn, $_POST['nama_makanan']);
    $harga_makanan = mysqli_real_escape_string($conn, $_POST['harga_makanan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
// Ambil foto lama
$foto_lama = $produk['foto'];
$foto = $foto_lama; // Default: gunakan foto lama

// Proses upload foto baru jika ada
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $file = $_FILES['foto'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    
    // Cek ekstensi file yang diizinkan
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if (in_array($file_ext, $allowed_extensions)) {
        // Cek ukuran file (max 5MB)
        if ($file_size <= 5242880) { // 5MB dalam bytes
            // Buat nama file unik dengan timestamp
            $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
            $upload_dir = 'img/produk/';
            
            // Pastikan folder upload ada
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $upload_path = $upload_dir . $new_file_name;
            
            // Pindahkan file ke folder upload
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Hapus foto lama jika bukan placeholder
                if ($foto_lama != 'placeholder_foto.jpg' && file_exists($upload_dir . $foto_lama)) {
                    unlink($upload_dir . $foto_lama);
                }
                $foto = $new_file_name;
            } else {
                echo "<script>alert('Gagal mengupload foto baru! Foto lama tetap digunakan.');</script>";
            }
        } else {
            echo "<script>alert('Ukuran file terlalu besar! Maksimal 5MB. Foto lama tetap digunakan.');</script>";
        }
    } else {
        echo "<script>alert('Format file tidak didukung! Foto lama tetap digunakan.');</script>";
    }
}

    // Query UPDATE
    $update_query = "UPDATE produk SET 
                        nama_makanan = '$nama_makanan', 
                        harga_makanan = '$harga_makanan', 
                        kategori = '$kategori' 
                     WHERE id = '$id_produk'";
    
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Produk berhasil diperbarui!'); window.location='manajemen_produk.php';</script>";
    } else {
        echo "<script>alert('Error saat memperbarui produk: " . mysqli_error($conn) . "');</script>";
    }
}

// 3. Ambil data produk lama untuk diisi ke form
$data_query = "SELECT * FROM produk WHERE id = '$id_produk'";
$data_result = mysqli_query($conn, $data_query);
$produk = mysqli_fetch_assoc($data_result);

// Jika produk tidak ditemukan, kembali ke halaman manajemen
if (!$produk) {
    header("Location: manajemen_produk.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Dapur Azizah</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="manajemen_produk.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        </div>

    <div class="main-content">
        <header class="top-bar">
             </header>

        <div class="dashboard-area">
            <h2>Edit Produk: <?php echo htmlspecialchars($produk['nama_makanan']); ?></h2>
            <div class="form-container">
                <form action="edit_produk.php?id=<?php echo $produk['id']; ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label for="nama_makanan">Nama Makanan:</label>
                        <input type="text" id="nama_makanan" name="nama_makanan" value="<?php echo htmlspecialchars($produk['nama_makanan']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="harga_makanan">Harga Makanan (contoh: 50000):</label>
                        <input type="number" id="harga_makanan" name="harga_makanan" value="<?php echo htmlspecialchars($produk['harga_makanan']); ?>" required min="1000">
                    </div>
                    
                    <div class="form-group">
                        <label for="kategori">Kategori:</label>
                        <select id="kategori" name="kategori" required>
                            <option value="Makanan pembuka" <?php if ($produk['kategori'] == 'Makanan pembuka') echo 'selected'; ?>>Makanan pembuka</option>
                            <option value="Ayam dan Bebek" <?php if ($produk['kategori'] == 'Ayam dan Bebek') echo 'selected'; ?>>Ayam dan Bebek</option>
                            <option value="Menu Utama" <?php if ($produk['kategori'] == 'Menu Utama') echo 'selected'; ?>>Menu Utama</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Produk (kosongkan jika tidak ingin mengubah):</label>
                        <?php if (!empty($produk['foto']) && $produk['foto'] != 'placeholder_foto.jpg'): ?>
                            <p style="margin-bottom: 10px;">
                                <strong>Foto saat ini:</strong><br>
                                <img src="img/produk/<?php echo htmlspecialchars($produk['foto']); ?>" 
                                     alt="Foto Produk" 
                                     style="max-width: 200px; max-height: 200px; margin-top: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            </p>
                        <?php endif; ?>
                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/jpg,image/png,image/gif">
                        <small style="display: block; margin-top: 5px; color: #666;">Format: JPG, JPEG, PNG, GIF (Maksimal 5MB)</small>
                    </div>

                    <button type="submit" class="btn-action btn-save">Simpan Perubahan</button>

                    <!-- <button type="submit" class="btn-action btn-save">Simpan Perubahan</button>
                    <a href="manajemen_produk.php" class="btn-action btn-back" style="background-color: #6c757d;">Batal</a> -->
                </form>
            </div>
        </div>
    </div>
</body>
</html>