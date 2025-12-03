-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250914.f72491a1c0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 02, 2025 at 11:38 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_restoran`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `product_id` int NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `pesan` text NOT NULL,
  `status_baca` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL,
  `tanggal_order` datetime NOT NULL,
  `status_order` varchar(50) NOT NULL,
  `nama_penerima` varchar(100) NOT NULL,
  `email_penerima` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_bayar`, `tanggal_order`, `status_order`, `nama_penerima`, `email_penerima`, `telepon`, `alamat`, `metode_pembayaran`, `is_read`) VALUES
(2, 2, 250000.00, '2025-11-18 12:16:45', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(3, 2, 250000.00, '2025-11-18 12:16:51', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(4, 2, 250000.00, '2025-11-18 12:17:19', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(5, 6, 50000.00, '2025-11-18 12:22:23', 'Menunggu Pembayaran', 'yusuf Aray', 'yuuu@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(6, 6, 50000.00, '2025-11-18 12:28:37', 'Menunggu Pembayaran', 'yusuf Aray', 'yuuu@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(7, 6, 250000.00, '2025-11-18 12:29:43', 'Menunggu Pembayaran', 'yusuf Aray', 'yuuu@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'TRANSFER', 0),
(8, 6, 250000.00, '2025-11-18 12:30:31', 'Menunggu Pembayaran', 'yusuf Aray', 'yuuu@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'TRANSFER', 0),
(9, 6, 250000.00, '2025-11-18 12:35:14', 'Menunggu Pembayaran', 'yusuf Aray', 'yuuu@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'TRANSFER', 0),
(10, 6, 250000.00, '2025-11-18 12:35:20', 'Menunggu Pembayaran', 'yusuf Aray', 'yuuu@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'TRANSFER', 0),
(11, 6, 250000.00, '2025-11-18 12:35:40', 'Menunggu Pembayaran', 'yusuf Aray', 'yuuu@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'TRANSFER', 0),
(12, 2, 50000.00, '2025-11-18 23:34:08', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(13, 2, 185000.00, '2025-11-18 23:36:08', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(14, 2, 50000.00, '2025-11-18 23:39:17', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(15, 2, 50000.00, '2025-11-19 12:12:06', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(16, 2, 300000.00, '2025-11-19 12:14:30', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(17, 2, 50000.00, '2025-11-19 12:32:59', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(18, 2, 100000.00, '2025-11-19 13:05:04', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'TRANSFER', 0),
(19, 8, 70000.00, '2025-11-20 01:13:29', 'Menunggu Pembayaran', 'muhaimin', 'hamid@gmail.com', '081337470942', 'anaraja ', 'CASH', 0),
(20, 2, 50000.00, '2025-11-20 02:12:01', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(21, 2, 100000.00, '2025-11-20 13:02:10', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'TRANSFER', 0),
(22, 2, 100000.00, '2025-11-20 13:36:22', 'Menunggu Pembayaran', 'alif', 'yusufaray81@gmail.com', '53464767788', '                                           alor ', 'CASH', 0),
(23, 9, 85000.00, '2025-11-21 00:12:50', 'Menunggu Pembayaran', 'laila', 'laila@gmail.com', '082144780587', '                                            labuan bajo', 'TRANSFER', 0),
(27, 8, 50000.00, '2025-11-22 06:08:37', 'Menunggu Pembayaran', 'fdhfgvdsf', 'hamid@gmail.com', '656456', '                        adonara                    ', 'CASH', 0),
(28, 2, 160000.00, '2025-11-23 12:13:16', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0),
(29, 2, 120000.00, '2025-11-24 12:14:32', 'Menunggu Pembayaran', 'yusuf Aray', 'yusufaray81@gmail.com', '081337470942', 'reo, reok, jl lintas graja katolik', 'CASH', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_satuan` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `produk_id`, `quantity`, `price_satuan`, `subtotal`) VALUES
(52, 28, 38, 1, 100000.00, 100000.00),
(53, 28, 41, 1, 10000.00, 10000.00),
(54, 28, 43, 2, 5000.00, 10000.00),
(55, 28, 40, 1, 10000.00, 10000.00),
(56, 28, 42, 1, 5000.00, 5000.00),
(59, 29, 43, 4, 5000.00, 20000.00),
(60, 29, 41, 1, 10000.00, 10000.00),
(61, 29, 40, 1, 10000.00, 10000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int NOT NULL,
  `id_pesanan` varchar(100) NOT NULL,
  `nama_pembeli` varchar(100) NOT NULL,
  `jumlah` int NOT NULL,
  `makanan` varchar(255) NOT NULL,
  `status` enum('proses','selesai','batal') NOT NULL DEFAULT 'proses',
  `tanggal_pesan` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `id_pesanan`, `nama_pembeli`, `jumlah`, `makanan`, `status`, `tanggal_pesan`) VALUES
(1, 'grtgh', 'hrthtrh', 4, 'hgfhh', 'proses', '2025-11-04 01:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `nama_makanan` varchar(100) NOT NULL,
  `harga_makanan` decimal(10,0) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama_makanan`, `harga_makanan`, `foto`, `kategori`) VALUES
(38, 'Bebek Bakar', 100000, '1763792945_692158312750b.jpg', 'Ayam dan Bebek'),
(39, 'Bebek Goreng', 25000, '1763792983_692158575730e.jpg', 'Ayam dan Bebek'),
(40, 'Risol Ayam', 10000, '1763793199_6921592f91386.jpg', 'Makanan pembuka'),
(41, 'Pisang Coklat', 10000, '1763793227_6921594b4d7a7.jpg', 'Makanan pembuka'),
(42, 'Sosis Bakar', 5000, '1763793266_69215972ca4d6.jpg', 'Makanan pembuka'),
(43, 'Es Kopi', 5000, '1763793292_6921598c869ed.png', 'Makanan pembuka'),
(46, 'ayam', 5000, '1763991235_69245ec3a478d.jpg', 'Ayam dan Bebek'),
(47, 'Bakso', 15000, '1764073335_69259f774c0f8.jpg', 'Menu Utama'),
(48, 'Bakso Bakar', 10000, '1764073371_69259f9b6258e.jpg', 'Menu Utama'),
(49, 'Mie Ayam', 15000, '1764073449_69259fe92ebaa.jpg', 'Menu Utama'),
(50, 'Nasi Goreng', 20000, '1764073640_6925a0a83facb.jpg', 'Menu Utama'),
(51, 'Nasi Kuning', 10000, '1764073682_6925a0d202129.jpg', 'Menu Utama'),
(52, 'Soto Ayam', 25000, '1764073709_6925a0ed9dc2c.jpg', 'Menu Utama'),
(53, 'Sate Ayam', 30000, '1764074049_6925a2414dbcf.jpg', 'Menu Utama'),
(54, 'Martabak Manis', 20000, '1764074281_6925a32962b5e.jpg', 'Makanan pembuka'),
(55, 'Tahu Goreng', 5000, '1764074332_6925a35c99728.jpg', 'Makanan pembuka'),
(56, 'Bubur Kacang HIjau', 5000, '1764074365_6925a37d219c3.jpg', 'Makanan pembuka'),
(57, 'Ayam Bakar', 50000, '1764074437_6925a3c5519d6.jpg', 'Ayam dan Bebek'),
(58, 'Telu Gulung', 50000, '1764074476_6925a3ecccdd0.jpg', 'Makanan pembuka');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `nama_lengkap`, `alamat`) VALUES
(2, 'useryu', 'yusufaray81@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'YUSUF ', NULL),
(4, 'rehannn', 'raihan@gmail.com', 'd175a00b058e33c88a881de9d53b3975', 'raihaan', NULL),
(5, 'tawabbb', 'tawwab@gmail.com', 'adcaec3805aa912c0d0b14a81bedb6ff', 'Ali abdilah mutawab', NULL),
(6, 'yusufff', 'yuuu@gmail.com', '508df4cb2f4d8f80519256258cfb975f', 'YUSUF ', NULL),
(8, 'hamidd', 'hamid@gmail.com', 'dfb8e2bec9362a4e99e0cc79af77f123', 'Muhaimin  mahmud', 'adonara'),
(9, 'lail', 'laila@gmail.com', '0fbeea2166b4ac33c9160c78b175e061', 'laila', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
