-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 09:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resto`
--

-- --------------------------------------------------------

--
-- Table structure for table `meja`
--

CREATE TABLE `meja` (
  `id` int(11) NOT NULL,
  `nomor_meja` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `meja`
--

INSERT INTO `meja` (`id`, `nomor_meja`) VALUES
(4, '2'),
(5, '3'),
(6, '4');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `kategori` enum('makanan','minuman') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `harga`, `kategori`) VALUES
(9, 'es teh', 4000, 'minuman'),
(10, 'jengkol kuah', 17000, 'makanan');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `id_meja` int(11) DEFAULT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `id_waiter` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `total_bayar` decimal(10,2) DEFAULT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `id_meja`, `id_pelanggan`, `id_menu`, `id_waiter`, `tanggal`, `total_bayar`, `jumlah`) VALUES
(7, 5, 2, 1, NULL, '2025-04-29 17:56:57', NULL, 8),
(10, 4, 9, 3, NULL, '2025-04-30 11:26:15', NULL, 4),
(11, 6, 8, 6, NULL, '2025-04-30 12:58:33', NULL, 5),
(13, 4, 11, 9, NULL, '2025-04-30 13:40:41', NULL, 22),
(14, 4, 12, 10, NULL, '2025-04-30 13:55:21', NULL, 7),
(15, 4, 2, 9, NULL, '2025-04-30 14:21:53', NULL, 2),
(16, 4, 12, 9, NULL, '2025-04-30 14:22:34', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `id_order` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `kontak` varchar(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama_pelanggan`, `kontak`, `alamat`, `jenis_kelamin`) VALUES
(1, '', '', 'ciracas', 'Laki-Laki'),
(2, 'alex', '09865432', 'ciracas', 'Laki-Laki'),
(3, 'asep', '0812', 'ciracas', 'Laki-Laki'),
(4, 'zek', '0812', 'ciracas', 'Laki-Laki'),
(5, 'ose', '09876543', 'ciracas', 'Perempuan'),
(6, 'ose', '09876543', 'ciracas', 'Perempuan'),
(7, 'ose', '09876543', 'ciracas', 'Perempuan'),
(8, 'kejel', '08123', 'ciracas', 'Laki-Laki'),
(9, 'keje', '0815232132', 'ciracas', 'Perempuan'),
(10, 'susana', '0812312321', 'kalimantan', 'Perempuan'),
(11, 'sri', '0987', 'ciracas', 'Perempuan'),
(12, 'zaki', '098', 'ciracas', 'Laki-Laki');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `total_bayar` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `id_pelanggan`, `id_menu`, `total_bayar`, `tanggal`) VALUES
(1, 2, NULL, 30000, '2025-04-28 21:15:19'),
(2, 3, 1, 30000, '2025-04-29 12:45:05');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_selesai`
--

CREATE TABLE `pembayaran_selesai` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `total_bayar` int(11) DEFAULT NULL,
  `uang_diterima` int(11) DEFAULT NULL,
  `kembalian` int(11) DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_menu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pembayaran_selesai`
--

INSERT INTO `pembayaran_selesai` (`id`, `id_pelanggan`, `total_bayar`, `uang_diterima`, `kembalian`, `waktu`, `id_menu`) VALUES
(1, 3, 60000, 100000, 40000, '2025-04-28 20:50:54', NULL),
(2, 2, 30000, 40000, 10000, '2025-04-28 20:51:34', NULL),
(3, 2, 30000, 50000, 20000, '2025-04-29 04:39:30', NULL),
(4, 2, 30000, 50000, 20000, '2025-04-29 04:43:30', NULL),
(5, 3, 60000, 70000, 10000, '2025-04-29 04:44:06', NULL),
(6, 3, 60000, 70000, 10000, '2025-04-29 05:15:39', NULL),
(7, 3, 60000, 70000, 10000, '2025-04-29 05:22:36', NULL),
(8, 3, 0, 100000, 100000, '2025-04-29 06:34:31', NULL),
(9, 3, 30000, 999999999, 999969999, '2025-04-29 10:58:06', NULL),
(10, 2, 80000, 800000, 720000, '2025-04-29 11:20:21', NULL),
(11, 2, 180000, 200000, 20000, '2025-04-29 11:32:39', NULL),
(12, 3, 30000, 9999999, 9969999, '2025-04-29 14:24:46', NULL),
(13, 3, 30000, 9999999, 9969999, '2025-04-29 14:25:35', NULL),
(14, 3, 30000, 9999999, 9969999, '2025-04-29 14:26:11', NULL),
(15, 3, 30000, 9999999, 9969999, '2025-04-29 14:27:18', NULL),
(16, 3, 30000, 9999999, 9969999, '2025-04-29 14:29:41', NULL),
(17, 3, 30000, 9999999, 9969999, '2025-04-29 15:22:38', NULL),
(18, 2, 180000, 2000000, 1820000, '2025-04-30 04:17:54', NULL),
(19, 9, 48000, 50000, 2000, '2025-04-30 04:44:37', NULL),
(20, 9, 48000, 50000, 2000, '2025-04-30 04:46:01', NULL),
(21, 8, 50000, 50000, 0, '2025-04-30 05:59:01', NULL),
(22, 10, 64000, 80000, 16000, '2025-04-30 06:16:37', NULL),
(23, 11, 88000, 100000, 12000, '2025-04-30 06:48:40', NULL),
(24, 12, 119000, 120000, 1000, '2025-04-30 06:55:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `total_bayar` decimal(15,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_pelanggan`, `id_menu`, `jumlah`, `total_bayar`, `created_at`) VALUES
(13, 11, 9, 22, 88000.00, '2025-04-30 13:40:45'),
(14, 11, 9, 22, 88000.00, '2025-04-30 13:48:24'),
(15, 12, 10, 7, 119000.00, '2025-04-30 13:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','waiter','kasir','owner') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(2, 'admin', '$2y$10$ajgP24qpO6jBlBJj/YT2luzkJOUKDeGn3NYbKg.kt4UmePUxKHEka', 'admin'),
(3, 'waiter', '$2y$10$16ic2LgVh7IOCK5suLlBeOp.bN1tL691Zo/f6ngnBTDXIXWV4eLMm', 'waiter'),
(4, 'kasir', '$2y$10$CMwB12k1QAvOL3pgVtGgF.F1v1aaRQtqBGl7pyDdITLSqm54fVLg.', 'kasir'),
(5, 'owner', '$2y$10$8ZizpeTVddybOl3aCd1XdOQ57B6D/TVJnsQqC4nLwfGBGSlllMyXa', 'owner');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_meja` (`nomor_meja`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_meja` (`id_meja`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_waiter` (`id_waiter`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran_selesai`
--
ALTER TABLE `pembayaran_selesai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meja`
--
ALTER TABLE `meja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembayaran_selesai`
--
ALTER TABLE `pembayaran_selesai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`id_waiter`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
