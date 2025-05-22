-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Apr 2025 pada 13.51
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
-- Database: `laundry1`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(25) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `prioritas` varchar(50) NOT NULL,
  `no_tlp` varchar(25) NOT NULL,
  `weight` varchar(25) NOT NULL,
  `type` varchar(25) NOT NULL,
  `date_received` date NOT NULL,
  `amount` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `nama`, `prioritas`, `no_tlp`, `weight`, `type`, `date_received`, `amount`) VALUES
(6, 'Olip', '1', '087556643090', '4', '2', '2025-03-13', '28000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `report`
--

CREATE TABLE `report` (
  `id` int(25) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `berat` varchar(50) NOT NULL,
  `type` varchar(25) NOT NULL,
  `date_received` date NOT NULL,
  `harga` varchar(50) NOT NULL,
  `date_finished` date NOT NULL,
  `pay_money` varchar(25) NOT NULL,
  `refund` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `report`
--

INSERT INTO `report` (`id`, `nama`, `berat`, `type`, `date_received`, `harga`, `date_finished`, `pay_money`, `refund`) VALUES
(4, 'Rahmat', '1', '1', '2025-03-13', '5000', '2025-03-13', '5000', '0'),
(7, 'Aden', '4', '2', '2025-03-13', '28000', '2025-03-13', '30000', '2000'),
(8, 'wahyu', '2', '3', '2025-03-05', '16000', '2025-03-13', '20000', '4000'),
(9, 'Safira', '2', '1', '2025-03-10', '10000', '2025-03-13', '10000', '0'),
(10, 'Rojak', '6', '1', '2025-03-01', '30000', '2025-03-03', '50000', '20000'),
(11, 'Erik', '3', '3', '2025-03-13', '24000', '2025-03-15', '25000', '1000'),
(12, 'Aden', '3', '1', '2025-03-14', '15000', '2025-03-15', '50000', '35000'),
(13, 'Sabrina', '3', '1', '2025-03-15', '15000', '2025-03-17', '20000', '5000'),
(14, 'Budi', '2', '3', '2025-03-15', '16000', '2025-03-16', '20000', '4000'),
(15, 'Erik', '6', '1', '2025-03-18', '30000', '2025-03-20', '50000', '20000'),
(16, 'Mojo', '3', '4', '2025-03-18', '30000', '2025-03-19', '50000', '20000'),
(17, 'Aden', '2', '3', '2025-03-20', '16000', '2025-03-21', '20000', '4000'),
(18, 'Huda', '1', '4', '2025-04-18', '10000', '2025-04-18', '1000', '-9000'),
(19, 'Denis', '2', '3', '2025-04-18', '16000', '2025-04-18', '20000', '4000'),
(20, 'Raju', '3', '3', '2025-04-18', '24000', '2025-04-19', '25000', '1000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `type`
--

CREATE TABLE `type` (
  `id` int(25) NOT NULL,
  `type_laundry` varchar(25) NOT NULL,
  `price` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `type`
--

INSERT INTO `type` (`id`, `type_laundry`, `price`) VALUES
(1, 'Baju', '6000'),
(2, 'Mukena', '7000'),
(3, 'Selimut', '8000'),
(4, 'Karpet', '10000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(25) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(25) NOT NULL,
  `role` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'rifki', 'rifki@gmail.com', '123', 'admin'),
(2, 'rahmat', 'rahmat@gmail.com', '123', 'kasir'),
(3, 'wahyu', 'wahyu@gmail.com', '123', 'admin'),
(4, 'olip', 'olip@gmail.com', '123', 'admin'),
(5, 'safira', 'safira@gmail.com', '123', 'admin'),
(6, 'irul', 'irul@gmail.com', '123', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `report`
--
ALTER TABLE `report`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `type`
--
ALTER TABLE `type`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
