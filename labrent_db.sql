-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Jun 2026 pada 14.01
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
-- Database: `labrent_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alat`
--

CREATE TABLE `alat` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `kondisi_alat` enum('Baik','Retak','Rusak') DEFAULT NULL,
  `merek_alat` varchar(100) DEFAULT NULL,
  `jumlah_alat` int(11) NOT NULL DEFAULT 0,
  `ukuran` varchar(50) DEFAULT NULL,
  `kategori` enum('Alat','Bahan') DEFAULT 'Alat',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alat`
--

INSERT INTO `alat` (`id`, `nama_alat`, `kondisi_alat`, `merek_alat`, `jumlah_alat`, `ukuran`, `kategori`, `created_at`) VALUES
(1, 'Mikroskop Binokuler', 'Baik', 'Olympus', 5, 'Besar', 'Bahan', '2026-04-01 01:30:13'),
(3, 'Gelas Ukur', 'Baik', 'Herma', 28, '100ml', 'Alat', '2026-04-01 01:30:13'),
(4, 'Beaker Glass', 'Retak', 'Pyrex', 5, '250ml', 'Bahan', '2026-04-01 01:30:13'),
(5, 'Pipet Tetes', 'Baik', 'General', 16, 'Kecil', 'Alat', '2026-04-01 01:30:13'),
(7, 'Mikroskop', 'Baik', 'www', 9, '90ml', 'Alat', '2026-05-06 00:21:52'),
(14, 'Kulkas', 'Rusak', 'Sony', 0, '20m', 'Bahan', '2026-05-06 00:35:38'),
(15, 'Tabung Reaksi', 'Baik', 'Pyr', 30, '20ml', 'Alat', '2026-05-06 01:05:44'),
(17, 'Botol Kaca', 'Retak', 'm', 8, '10m', 'Alat', '2026-05-12 17:29:35'),
(18, 'Toples', '', '', 0, '', 'Alat', '2026-05-12 17:30:39'),
(19, 'a', '', '', 0, '', 'Alat', '2026-05-20 02:22:51'),
(20, 'b', '', '', 0, '', 'Alat', '2026-05-20 02:22:56'),
(21, 'c', '', '', 0, '', 'Alat', '2026-05-20 02:23:01'),
(22, 'd', '', '', 0, '', 'Alat', '2026-05-20 02:23:05'),
(23, 'e', '', '', 0, '', 'Bahan', '2026-05-20 02:23:14'),
(24, 'f', '', '', 0, '', 'Alat', '2026-05-20 02:23:19'),
(25, 'g', '', '', 0, '', 'Bahan', '2026-05-20 02:23:25'),
(26, 'h', '', '', 0, '', 'Alat', '2026-05-20 03:09:14'),
(27, 'i', '', '', 0, '', 'Alat', '2026-05-20 03:09:19'),
(28, 'j', '', '', 0, '', 'Alat', '2026-05-20 03:09:25'),
(29, 'K', 'Rusak', '', 10, '1000ml', 'Bahan', '2026-05-21 14:40:53'),
(30, 'Cawan Penguap', 'Baik', '', 10, '', 'Alat', '2026-06-01 02:12:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `modul`
--

CREATE TABLE `modul` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `nama_kelas` varchar(50) DEFAULT NULL,
  `nama_matakuliah` varchar(100) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `nama_pengupload` varchar(100) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `ukuran` varchar(50) DEFAULT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `modul`
--

INSERT INTO `modul` (`id`, `judul`, `nama_kelas`, `nama_matakuliah`, `kategori`, `nama_pengupload`, `nama_file`, `ukuran`, `tgl_upload`) VALUES
(18, 'Kimia', 'Kimia A', 'Kimia Dasar', 'Laporan', 'matthewm', '1779245760_5063_19216_1_PB.pdf', '273.81', '2026-05-20 02:56:00'),
(21, 'prak', 'Kimia A', 'Kimia Dasar', 'Modul Praktikum', 'Haira Kayshila ', '1780383204_Black_and_Gray_Minimalist_Creative_Portfolio_Presentation.pdf', '1035.01', '2026-06-02 07:53:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `nama_mahasiswa` varchar(150) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `status` enum('Menunggu Persetujuan','Dipinjam','Dikembalikan') DEFAULT 'Menunggu Persetujuan',
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `nama_mahasiswa`, `nama_alat`, `jumlah`, `tgl_pinjam`, `tgl_kembali`, `status`, `catatan`) VALUES
(7, 'matthewm', 'Gelas Ukur', 1, '2026-04-29', NULL, 'Dikembalikan', NULL),
(22, 'matthewm', 'Pipet', 1, '2026-05-09', NULL, 'Dipinjam', NULL),
(41, 'matthewm', 'Pipet Tetes', 3, '2026-05-21', NULL, 'Dipinjam', NULL),
(46, 'matthewm', 'Beaker Glass', 5, '2026-05-29', NULL, 'Dipinjam', NULL),
(47, 'Haira Kayshila ', 'Beaker Glass', 3, '2026-06-02', NULL, 'Dipinjam', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin','mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, 'admin', '$2y$10$2sn9QzwzNtVMctwzyn21ueeetGBCJPboXYIVnqhtQRpkUd4NQF1aS', 'Administrator Lab', 'admin'),
(3, 'matt', '$2y$10$sAQOrnEPRuKkG6Kkk3R13OXNp3Y7RijtyHiwazTMwBfBiTDtwP0iW', 'matthewm', 'mahasiswa'),
(7, 'kiapotabuga', '$2y$10$yDRf5jPGjxsFdDSPPyyn1ONREE.epkIajV2gnuvyfBQsKGP36AqLa', 'Hilkia Potabuga', 'mahasiswa'),
(8, 'hairaila', '$2y$10$kh43Emb5nr0AhcvoTaRdIu/Y060jPmbq/QtUui0zzYDpoH5Q95OhW', 'Haira Kayshila ', 'mahasiswa'),
(9, 'mad', '$2y$10$ERsSxxF62vaFYxNgQCIz0eftewC4RaJVT/Jf9aJce.L8T7MK1e6pa', 'ahmad', 'mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT untuk tabel `alat`
--
ALTER TABLE `alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `modul`
--
ALTER TABLE `modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
