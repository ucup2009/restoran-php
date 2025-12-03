<?php
session_start();
include 'koneksi.php';
// Periksa keamanan
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// 1. Logika untuk memproses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_makanan = mysqli_real_escape_string($conn, $_POST['nama_makanan']);
    $harga_makanan = mysqli_real_escape_string($conn, $_POST['harga_makanan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
   // Proses upload foto
   $foto = 'placeholder_foto.jpg'; // Default placeholder
    
   if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
       $file = $_FILES['foto'];
       $file_name = $file['name'];
       $file_tmp = $file['tmp_name'];
       $file_size = $file['size'];
       $file_error = $file['error'];
       
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
                   $foto = $new_file_name;
               } else {
                   echo "<script>alert('Gagal mengupload foto!');</script>";
               }
           } else {
               echo "<script>alert('Ukuran file terlalu besar! Maksimal 5MB.');</script>";
           }
       } else {
           echo "<script>alert('Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau GIF.');</script>";
       }
   }
    // Tidak perlu perubahan di sini pada proses penyimpanan,
    // karena proses upload sudah berjalan baik.
    // Untuk MENAMPILKAN foto di halaman, biasanya dilakukan pada bagian form (HTML) di bawah.
    // Namun, jika ingin menampilkan preview foto yang baru diupload setelah submit (atau saat menambah baru), 
    // kita bisa tampilkan gambar yang sudah dipilih user di form. Misal:
    // (Masukkan kode ini di bawah form input file pada form HTML:)
    //
    // <div id="preview-foto"></div>
    // <script>
    // document.getElementById('foto').addEventListener('change', function(e) {
    //     var file = e.target.files[0];
    //     if (file) {
    //         var reader = new FileReader();
    //         reader.onload = function(e) {
    //             document.getElementById('preview-foto').innerHTML = '<img src="'+e.target.result+'" style="max-width:200px;max-height:200px;margin-top:10px;">';
    //         }
    //         reader.readAsDataURL(file);
    //     } else {
    //         document.getElementById('preview-foto').innerHTML = '';
    //     }
    // });
    // </script>
    //
    // Jika ingin menampilkan foto default atau foto yang baru disubmit (setelah reload), tampilkan ini SETELAH proses penambahan produk:
    // Namun pada halaman tambah_produk.php, setelah produk ditambahkan, user diarahkan ke halaman manajemen_produk.php.
    // Maka cukup tambahkan preview pada form untuk menampilkannya sebelum submit.

    // Query INSERT data ke tabel produk
    $insert_query = "INSERT INTO produk (nama_makanan, harga_makanan, foto, kategori) 
                     VALUES ('$nama_makanan', '$harga_makanan', '$foto', '$kategori')";
    
    if (mysqli_query($conn, $insert_query)) {
        // Jika berhasil, redirect kembali ke halaman manajemen produk
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='manajemen_produk.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - Dapur Azizah</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/manajemen_produk.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" href="css/theme.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                <li><a href="order.php"><i class="fas fa-clipboard-list"></i> Order</a></li>
                <li><a href="manajemen_produk.php" class="active"><i class="fas fa-utensils"></i> Manajemen Produk</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <header class="top-bar">
            <div class="icons">
                <i class="fas fa-search"></i>
                <i class="fas fa-bell"></i>
            </div>
        </header>

        <div class="dashboard-area">
            <h2>Tambah Produk Baru</h2>
            <div class="form-container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label for="nama_makanan">Nama Makanan:</label>
                        <input type="text" id="nama_makanan" name="nama_makanan" required>
                    </div>

                    <div class="form-group">
                        <label for="harga_makanan">Harga Makanan (contoh: 50000):</label>
                        <input type="number" id="harga_makanan" name="harga_makanan" required min="1000">
                    </div>
                    
                    <div class="form-group">
                        <label for="kategori">Kategori:</label>
                        <select id="kategori" name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Makanan pembuka">Makanan pembuka</option>
                            <option value="Ayam dan Bebek">Ayam dan Bebek</option>
                            <option value="Menu Utama">Menu Utama</option>
                            </select>
                    </div>

                    <div class="form-group">
                        <label for="foto">Foto Produk:</label>
                        <input type="file" id="foto" name="foto">
                    </div>
                    
                    <button type="submit" class="btn-action btn-tambah">Simpan Produk</button>
                    <a href="manajemen_produk.php" class="btn-action btn-back" style="background-color: #6c757d;">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>