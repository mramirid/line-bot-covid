-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 11, 2020 at 06:28 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `line_bot_covid`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengambilan_provinsi`
--

CREATE TABLE `detail_pengambilan_provinsi` (
  `id` int(11) NOT NULL,
  `id_pengambilan_provinsi` int(11) NOT NULL,
  `kode_provinsi` int(11) NOT NULL,
  `nama_provinsi` varchar(50) NOT NULL,
  `positif` int(11) NOT NULL,
  `sembuh` int(11) NOT NULL,
  `dalam_perawatan` int(11) NOT NULL,
  `meninggal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_pengambilan_provinsi`
--

INSERT INTO `detail_pengambilan_provinsi` (`id`, `id_pengambilan_provinsi`, `kode_provinsi`, `nama_provinsi`, `positif`, `sembuh`, `dalam_perawatan`, `meninggal`) VALUES
(103, 11, 31, 'DKI Jakarta', 1948, 82, 1707, 159),
(104, 11, 32, 'Jawa Barat', 421, 19, 362, 40),
(105, 11, 36, 'Banten', 279, 7, 251, 21),
(106, 11, 35, 'Jawa Timur', 267, 64, 178, 25),
(107, 11, 73, 'Sulawesi Selatan', 178, 25, 138, 15),
(108, 11, 33, 'Jawa Tengah', 144, 18, 104, 22),
(109, 11, 51, 'Bali', 79, 19, 58, 2),
(110, 11, 94, 'Papua', 62, 3, 57, 2),
(111, 11, 12, 'Sumatera Utara', 59, 8, 43, 8),
(112, 11, 34, 'Daerah Istimewa Yogyakarta', 41, 6, 28, 7),
(113, 11, 64, 'Kalimantan Timur', 35, 6, 28, 1),
(114, 11, 13, 'Sumatera Barat', 31, 7, 21, 3),
(115, 11, 63, 'Kalimantan Selatan', 29, 0, 27, 2),
(116, 11, 52, 'Nusa Tenggara Barat', 27, 2, 23, 2),
(117, 11, 62, 'Kalimantan Tengah', 24, 7, 16, 1),
(118, 11, 16, 'Sumatera Selatan', 21, 1, 18, 2),
(119, 11, 21, 'Kepulauan Riau', 21, 2, 18, 1),
(120, 11, 18, 'Lampung', 20, 1, 18, 1),
(121, 11, 72, 'Sulawesi Tengah', 19, 0, 17, 2),
(122, 11, 14, 'Riau', 16, 1, 15, 0),
(123, 11, 65, 'Kalimantan Utara', 16, 0, 15, 1),
(124, 11, 74, 'Sulawesi Tenggara', 16, 1, 14, 1),
(125, 11, 71, 'Sulawesi Utara', 13, 1, 10, 2),
(126, 11, 61, 'Kalimantan Barat', 10, 3, 5, 2),
(127, 11, 11, 'Aceh', 5, 1, 3, 1),
(128, 11, 76, 'Sulawesi Barat', 5, 0, 4, 1),
(129, 11, 15, 'Jambi', 4, 0, 4, 0),
(130, 11, 17, 'Bengkulu', 4, 0, 3, 1),
(131, 11, 19, 'Kepulauan Bangka Belitung', 4, 0, 3, 1),
(132, 11, 81, 'Maluku', 3, 1, 2, 0),
(133, 11, 82, 'Maluku Utara', 2, 1, 1, 0),
(134, 11, 91, 'Papua Barat', 2, 0, 1, 1),
(135, 11, 53, 'Nusa Tenggara Timur', 1, 0, 1, 0),
(136, 11, 75, 'Gorontalo', 1, 0, 1, 0),
(137, 12, 31, 'DKI Jakarta', 1948, 82, 1707, 159),
(138, 12, 32, 'Jawa Barat', 421, 19, 362, 40),
(139, 12, 36, 'Banten', 279, 7, 251, 21),
(140, 12, 35, 'Jawa Timur', 267, 64, 178, 25),
(141, 12, 73, 'Sulawesi Selatan', 178, 25, 138, 15),
(142, 12, 33, 'Jawa Tengah', 144, 18, 104, 22),
(143, 12, 51, 'Bali', 79, 19, 58, 2),
(144, 12, 94, 'Papua', 62, 3, 57, 2),
(145, 12, 12, 'Sumatera Utara', 59, 8, 43, 8),
(146, 12, 34, 'Daerah Istimewa Yogyakarta', 41, 6, 28, 7),
(147, 12, 64, 'Kalimantan Timur', 35, 6, 28, 1),
(148, 12, 13, 'Sumatera Barat', 31, 7, 21, 3),
(149, 12, 63, 'Kalimantan Selatan', 29, 0, 27, 2),
(150, 12, 52, 'Nusa Tenggara Barat', 27, 2, 23, 2),
(151, 12, 62, 'Kalimantan Tengah', 24, 7, 16, 1),
(152, 12, 16, 'Sumatera Selatan', 21, 1, 18, 2),
(153, 12, 21, 'Kepulauan Riau', 21, 2, 18, 1),
(154, 12, 18, 'Lampung', 20, 1, 18, 1),
(155, 12, 72, 'Sulawesi Tengah', 19, 0, 17, 2),
(156, 12, 14, 'Riau', 16, 1, 15, 0),
(157, 12, 65, 'Kalimantan Utara', 16, 0, 15, 1),
(158, 12, 74, 'Sulawesi Tenggara', 16, 1, 14, 1),
(159, 12, 71, 'Sulawesi Utara', 13, 1, 10, 2),
(160, 12, 61, 'Kalimantan Barat', 10, 3, 5, 2),
(161, 12, 11, 'Aceh', 5, 1, 3, 1),
(162, 12, 76, 'Sulawesi Barat', 5, 0, 4, 1),
(163, 12, 15, 'Jambi', 4, 0, 4, 0),
(164, 12, 17, 'Bengkulu', 4, 0, 3, 1),
(165, 12, 19, 'Kepulauan Bangka Belitung', 4, 0, 3, 1),
(166, 12, 81, 'Maluku', 3, 1, 2, 0),
(167, 12, 82, 'Maluku Utara', 2, 1, 1, 0),
(168, 12, 91, 'Papua Barat', 2, 0, 1, 1),
(169, 12, 53, 'Nusa Tenggara Timur', 1, 0, 1, 0),
(170, 12, 75, 'Gorontalo', 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `nasional`
--

CREATE TABLE `nasional` (
  `id` int(11) NOT NULL,
  `positif` int(11) NOT NULL,
  `sembuh` int(11) NOT NULL,
  `meninggal` int(11) NOT NULL,
  `dalam_perawatan` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nasional`
