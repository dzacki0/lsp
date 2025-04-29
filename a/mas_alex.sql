-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Apr 2025 pada 19.04
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mas_alex`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `meja`
--

CREATE TABLE `meja` (
  `id` int(11) NOT NULL,
  `nomor_meja` varchar(10) DEFAULT NULL,
  `status` enum('available','occupied') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `meja`
--

INSERT INTO `meja` (`id`, `nomor_meja`, `status`) VALUES
(1, '1', NULL),
(4, '2', ''),
(5, '3', ''),
(6, '4', ''),
(7, '5', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `kategori` enum('makanan','minuman') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `harga`, `kategori`) VALUES
(1, 'ayam betutu', 10000, 'makanan'),
(3, 'ayam taliwang', 12000, 'makanan'),
(4, 'esteh', 3000, 'minuman');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
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
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `id_meja`, `id_pelanggan`, `id_menu`, `id_waiter`, `tanggal`, `total_bayar`, `jumlah`) VALUES
(6, 1, 3, 1, NULL, '2025-04-29 03:12:54', NULL, 3),
(7, 5, 2, 1, NULL, '2025-04-29 17:56:57', NULL, 8),
(8, 1, 4, 1, NULL, '2025-04-29 18:08:45', NULL, 6),
(9, 1, 2, 1, NULL, '2025-04-29 18:31:28', NULL, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `id_order` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `kontak` varchar(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama_pelanggan`, `kontak`, `alamat`, `jenis_kelamin`) VALUES
(1, '', '', 'ciracas', 'Laki-Laki'),
(2, 'alex', '09865432', 'ciracas', 'Laki-Laki'),
(3, 'asep', '0812', 'ciracas', 'Laki-Laki'),
(4, 'zek', '0812', 'ciracas', 'Laki-Laki'),
(5, 'ose', '09876543', 'ciracas', 'Perempuan'),
(6, 'ose', '09876543', 'ciracas', 'Perempuan'),
(7, 'ose', '09876543', 'ciracas', 'Perempuan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `total_bayar` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `id_pelanggan`, `id_menu`, `total_bayar`, `tanggal`) VALUES
(1, 2, NULL, 30000, '2025-04-28 21:15:19'),
(2, 3, 1, 30000, '2025-04-29 12:45:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran_selesai`
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
-- Dumping data untuk tabel `pembayaran_selesai`
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
(17, 3, 30000, 9999999, 9969999, '2025-04-29 15:22:38', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
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
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_pelanggan`, `id_menu`, `jumlah`, `total_bayar`, `created_at`) VALUES
(5, 2, 1, 8, 80000.00, '2025-04-29 17:57:12'),
(6, 4, 1, 6, 60000.00, '2025-04-29 18:08:58'),
(7, 4, 1, 6, 60000.00, '2025-04-29 18:09:01'),
(8, 4, 1, 6, 60000.00, '2025-04-29 18:09:03'),
(9, 2, 1, 10, 100000.00, '2025-04-29 18:31:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','waiter','kasir','owner') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `users`
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
-- Indeks untuk tabel `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_meja` (`nomor_meja`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_meja` (`id_meja`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_waiter` (`id_waiter`);

--
-- Indeks untuk tabel `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pembayaran_selesai`
--
ALTER TABLE `pembayaran_selesai`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `meja`
--
ALTER TABLE `meja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pembayaran_selesai`
--
ALTER TABLE `pembayaran_selesai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`id_waiter`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
