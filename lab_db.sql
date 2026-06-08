-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Bulan Mei 2026 pada 17.34
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
-- Database: `lab_db`
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
(1, 'Mikroskop Binokuler', 'Baik', 'Olympus', 1, 'Besar', 'Bahan', '2026-04-01 01:30:13'),
(3, 'Gelas Ukur', 'Baik', 'Herma', 8, '100ml', 'Alat', '2026-04-01 01:30:13'),
(4, 'Beaker Glass', 'Retak', 'Pyrex', 10, '250ml', 'Bahan', '2026-04-01 01:30:13'),
(5, 'Pipet Tetes', 'Baik', 'General', 16, 'Kecil', 'Alat', '2026-04-01 01:30:13'),
(7, 'Mikroskop', 'Baik', 'www', 9, '90ml', '', '2026-05-06 00:21:52'),
(14, 'Kulkas', 'Rusak', 'Sony', 0, '20m', 'Bahan', '2026-05-06 00:35:38'),
(15, 'Tabung Reaksi', 'Retak', 'Pyr', 30, '20ml', 'Alat', '2026-05-06 01:05:44'),
(16, 'Pipet', 'Baik', '.', 9, '89 ml', 'Alat', '2026-05-09 15:10:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `modul`
--

CREATE TABLE `modul` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `nama_pengupload` varchar(100) DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `ukuran` varchar(50) DEFAULT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `modul`
--

INSERT INTO `modul` (`id`, `judul`, `kategori`, `nama_pengupload`, `nama_file`, `ukuran`, `tgl_upload`) VALUES
(4, 'NID ', 'Modul Mahasiswa', 'Admin', '1777426232_Network_Intrusion_Detection.pdf', '782.54', '2026-04-29 01:30:32'),
(6, 'Kimia', 'Modul Mahasiswa', 'matthewmatthew', '1778509263_admin__SYSTEMATIC_LITERATURE_REVIEW_MODEL_PEMBELAJARAN_DISCOVERY_LEARNING_UNTUK_MENINGKATKAN_KEMAMPUAN_BERPIKIR_KRITIS.pdf', '264.8', '2026-05-11 14:21:03');

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
(2, 'Budi Utomo', 'Mikroskop Binokuler', 1, '2023-10-01', NULL, 'Dipinjam', NULL),
(3, 'Siti Aminah', 'Tabung Reaksi', 5, '2023-10-02', NULL, 'Dipinjam', NULL),
(4, 'Andi Wijaya', 'Gelas Ukur', 2, '2023-10-03', NULL, 'Dikembalikan', NULL),
(5, 'kia', 'Gelas Ukur', 20, '2026-04-07', NULL, 'Dipinjam', NULL),
(6, 'budi', 'Tabung Reaksi', 9, '2026-04-15', NULL, 'Dikembalikan', NULL),
(7, 'matthewm', 'Gelas Ukur', 1, '2026-04-29', NULL, 'Dikembalikan', NULL),
(8, 'matthewm', 'Beaker Glass', 3, '2026-04-29', NULL, 'Dikembalikan', NULL),
(11, 'Mahasiswa', 'Beaker Glass', 2, '2026-05-06', NULL, 'Dipinjam', NULL),
(12, 'Mahasiswa', 'Tabung Reaksi', 5, '2026-05-06', NULL, 'Dikembalikan', NULL),
(13, 'matthewm', 'Mikroskop Binokuler', 2, '2026-05-07', NULL, 'Dipinjam', NULL),
(14, 'matthewm', 'Gelas Ukur', 2, '2026-05-09', NULL, 'Dipinjam', NULL),
(15, 'matthewm', 'Pipet Tetes', 3, '2026-05-09', NULL, 'Dipinjam', NULL),
(16, 'matthewm', 'Pipet', 1, '2026-05-09', NULL, 'Dipinjam', NULL),
(17, 'matthewm', 'Beaker Glass', 1, '2026-05-09', NULL, 'Dikembalikan', NULL),
(18, 'matthewm', 'Beaker Glass', 1, '2026-05-09', NULL, 'Dikembalikan', NULL),
(19, 'matthewm', 'Beaker Glass', 2, '2026-05-09', NULL, 'Menunggu Persetujuan', NULL),
(22, 'matthewm', 'Pipet', 1, '2026-05-09', NULL, 'Menunggu Persetujuan', NULL),
(24, 'matthewm', 'Beaker Glass', 1, '2026-05-09', NULL, 'Menunggu Persetujuan', NULL),
(25, 'matthewmatthew', 'Mikroskop Binokuler', 2, '2026-05-11', NULL, 'Menunggu Persetujuan', NULL);

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
(1, 'admin', '$2y$10$KJKpiaBvE3ec2YBADfSodunkmJNGoc00Gmw3tFbCxpsjY7AuEzdRq', 'Administrator Lab', 'admin'),
(2, 'mahasiswa', '$2y$10$7PMBGYbjGASXK1WUISHXeO8SsR8z3Zegg18EkzNCNoQg5gbJ4rtKS', 'Mahasiswa', 'mahasiswa'),
(3, 'matt', '$2y$10$CyAtPg2MhYVEJQsruS5upeochk7Ov7AI5aURAKhd4QC7wh.5IEYvu', 'matthewm', 'mahasiswa'),
(4, 'met', '$2y$10$43bx76KzwkpOlWhvSo8y1e3Z83RsmuLqtL7aR0qI31zOpPXPvFMEm', 'met', 'mahasiswa'),
(5, 'matthew', '$2y$10$KaKEcSMv.b9phBxLn6ak0eVe7oksBtZhrc95wa6coXhz6M5Yk8nBq', 'matthewmatthew', 'mahasiswa');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `modul`
--
ALTER TABLE `modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