--

INSERT INTO `nasional` (`id`, `positif`, `sembuh`, `meninggal`, `dalam_perawatan`, `created_at`, `updated_at`) VALUES
(3, 3842, 286, 327, 3229, '2020-04-11 17:11:46', '2020-04-11 17:11:46'),
(4, 3842, 286, 327, 3229, '2020-04-12 01:21:16', '2020-04-12 01:21:16');

-- --------------------------------------------------------

--
-- Table structure for table `pengambilan_provinsi`
--

CREATE TABLE `pengambilan_provinsi` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pengambilan_provinsi`
--

INSERT INTO `pengambilan_provinsi` (`id`, `created_at`, `updated_at`) VALUES
(11, '2020-04-11 21:50:10', '2020-04-11 22:50:21'),
(12, '2020-04-12 01:21:18', '2020-04-12 01:21:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pengambilan_provinsi`
--
ALTER TABLE `detail_pengambilan_provinsi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nasional`
--
ALTER TABLE `nasional`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengambilan_provinsi`
--
ALTER TABLE `pengambilan_provinsi`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pengambilan_provinsi`
--
ALTER TABLE `detail_pengambilan_provinsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `nasional`
--
ALTER TABLE `nasional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengambilan_provinsi`
--
ALTER TABLE `pengambilan_provinsi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
