-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 10, 2020 at 10:07 AM
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
-- Table structure for table `internasional`
--

CREATE TABLE `internasional` (
  `id` int(11) NOT NULL,
  `positif` int(11) NOT NULL,
  `sembuh` int(11) NOT NULL,
  `meninggal` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `internasional`
--

INSERT INTO `internasional` (`id`, `positif`, `sembuh`, `meninggal`, `created_at`, `updated_at`) VALUES
(3, 1605548, 356161, 95808, '2020-04-10 16:35:40', '2020-04-10 16:36:21');

-- --------------------------------------------------------

--
-- Table structure for table `nasional`
--

CREATE TABLE `nasional` (
  `id` int(11) NOT NULL,
  `positif` int(11) NOT NULL,
  `sembuh` int(11) NOT NULL,
  `meninggal` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nasional`
--

INSERT INTO `nasional` (`id`, `positif`, `sembuh`, `meninggal`, `created_at`, `updated_at`) VALUES
(3, 3293, 252, 280, '2020-04-08 15:48:04', '2020-04-10 15:48:04'),
(4, 3512, 282, 306, '2020-04-09 15:51:28', '2020-04-10 16:04:14'),
(6, 3512, 282, 306, '2020-04-10 17:05:03', '2020-04-10 17:05:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `internasional`
--
ALTER TABLE `internasional`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nasional`
--
ALTER TABLE `nasional`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `internasional`
--
ALTER TABLE `internasional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nasional`
--
ALTER TABLE `nasional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
