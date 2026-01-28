-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 08, 2026 at 02:28 AM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pwd2025`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_identitas_mahasiswa`
--

CREATE TABLE `tbl_identitas_mahasiswa` (
  `cmid` int(11) NOT NULL,
  `cnim` varchar(20) NOT NULL,
  `cnama` varchar(100) NOT NULL,
  `ctempat_lahir` varchar(50) NOT NULL,
  `ctanggal_lahir` date NOT NULL,
  `chobi` varchar(100) NOT NULL,
  `cpasangan` varchar(50) DEFAULT NULL,
  `cpekerjaan` varchar(50) DEFAULT NULL,
  `cnama_ortu` varchar(100) NOT NULL,
  `cnama_kakak` varchar(100) DEFAULT NULL,
  `cnama_adik` varchar(100) DEFAULT NULL,
  `dcreated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dupdated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_identitas_mahasiswa`
--

INSERT INTO `tbl_identitas_mahasiswa` (`cmid`, `cnim`, `cnama`, `ctempat_lahir`, `ctanggal_lahir`, `chobi`, `cpasangan`, `cpekerjaan`, `cnama_ortu`, `cnama_kakak`, `cnama_adik`, `dcreated_at`, `dupdated_at`) VALUES
(1, '2511500008', 'Raka Pradipta', 'Jebus', '2005-02-06', 'Futsal, Volly, dan Sepak Bola.', 'Rulita Rizqi Aprillia', 'Mahasiswa', 'Muhammad Dian Alifi dan Rusmi Hartati', 'Adityo Nurzi dan Dwi Adji Muzhaffar', '-', '2026-01-07 07:12:24', '2026-01-07 07:32:26'),
(2, '2511500039', 'Fiki', 'Muntok', '1997-12-31', 'ngelas', 'wak be', 'Mahasiswa', 'abun dan lana', 'abuy', '-', '2026-01-07 07:35:05', '2026-01-07 07:35:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_identitas_mahasiswa`
--
ALTER TABLE `tbl_identitas_mahasiswa`
  ADD PRIMARY KEY (`cmid`),
  ADD UNIQUE KEY `cnim` (`cnim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_identitas_mahasiswa`
--
ALTER TABLE `tbl_identitas_mahasiswa`
  MODIFY `cmid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
