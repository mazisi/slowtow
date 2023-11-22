-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2023 at 01:56 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

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
-- Table structure for table `licence_types`
--

CREATE TABLE `licence_types` (
  `id` bigint(20) NOT NULL,
  `licence_type` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `province` enum('Mpumalanga','Western Cape','KwaZulu-Natal','Gauteng','Free State','North West','Eastern Cape','Northern Cape','Limpopo','null') DEFAULT 'null',
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `licence_types`
--

INSERT INTO `licence_types` (`id`, `licence_type`, `province`, `created_at`, `updated_at`) VALUES
(1, 'Club Liquor Licence', 'Gauteng', NULL, NULL),
(2, 'Dance Hall Liquor Licence', 'Gauteng', NULL, NULL),
(3, 'Discretionary Off-Consumption Liquor Licence', 'Gauteng', NULL, NULL),
(4, 'Discretionary On-Consumption Liquor Licence', 'Gauteng', NULL, NULL),
(5, 'Gaming Premises Liquor Licence', 'Gauteng', NULL, NULL),
(6, 'Grocers Wine Liquor Licence', 'Gauteng', NULL, NULL),
(7, 'Hotel Liquor Licence', 'Gauteng', NULL, NULL),
(8, 'Liquor Store Liquor Licence', 'Gauteng', NULL, NULL),
(9, 'Micro-Manufacturing Liquor Licence', 'Gauteng', NULL, NULL),
(10, 'National Distribution Liquor Licence', 'Gauteng', NULL, NULL),
(11, 'National Manufacturing and Distribution Liquor Licence', 'Gauteng', NULL, NULL),
(12, 'National Manufacturing Liquor Licence', 'Gauteng', NULL, NULL),
(13, 'Nightclub Liquor Licence', 'Gauteng', NULL, NULL),
(14, 'Occasional Liquor Licence', 'Gauteng', NULL, NULL),
(15, 'Pool Club Liquor Licence', 'Gauteng', NULL, NULL),
(16, 'Pub Liquor Licence', 'Gauteng', NULL, NULL),
(17, 'Restaurant Liquor Licence', 'Gauteng', NULL, NULL),
(18, 'Sorghum Beer (Off) Consumption Liquor Licence', 'Gauteng', NULL, NULL),
(19, 'Sorghum Beer (On) Consumption Liquor Licence', 'Gauteng', NULL, NULL),
(20, 'Sports Club Liquor Licence', 'Gauteng', NULL, NULL),
(21, 'Tavern Liquor Licence', 'Gauteng', NULL, NULL),
(22, 'Theatre Liquor Licence', 'Gauteng', NULL, NULL),
(23, 'UNKNOWN', 'Gauteng', NULL, NULL),
(24, 'Functions Venue Liquor Licence', 'Gauteng', NULL, NULL),
(25, 'Accommodation Liquor Licence', 'Gauteng', NULL, NULL),
(26, 'Airport Lounge Liquor Licence', 'Gauteng', NULL, NULL),
(27, 'Guest House Liquor Licence', 'Gauteng', NULL, NULL),
(28, 'Off-Consumption Liquor Licence', 'Gauteng', NULL, NULL),
(30, 'On and Off Consumption Liquor Licence', 'Gauteng', NULL, NULL),
(31, 'On-Consumption Liquor Licence', 'Gauteng', NULL, NULL),
(32, 'Micro-Manufacturing Liquor Licence', 'Eastern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(33, 'Off-Consumption Liquor Licence', 'Eastern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(34, 'On and Off Consumption Liquor Licence', 'Eastern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(35, 'On-Consumption Liquor Licence', 'Eastern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(36, 'Accommodation Liquor Licence', 'Free State', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(37, 'Club Liquor Licence', 'Free State', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(38, 'Gaming Premises Liquor Licence', 'Free State', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(39, 'Micro-Manufacturing Liquor Licence', 'Free State', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(40, 'Nightclub Liquor Licence', 'Free State', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(41, 'Restaurant Liquor Licence', 'Free State', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(42, 'Tavern Liquor Licence', 'Free State', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(43, 'Accommodation Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(44, 'Restaurant Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(45, 'Club Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(46, 'Nightclub Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(47, 'Gaming Premises Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(48, 'Sports Club Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(49, 'Pub Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(50, 'Tavern Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(51, 'Theatre Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(52, 'Liquor Store Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(53, 'Grocers Wine Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(54, 'Micro-Manufacturing Liquor Licence', 'KwaZulu-Natal', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(55, 'Hotel Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(56, 'Restaurant Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(57, 'Wine-House Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(58, 'Theatre Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(59, 'Club Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(60, 'Sorghum Beer (On) Consumption Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(61, 'Micro-Manufacturing Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(62, 'Liquor Store Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(63, 'Grocers Wine Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(64, 'Wine-Farmers Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(65, 'Sorghum Beer (Off) Consumption Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(66, 'Sorghum Brewers Liquor Licence', 'Limpopo', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(67, 'Micro-Manufacturing Liquor Licence', 'Mpumalanga', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(68, 'Off-Consumption Liquor Licence', 'Mpumalanga', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(69, 'On and Off Consumption Liquor Licence', 'Mpumalanga', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(70, 'On-Consumption Liquor Licence', 'Mpumalanga', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(71, 'Hotel Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(72, 'Restaurant Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(73, 'Theatre Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(74, 'Wine-House Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(75, 'Club Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(76, 'Sorghum Beer (Off) Consumption Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(77, 'Sorghum Beer (On) Consumption Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(78, 'Tavern Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(79, 'Guest House Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(80, 'Gaming Premises Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(81, 'Sports Club Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(82, 'Liquor Store Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(83, 'Grocers Wine Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(84, 'Micro-Manufacturing Liquor Licence', 'Northern Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(85, 'Accommodation Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(86, 'Restaurant Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(87, 'Theatre Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(88, 'Club Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(89, 'Sports Ground Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(90, 'Sports Bar Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(91, 'Nightclub Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(92, 'Gaming Premises Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(93, 'Tavern Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(94, 'Pub Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(95, 'Grocers Wine Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(96, 'Liquor Store Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(97, 'Micro-Manufacturing Liquor Licence', 'North West', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(98, 'Off-Consumption Liquor Licence', 'Western Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(99, 'On and Off Consumption Liquor Licence', 'Western Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(100, 'On-Consumption Liquor Licence', 'Western Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(101, 'Micro-Manufacturing Liquor Licence', 'Western Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `licence_types`
--
ALTER TABLE `licence_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `licence_types`
--
ALTER TABLE `licence_types`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
