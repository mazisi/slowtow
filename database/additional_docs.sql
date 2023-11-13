-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2023 at 09:53 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slotow`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_docs`
--

CREATE TABLE `additional_docs` (
  `id` bigint(20) NOT NULL,
  `licence_id` bigint(20) NOT NULL,
  `description` text NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `uploaded_at` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `additional_docs`
--

INSERT INTO `additional_docs` (`id`, `licence_id`, `description`, `document_name`, `path`, `uploaded_at`, `created_at`, `updated_at`) VALUES
(4, 104684, 'I know Angular isn’t the most popular framework amongst developers', '-_PleaseRead_-.pdf', 'mrnlabs/391...__PleaseRead__.pdf', '2023-11-13', '2023-11-13 20:09:15', '2023-11-13 20:09:15'),
(5, 104684, 'It is a strongly opinionated, object-oriented framework', '-_PleaseRead_-.pdf', 'mrnlabs/5eb...__PleaseRead__.pdf', '2023-11-16', '2023-11-13 20:09:59', '2023-11-13 20:09:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_docs`
--
ALTER TABLE `additional_docs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licence_id` (`licence_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_docs`
--
ALTER TABLE `additional_docs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `additional_docs`
--
ALTER TABLE `additional_docs`
  ADD CONSTRAINT `licence_id` FOREIGN KEY (`licence_id`) REFERENCES `licences` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
