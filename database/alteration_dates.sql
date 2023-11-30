-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2023 at 10:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rico_db_changes`
--

-- --------------------------------------------------------

--
-- Table structure for table `alteration_dates`
--

CREATE TABLE `alteration_dates` (
  `id` int(11) NOT NULL,
  `alteration_id` bigint(20) NOT NULL,
  `stage` varchar(200) NOT NULL,
  `dated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alteration_dates`
--

INSERT INTO `alteration_dates` (`id`, `alteration_id`, `stage`, `dated_at`) VALUES
(1, 100146, 'Client Paid', '2023-11-25'),
(2, 100146, 'Payment to the Liquor Board', '2023-11-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alteration_dates`
--
ALTER TABLE `alteration_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alteration_id` (`alteration_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alteration_dates`
--
ALTER TABLE `alteration_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
