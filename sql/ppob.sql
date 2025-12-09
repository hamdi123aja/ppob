-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 09 Des 2025 pada 03.21
-- Versi server: 8.0.30
-- Versi PHP: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppob`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_laporan_pembayaran` ()   BEGIN
    SELECT p.id, pel.nama, pel.no_meter, p.jumlah, p.tanggal_bayar, p.status
    FROM pembayaran p
    JOIN pelanggan pel ON pel.id = p.pelanggan_id
    ORDER BY p.tanggal_bayar DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_tambah_pembayaran` (IN `p_pelanggan_id` INT, IN `p_jumlah` DECIMAL(12,2), IN `p_tanggal` DATE)   BEGIN
    INSERT INTO pembayaran (pelanggan_id, jumlah, tanggal_bayar, status)
    VALUES (p_pelanggan_id, p_jumlah, p_tanggal, 'pending');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_verifikasi_pembayaran` (IN `p_id` INT)   BEGIN
    UPDATE pembayaran 
    SET status = 'verified' 
    WHERE id = p_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_meter` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `alamat`, `no_meter`, `user_id`, `created_at`) VALUES
(1, 'Dimas Erlangga', 'Agam', '15', 4, '2025-09-29 06:43:57'),
(2, 'Hamdi Septiawan', 'Batusangkar', '99', 5, '2025-09-29 06:53:42'),
(3, 'hanif dinata', 'Pasaman', '69', 6, '2025-10-29 06:48:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int NOT NULL,
  `pelanggan_id` int NOT NULL,
  `jumlah` decimal(12,2) NOT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `status` enum('pending','verified') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `pelanggan_id`, `jumlah`, `bulan`, `tahun`, `tanggal_bayar`, `status`) VALUES
(1, 2, 200000.00, NULL, NULL, '2025-09-29 06:55:45', 'verified'),
(2, 1, 3000000.00, NULL, NULL, '2025-09-29 06:59:58', 'verified'),
(3, 2, 200000.00, '1', 2025, '2025-09-30 00:00:00', 'verified'),
(5, 2, 1000000.00, 'Januari', 2025, '2025-10-03 06:13:47', 'verified'),
(6, 1, 500000.00, 'April', 2025, '2025-10-03 06:22:01', 'verified'),
(7, 1, 600000.00, 'Agustus', 2025, '2025-10-03 06:22:23', 'verified'),
(8, 2, 700000.00, 'Februari', 2025, '2025-10-03 13:34:01', 'verified'),
(9, 2, 400000.00, '6', 2025, '2025-10-03 14:23:49', 'verified'),
(10, 1, 50000.00, '4', 2024, '2025-10-04 13:29:59', 'verified'),
(13, 1, 150000.00, 'Januari', 2025, '2025-10-04 17:03:54', 'verified'),
(14, 1, 200000.00, 'Februari', 2025, '2025-10-04 17:03:54', 'verified'),
(15, 1, 175000.00, 'Maret', 2025, '2025-10-04 17:03:54', 'verified'),
(16, 1, 250000.00, 'April', 2025, '2025-10-04 17:03:54', 'verified'),
(17, 2, 180000.00, 'Januari', 2025, '2025-10-04 17:03:54', 'verified'),
(18, 2, 220000.00, 'Februari', 2025, '2025-10-04 17:03:54', 'verified'),
(25, 1, 800000.00, '9', 2025, '2025-10-05 13:02:58', 'verified'),
(28, 1, 500000.00, '10', 2025, '2025-10-05 13:51:48', 'pending'),
(29, 3, 500000.00, '10', 2025, '2025-10-29 13:48:29', 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tagihan`
--

CREATE TABLE `tagihan` (
  `id` int NOT NULL,
  `pelanggan_id` int NOT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `tanggal_bayar` datetime DEFAULT NULL,
  `jumlah` decimal(12,2) DEFAULT NULL,
  `status` enum('belum','lunas') DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','bank','pelanggan') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '123', 'admin', '2025-09-24 06:49:46'),
(2, 'bank1', '123', 'bank', '2025-09-24 06:49:46'),
(3, 'pelanggan1', '123', 'pelanggan', '2025-09-24 06:49:46'),
(4, 'dimas', '123', 'pelanggan', '2025-09-29 06:43:57'),
(5, 'hamdi', '123', 'pelanggan', '2025-09-29 06:53:42'),
(6, 'hanif', '123', 'pelanggan', '2025-10-29 06:48:09');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indeks untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

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
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tagihan`
--
ALTER TABLE `tagihan`
  ADD CONSTRAINT `tagihan_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
