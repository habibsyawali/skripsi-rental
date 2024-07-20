-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2022 at 09:17 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_forecastses`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `tb_alpha`
--

CREATE TABLE `tb_alpha` (
  `id_alpha` varchar(11) NOT NULL,
  `nilai_alpha` decimal(2,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_alpha`
--

INSERT INTO `tb_alpha` (`id_alpha`, `nilai_alpha`) VALUES
('A1', '0.9');

-- --------------------------------------------------------

--
-- Table structure for table `tb_penjualan`
--

CREATE TABLE `tb_penjualan` (
  `id` int(11) NOT NULL,
  `bln_thn` varchar(50) NOT NULL,
  `d_aktual` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_penjualan`
--

INSERT INTO `tb_penjualan` (`id`, `bln_thn`, `d_aktual`) VALUES
(39, 'Januari-Maret(Q1) 2000', 1517),
(40, 'April-Juni(Q2) 2000', 1248),
(41, 'Juli-September(Q3) 2000', 1677),
(42, 'Oktober-Desember(Q4) 2000', 1393),
(43, 'Januari-Maret(Q1) 2001', 1558),
(44, 'April-Juni(Q2) 2001', 1368),
(45, 'Juli-September(Q3) 2001', 1790),
(46, 'Oktober-Desember(Q4) 2001', 1396),
(47, 'Januari-Maret(Q1) 2002', 1638),
(48, 'April-Juni(Q2) 2002', 1507),
(49, 'Juli-September(Q3) 2002', 1868),
(50, 'Oktober-Desember(Q4) 2002', 1510),
(51, 'Januari-Maret(Q1) 2003', 1669),
(52, 'April-Juni(Q2) 2003', 1392),
(53, 'Juli-September(Q3) 2003', 1853),
(54, 'Oktober-Desember(Q4) 2003', 1353),
(55, 'Januari-Maret(Q1) 2004', 1623),
(56, 'April-Juni(Q2) 2004', 1401),
(57, 'Juli-September(Q3) 2004', 1758),
(58, 'Oktober-Desember(Q4) 2004', 1078),
(59, 'Januari-Maret(Q1) 2005', 1674),
(60, 'April-Juni(Q2) 2005', 1516),
(61, 'Juli-September(Q3) 2005', 1924),
(62, 'Oktober-Desember(Q4) 2005', 1522),
(63, 'Januari-Maret(Q1) 2006', 2459),
(64, 'April-Juni(Q2) 2006', 2428),
(65, 'Juli-September(Q3) 2006', 2949),
(66, 'Oktober-Desember(Q4) 2006', 2248),
(67, 'Januari-Maret(Q1) 2007', 2538),
(68, 'April-Juni(Q2) 2007', 2400),
(69, 'Juli-September(Q3) 2007', 2941),
(70, 'Oktober-Desember(Q4) 2007', 2420),
(71, 'Januari-Maret(Q1) 2008', 2621),
(72, 'April-Juni(Q2) 2008', 2521),
(73, 'Juli-September(Q3) 2008', 3083),
(74, 'Oktober-Desember(Q4) 2008', 2574),
(75, 'Januari-Maret(Q1) 2009', 2577),
(76, 'April-Juni(Q2) 2009', 2457),
(77, 'Juli-September(Q3) 2009', 2888),
(78, 'Oktober-Desember(Q4) 2009', 2458),
(79, 'Januari-Maret(Q1) 2010', 2674),
(80, 'April-Juni(Q2) 2010', 2917),
(81, 'Juli-September(Q3) 2010', 3468),
(82, 'Oktober-Desember(Q4) 2010', 2931),
(83, 'Januari-Maret(Q1) 2011', 3273),
(84, 'April-Juni(Q2) 2011', 3064),
(85, 'Juli-September(Q3) 2011', 3744),
(86, 'Oktober-Desember(Q4) 2011', 3241),
(87, 'Januari-Maret(Q1) 2012', 3824),
(88, 'April-Juni(Q2) 2012', 3517),
(89, 'Juli-September(Q3) 2012', 4173),
(90, 'Oktober-Desember(Q4) 2012', 3369),
(91, 'Januari-Maret(Q1) 2013', 3751),
(92, 'April-Juni(Q2) 2013', 3383),
(93, 'Juli-September(Q3) 2013', 3879),
(94, 'Oktober-Desember(Q4) 2013', 3391),
(95, 'Januari-Maret(Q1) 2014', 3480),
(96, 'April-Juni(Q2) 2014', 3400),
(97, 'Juli-September(Q3) 2014', 4044),
(98, 'Oktober-Desember(Q4) 2014', 3610),
(99, 'Januari-Maret(Q1) 2015', 4083),
(100, 'April-Juni(Q2) 2015', 3907),
(101, 'Juli-September(Q3) 2015', 4758),
(102, 'Oktober-Desember(Q4) 2015', 4167),
(103, 'Januari-Maret(Q1) 2016', 4769),
(104, 'April-Juni(Q2) 2016', 4199),
(105, 'Juli-September(Q3) 2016', 5413),
(106, 'Oktober-Desember(Q4) 2016', 4687),
(107, 'Januari-Maret(Q1) 2017', 5671);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
