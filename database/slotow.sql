-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 04, 2024 at 04:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
  `document_name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `uploaded_at` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `additional_docs`
--

INSERT INTO `additional_docs` (`id`, `licence_id`, `description`, `document_name`, `path`, `uploaded_at`, `created_at`, `updated_at`) VALUES
(7, 3, 'Test', 'null_issued_at.pdf', 'mrnlabs/cd2...null_issued_at.pdf', '2024-02-02', '2024-01-30 20:47:20', '2024-01-30 20:47:20'),
(8, 3, 'Man', NULL, NULL, '2024-02-04', '2024-02-04 13:11:01', '2024-02-04 13:11:01');

-- --------------------------------------------------------

--
-- Table structure for table `alterations`
--

CREATE TABLE `alterations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `licence_id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(5) DEFAULT NULL,
  `logded_at` varchar(30) DEFAULT NULL,
  `merged_document` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alterations`
--

INSERT INTO `alterations` (`id`, `licence_id`, `date`, `status`, `logded_at`, `merged_document`, `description`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, '600', '2024-01-19', NULL, NULL, '2e559b4093e317eec0582f885d6ed45e997c8bb6', NULL, '2024-01-31 20:09:34', '2024-01-31 20:40:14');

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
(4, 1, 'Client Paid', '2024-01-27');

-- --------------------------------------------------------

--
-- Table structure for table `alteration_documents`
--

CREATE TABLE `alteration_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alteration_id` bigint(20) UNSIGNED NOT NULL,
  `num` tinyint(4) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `doc_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alteration_documents`
--

INSERT INTO `alteration_documents` (`id`, `alteration_id`, `num`, `document_name`, `path`, `doc_type`, `created_at`, `updated_at`) VALUES
(14, 1, 1, 'null_issued_at.pdf', 'mrnlabs/d7d...null_issued_at.pdf', 'Application Form', '2024-01-31 20:14:45', '2024-01-31 20:14:45'),
(15, 1, 2, 'licences (3).pdf', 'mrnlabs/631...licences_(3).pdf', 'Fully Dimensional Plans', '2024-01-31 20:15:38', '2024-01-31 20:15:38'),
(17, 1, 4, 'licences.pdf', 'mrnlabs/870...licences.pdf', 'Smoking Affidavit', '2024-01-31 20:22:56', '2024-01-31 20:22:56'),
(18, 1, 3, 'licences (2).pdf', 'mrnlabs/7ac...licences_(2).pdf', 'POA & RES', '2024-01-31 20:23:02', '2024-01-31 20:23:02'),
(19, 1, 5, 'licences (2).pdf', 'mrnlabs/754...licences_(2).pdf', 'Payment To The Liquor Board', '2024-01-31 20:31:04', '2024-01-31 20:31:04'),
(21, 1, NULL, 'null_issued_at.pdf', 'mrnlabs/bbd...null_issued_at.pdf', 'Client Invoiced', '2024-01-31 20:38:02', '2024-01-31 20:38:02'),
(22, 1, NULL, 'licences (3).pdf', 'mrnlabs/bf1...licences_(3).pdf', 'Payment to the Liquor Board', '2024-01-31 20:38:11', '2024-01-31 20:38:11'),
(23, 1, NULL, 'null_issued_at.pdf', 'mrnlabs/854...null_issued_at.pdf', 'Alterations Lodged', '2024-01-31 20:40:14', '2024-01-31 20:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `reg_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vat_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `business_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_address2` varchar(500) DEFAULT NULL,
  `business_address3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_address_postal_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postal_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postal_province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postal_code2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `postal_code3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tel_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tel_number1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` int(3) DEFAULT 1,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deleted_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `reg_number`, `vat_number`, `company_type`, `business_address`, `business_address2`, `business_address3`, `business_province`, `business_address_postal_code`, `postal_address`, `postal_province`, `postal_code`, `postal_code2`, `postal_code3`, `website`, `email`, `email1`, `email2`, `tel_number`, `tel_number1`, `active`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Twerp', NULL, NULL, 'Association', '2 Ewing St, Rynfield', '34TT', '34 TT', 'Eastern Cape', '1501', '2 Ewing St, Rynfield', 'Eastern Cape', '1501', '34TT', '34 TT', NULL, 'muzi@twerp.co.za', 'muzi@twerp.co.za', 'muzi@twerp.co.za', '0792975182', '0680424068', 1, 'e255c5b4d34c6a8ecc1aac0b1e56c5573262dc50', NULL, '2024-01-30 21:11:23.000000', '2024-01-30 21:11:23.000000'),
(2, 'MrnLabs', '12345', '12222222', 'Association', 'plot 136 William Nicol Drive  Diepsloot Ext 10', NULL, NULL, 'Gauteng', '2169', 'plot 136 William Nicol Drive  Diepsloot Ext 10', 'Gauteng', '2169', NULL, NULL, NULL, 'mazisimsebele18@gmail.com', 'mazisimsebele18@gmail.com', 'mazisimsebele18@gmail.com', '0680424068', '0680424068', 1, '33141446c2c779612ccb62ccbb9d22119722b13f', NULL, '2024-01-30 21:44:16.000000', '2024-01-30 21:44:16.000000');

-- --------------------------------------------------------

--
-- Table structure for table `company_documents`
--

CREATE TABLE `company_documents` (
  `id` bigint(20) NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `uploaded_by` bigint(20) DEFAULT NULL,
  `document_file` varchar(500) DEFAULT NULL,
  `document_name` varchar(500) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `document_type` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_people`
--

CREATE TABLE `company_people` (
  `id` bigint(20) NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `people_id` bigint(20) NOT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_user`
--

CREATE TABLE `company_user` (
  `id` bigint(20) NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `middle_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `business_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mobile_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `duplicate_originals`
--

CREATE TABLE `duplicate_originals` (
  `id` bigint(20) NOT NULL,
  `licence_id` bigint(20) NOT NULL,
  `year` varchar(11) NOT NULL,
  `status` varchar(11) DEFAULT NULL,
  `paid_at` date DEFAULT NULL,
  `liquor_board_at` date DEFAULT NULL,
  `lodged_at` date DEFAULT NULL,
  `issued_at` date DEFAULT NULL,
  `delivered_at` date DEFAULT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duplicate_originals`
--

INSERT INTO `duplicate_originals` (`id`, `licence_id`, `year`, `status`, `paid_at`, `liquor_board_at`, `lodged_at`, `issued_at`, `delivered_at`, `slug`) VALUES
(2, 3, '2023', '400', '2024-01-26', '2024-01-20', NULL, NULL, NULL, '59bf99fdbefd0dea06c8307e8d16f35cdd91ea89');

-- --------------------------------------------------------

--
-- Table structure for table `duplicate_original_docs`
--

CREATE TABLE `duplicate_original_docs` (
  `id` bigint(20) NOT NULL,
  `duplicate_original_id` bigint(20) NOT NULL,
  `path` varchar(255) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `doc_type` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duplicate_original_docs`
--

INSERT INTO `duplicate_original_docs` (`id`, `duplicate_original_id`, `path`, `document_name`, `doc_type`, `updated_at`, `created_at`) VALUES
(8, 2, 'mrnlabs/335...null_issued_at.pdf', 'null_issued_at.pdf', 'Client Quoted', '2024-01-31 17:32:00', '2024-01-31 17:32:00');

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` bigint(20) NOT NULL,
  `trading_name` varchar(2550) DEFAULT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint(20) NOT NULL,
  `stage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `model_slug` varchar(255) NOT NULL,
  `parent_licence_slug` varchar(255) NOT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `connection` varchar(0) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `queue` varchar(0) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `payload` varchar(0) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `exception` varchar(0) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `import_file`
--

CREATE TABLE `import_file` (
  `ID` bigint(20) DEFAULT NULL,
  `n_id` varchar(255) DEFAULT NULL,
  `one` varchar(255) DEFAULT NULL,
  `two` varchar(255) DEFAULT NULL,
  `three` varchar(255) DEFAULT NULL,
  `four` varchar(255) DEFAULT NULL,
  `five` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `import_file`
--

INSERT INTO `import_file` (`ID`, `n_id`, `one`, `two`, `three`, `four`, `five`) VALUES
(47, 'licence-2962/14533-Majestic Restaurant Sect.104 Proof of Lodgement.pdf', 'licence-2962/14533-Majestic Restaurant Sect.104 Proof of Lodgement.pdf', 'Transfer Lodged', NULL, NULL, 'slug'),
(55, 'licence-4099/35149-GAU100230 Tsa Afrika Restaurant T.linked.pdf', 'licence-4099/35149-GAU100230 Tsa Afrika Restaurant T.linked.pdf', 'Transfer Lodged', NULL, NULL, 'slug'),
(108, 'licence-3508/36887-GAU300134 Fun Company Emerald Casino T.pdf', 'licence-3508/36887-GAU300134 Fun Company Emerald Casino T.pdf', 'Transfer Lodged', NULL, NULL, 'slug'),
(67, 'licence-3991/36905-GLB7000007569 Hallmark House Hotel T.pdf', 'licence-3991/36905-GLB7000007569 Hallmark House Hotel T.pdf', 'Transfer Lodged', NULL, NULL, 'slug'),
(110, 'licence-3643/37087-GLB7000003158 Standard Bank Foof Emperial - Trf.pdf', 'licence-3643/37087-GLB7000003158 Standard Bank Foof Emperial - Trf.pdf', 'Transfer Lodged', NULL, NULL, 'slug'),
(111, 'licence-1400/37089-NCP006430 Liquorzone De Aar T.pdf', 'licence-1400/37089-NCP006430 Liquorzone De Aar T.pdf', 'Transfer Lodged', NULL, NULL, 'slug'),
(93, 'licence-3496/37170-GLB7000005116 Cafe Tirana Trf.pdf', 'licence-3496/37170-GLB7000005116 Cafe Tirana Trf.pdf', 'Transfer Lodged', NULL, NULL, 'slug'),
(115, 'licence-4385/37281-NWP0006525 All Liquors Store T.pdf', 'licence-4385/37281-NWP0006525 All Liquors Store T.pdf', 'Transfer Lodged', NULL, NULL, 'slug'),
(85, 'licence-4240/37283-GLB7000008661 Delforno Parkmore T.pdf', 'licence-4240/37283-GLB7000008661 Delforno Parkmore T.pdf', 'Transfer Lodged', NULL, NULL, 'slug'),
(116, 'licence-4237/37323-NTV024152 Woolworths Tzaneen Letaba T.pdf', 'licence-4237/37323-NTV024152 Woolworths Tzaneen Letaba T.pdf', 'Transfer Lodged', NULL, NULL, 'slug');

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `severity` enum('High','Moderate','Low') NOT NULL,
  `status` enum('Pending','Resolved','Declined') NOT NULL DEFAULT 'Pending',
  `url` varchar(500) DEFAULT NULL,
  `body` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`id`, `user_id`, `severity`, `status`, `url`, `body`, `slug`, `created_at`, `updated_at`) VALUES
(3, 1, 'Moderate', 'Resolved', 'https://goverify.co.za/create-issue', '<p>Temporal Licence Spelling errors</p>', '9ea6f83ec54d79674b165a274e9373d975975cd8', '2023-02-28 09:23:42', '2023-02-28 09:23:42');

-- --------------------------------------------------------

--
-- Table structure for table `licences`
--

CREATE TABLE `licences` (
  `id` bigint(20) NOT NULL,
  `company_id` bigint(20) DEFAULT NULL,
  `people_id` bigint(20) DEFAULT NULL,
  `licence_type_id` bigint(20) NOT NULL,
  `type` enum('retail','wholesale') NOT NULL,
  `trading_name` varchar(255) NOT NULL,
  `licence_number` varchar(255) DEFAULT NULL,
  `old_licence_number` varchar(255) DEFAULT NULL,
  `client_number` varchar(500) DEFAULT NULL,
  `licence_date` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address2` varchar(500) DEFAULT NULL,
  `address3` varchar(500) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `belongs_to` varchar(255) DEFAULT NULL,
  `reg_number` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `board_region` varchar(255) DEFAULT NULL,
  `import_export` enum('Import','Export') DEFAULT NULL,
  `is_new_app` varchar(255) DEFAULT NULL,
  `status` varchar(5) DEFAULT NULL,
  `is_licence_active` tinyint(1) DEFAULT 1,
  `licence_issued_at` varchar(30) DEFAULT NULL,
  `latest_renewal` varchar(255) DEFAULT NULL,
  `renewal_amount` varchar(500) DEFAULT NULL,
  `merged_document` varchar(255) DEFAULT NULL,
  `client_quoted_invoice_number` varchar(255) DEFAULT NULL,
  `is_client_invoiced` varchar(10) DEFAULT NULL,
  `is_application_logded_doc_uploaded` varchar(255) DEFAULT NULL,
  `is_finalisation_doc_uploaded` varchar(10) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `deleted_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `licences`
--

INSERT INTO `licences` (`id`, `company_id`, `people_id`, `licence_type_id`, `type`, `trading_name`, `licence_number`, `old_licence_number`, `client_number`, `licence_date`, `address`, `address2`, `address3`, `province`, `postal_code`, `belongs_to`, `reg_number`, `id_number`, `board_region`, `import_export`, `is_new_app`, `status`, `is_licence_active`, `licence_issued_at`, `latest_renewal`, `renewal_amount`, `merged_document`, `client_quoted_invoice_number`, `is_client_invoiced`, `is_application_logded_doc_uploaded`, `is_finalisation_doc_uploaded`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 'retail', 'Existing Retail', '344444444', NULL, NULL, NULL, 'Plot 136 William Nicol Drive  Benoni Ext 10', '5656565', NULL, 'Gauteng', '2169', 'Company', NULL, NULL, 'Eastern Cape', NULL, NULL, '300', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'c6e3066e6b1804aa971ebb714c41f19ecfdcb9a2', NULL, '2024-01-30 21:13:21.000000', '2024-02-04 14:23:40.000000'),
(2, NULL, 1, 102, 'wholesale', 'NewApp Wholesale', '8989', NULL, NULL, '2024-02-01', 'plot 136 William Nicol Drive  Diepsloot Ext 10', 'wwwww', 'weewewew', 'Gauteng', NULL, 'Individual', NULL, NULL, 'Free State', NULL, '1', '200', 1, '2024-02-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1c3023ec39fbcd1957d5e68e8c5003d0aeb2fbf5', NULL, '2024-01-30 22:18:01.000000', '2024-02-01 22:19:52.000000'),
(3, 1, NULL, 102, 'wholesale', 'ExisitingWholesale', NULL, NULL, NULL, '2024-03-02', 'plot 136 William Nicol Drive  Diepsloot Ext 10', NULL, NULL, 'Free State', NULL, 'Company', NULL, NULL, NULL, 'Import', '0', '1100', 1, NULL, NULL, NULL, 'Original-Licence3', NULL, NULL, NULL, NULL, 'aca229c5ca63d1e8d8a3290708f113b679e0247b', NULL, '2024-01-30 22:20:13.000000', '2024-02-04 14:26:42.000000');

-- --------------------------------------------------------

--
-- Table structure for table `licence_dates`
--

CREATE TABLE `licence_dates` (
  `id` bigint(20) NOT NULL,
  `licence_id` bigint(20) NOT NULL,
  `stage` varchar(200) NOT NULL,
  `dated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `licence_dates`
--

INSERT INTO `licence_dates` (`id`, `licence_id`, `stage`, `dated_at`) VALUES
(28, 2, 'Client Paid', '2024-02-03'),
(29, 1, 'Payment To The Liquor Board', '2024-02-17'),
(30, 2, 'Scanned Application', '2024-02-03'),
(31, 1, 'Initial Inspection', '2024-02-23');

-- --------------------------------------------------------

--
-- Table structure for table `licence_documents`
--

CREATE TABLE `licence_documents` (
  `id` int(11) NOT NULL,
  `licence_id` bigint(20) NOT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `document_type` varchar(255) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `licence_documents`
--

INSERT INTO `licence_documents` (`id`, `licence_id`, `document_name`, `document_file`, `document_type`, `num`, `created_at`, `updated_at`) VALUES
(5, 3, 'null_issued_at.pdf', 'mrnlabs/edc...null_issued_at.pdf', 'Zoning Certificate', NULL, '2024-01-30 22:40:07.000000', '2024-01-30 22:40:07.000000'),
(6, 3, 'dummy.pdf', 'mrnlabs/d44...dummy.pdf', 'NLA 6 Proposed', NULL, '2024-02-03 19:21:57.000000', '2024-02-03 19:21:57.000000'),
(7, 3, 'dummy.pdf', 'mrnlabs/683...dummy.pdf', 'NLA 7 Submitted', NULL, '2024-02-03 19:22:34.000000', '2024-02-03 19:22:34.000000'),
(8, 3, 'dummy.pdf', 'mrnlabs/a5c...dummy.pdf', 'NLA 8 Issued', NULL, '2024-02-03 19:22:45.000000', '2024-02-03 19:22:45.000000'),
(11, 3, 'dummy.pdf', 'mrnlabs/dc7...dummy.pdf', 'Activation Fee', NULL, '2024-02-03 19:28:31.000000', '2024-02-03 19:28:31.000000'),
(15, 3, 'dummy.pdf', 'mrnlabs/db0...dummy.pdf', 'NLA 9 Issued', NULL, '2024-02-03 19:40:50.000000', '2024-02-03 19:40:50.000000'),
(17, 1, 'dummy.pdf', 'mrnlabs/374...dummy.pdf', 'GLB Application Forms', 1, '2024-02-03 19:56:54.000000', '2024-02-03 19:56:54.000000'),
(18, 1, 'dummy.pdf', 'mrnlabs/055...dummy.pdf', 'Payment To The Liquor Board', NULL, '2024-02-04 14:20:02.000000', '2024-02-04 14:20:02.000000'),
(19, 3, 'dummy.pdf', 'mrnlabs/bf2...dummy.pdf', 'LAA Certificate', NULL, '2024-02-04 14:25:24.000000', '2024-02-04 14:25:24.000000'),
(21, 3, 'dummy.pdf', 'mrnlabs/23a...dummy.pdf', 'Proof of rightful occupation', NULL, '2024-02-04 14:25:32.000000', '2024-02-04 14:25:32.000000'),
(23, 3, 'dummy.pdf', 'mrnlabs/c73...dummy.pdf', 'Signed POA & RES', NULL, '2024-02-04 14:25:45.000000', '2024-02-04 14:25:45.000000'),
(24, 3, 'Original-Licence3', 'mrnlabs/Original-Licence3.pdf', 'Original-Licence', NULL, '2024-02-04 14:25:45.000000', '2024-02-04 14:25:45.000000'),
(25, 3, 'dummy.pdf', 'mrnlabs/c5f...dummy.pdf', 'Signed POA & RES', NULL, '2024-02-04 14:26:42.000000', '2024-02-04 14:26:42.000000'),
(26, 3, 'Original-Licence3', 'mrnlabs/Original-Licence3.pdf', 'Original-Licence', NULL, '2024-02-04 14:26:42.000000', '2024-02-04 14:26:42.000000'),
(27, 3, 'dummy.pdf', 'mrnlabs/34e...dummy.pdf', 'Signed POA & RES', NULL, '2024-02-04 14:27:58.000000', '2024-02-04 14:27:58.000000'),
(28, 3, 'dummy.pdf', 'mrnlabs/9c9...dummy.pdf', 'Import/Export', NULL, '2024-02-04 14:48:53.000000', '2024-02-04 14:48:53.000000'),
(29, 3, 'dummy.pdf', 'mrnlabs/952...dummy.pdf', 'Product Analysis Certificate', 19, '2024-02-04 14:49:02.000000', '2024-02-04 14:49:02.000000'),
(30, 3, 'dummy.pdf', 'mrnlabs/b21...dummy.pdf', 'Photographs', NULL, '2024-02-04 14:49:11.000000', '2024-02-04 14:49:11.000000'),
(31, 3, 'dummy.pdf', 'mrnlabs/f74...dummy.pdf', 'Maps/Plans', NULL, '2024-02-04 14:49:24.000000', '2024-02-04 14:49:24.000000'),
(32, 3, 'dummy.pdf', 'mrnlabs/233...dummy.pdf', 'NLA Application Form', 21, '2024-02-04 14:49:33.000000', '2024-02-04 14:49:33.000000'),
(33, 3, 'dummy.pdf', 'mrnlabs/a61...dummy.pdf', 'Signed NLA 1 Form', 22, '2024-02-04 14:49:46.000000', '2024-02-04 14:49:46.000000'),
(34, 3, 'dummy.pdf', 'mrnlabs/d8f...dummy.pdf', 'BEE Certificate/Affidavit', NULL, '2024-02-04 14:54:55.000000', '2024-02-04 14:54:55.000000'),
(35, 3, 'dummy.pdf', 'mrnlabs/768...dummy.pdf', 'Tax Clearance', NULL, '2024-02-04 14:55:04.000000', '2024-02-04 14:55:04.000000'),
(36, 3, 'dummy.pdf', 'mrnlabs/106...dummy.pdf', 'ID Documents', NULL, '2024-02-04 14:55:55.000000', '2024-02-04 14:55:55.000000'),
(37, 3, 'dummy.pdf', 'mrnlabs/a51...dummy.pdf', 'Company Documents', 4, '2024-02-04 14:57:05.000000', '2024-02-04 14:57:05.000000');

-- --------------------------------------------------------

--
-- Table structure for table `licence_renewals`
--

CREATE TABLE `licence_renewals` (
  `id` bigint(20) NOT NULL,
  `licence_id` bigint(20) NOT NULL,
  `date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `client_paid_at` date DEFAULT NULL,
  `renewal_issued_at` date DEFAULT NULL,
  `renewal_delivered_at` date DEFAULT NULL,
  `payment_to_liquor_board_at` date DEFAULT NULL,
  `client_invoiced_at` date DEFAULT NULL,
  `is_quote_sent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `licence_renewals`
--

INSERT INTO `licence_renewals` (`id`, `licence_id`, `date`, `status`, `client_paid_at`, `renewal_issued_at`, `renewal_delivered_at`, `payment_to_liquor_board_at`, `client_invoiced_at`, `is_quote_sent`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, '2023', '500', NULL, '2024-02-08', '2024-02-02', NULL, NULL, NULL, '2f1e2983355731c093f25f30be60018fd0986e28', NULL, '2024-01-31 22:46:33.000000', '2024-02-04 16:40:11.000000'),
(2, 1, '2023', '600', NULL, NULL, NULL, NULL, NULL, NULL, 'fa2e244b44c502c0e9caff21ff225a1a01e69d18', NULL, '2024-01-31 22:59:38.000000', '2024-01-31 22:59:41.000000');

-- --------------------------------------------------------

--
-- Table structure for table `licence_transfers`
--

CREATE TABLE `licence_transfers` (
  `id` bigint(20) NOT NULL,
  `licence_id` bigint(20) NOT NULL,
  `company_id` bigint(20) DEFAULT NULL,
  `old_people_id` bigint(20) DEFAULT NULL,
  `people_id` bigint(20) DEFAULT NULL,
  `old_company_id` int(11) DEFAULT NULL,
  `transfered_from` varchar(500) DEFAULT NULL,
  `transfered_to` varchar(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lodged_at` date DEFAULT NULL,
  `activation_fee_paid_at` date DEFAULT NULL,
  `issued_at` date DEFAULT NULL,
  `delivered_at` date DEFAULT NULL,
  `payment_to_liquor_board_at` date DEFAULT NULL,
  `merged_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `licence_transfers`
--

INSERT INTO `licence_transfers` (`id`, `licence_id`, `company_id`, `old_people_id`, `people_id`, `old_company_id`, `transfered_from`, `transfered_to`, `date`, `status`, `lodged_at`, `activation_fee_paid_at`, `issued_at`, `delivered_at`, `payment_to_liquor_board_at`, `merged_document`, `slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 1, NULL, NULL, 2, 'Company', 'Company', NULL, '400', NULL, NULL, NULL, '2024-01-26', NULL, NULL, 'd513d530dc6603a76be0383c4e09ca10cd981e3e', '2024-01-31 20:06:32.000000', '2024-01-31 20:49:37.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `licence_types`
--

CREATE TABLE `licence_types` (
  `id` bigint(20) NOT NULL,
  `licence_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `province` enum('Mpumalanga','Western Cape','KwaZulu-Natal','Gauteng','Free State','North West','Eastern Cape','Northern Cape','Limpopo','Wholesale','null') DEFAULT 'null',
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(101, 'Micro-Manufacturing Liquor Licence', 'Western Cape', '2023-11-11 22:56:22.000000', '2023-11-11 22:56:22.000000'),
(102, 'Distribution and Manufacturing Liquor Licence', 'Wholesale', '2024-01-16 22:07:23.000000', '2024-01-16 22:07:23.000000'),
(103, 'Distribution Liquor Licence', 'Wholesale', '2024-01-16 22:08:17.000000', '2024-01-16 22:08:17.000000'),
(104, 'Manufacturing Liquor Licence', 'Wholesale', '2024-01-16 22:08:17.000000', '2024-01-16 22:08:17.000000');

-- --------------------------------------------------------

--
-- Table structure for table `liquor_board_requests`
--

CREATE TABLE `liquor_board_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` varchar(255) NOT NULL,
  `body` varchar(5000) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `liquor_board_requests`
--

INSERT INTO `liquor_board_requests` (`id`, `model_type`, `model_id`, `body`, `created_at`, `updated_at`) VALUES
(1, 'Licence Renewal', '1', 'test me tooggg', '2022-12-02 07:01:23', '2022-12-02 10:53:03'),
(2, 'Licence', '20002', 'tax', '2022-12-07 11:09:57', '2022-12-07 11:09:57'),
(3, 'Licence Transfer', '123', 'test me', '2023-01-19 11:09:05', '2023-01-19 11:09:05');

-- --------------------------------------------------------

--
-- Table structure for table `merged_documents`
--

CREATE TABLE `merged_documents` (
  `id` bigint(20) NOT NULL,
  `nomination_id` bigint(20) NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2013_05_13_085350_create_companies_table', 1),
(2, '2014_10_12_000000_create_people_table', 1),
(3, '2014_10_12_000000_create_users_table', 1),
(4, '2014_10_12_100000_create_password_resets_table', 1),
(5, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(7, '2019_12_14_000002_create_licence_types_table', 1),
(8, '2022_05_13_091828_create_licences_table', 1),
(9, '2022_05_13_095332_create_company_user', 1),
(10, '2022_05_16_121437_create_company_documents_table', 1),
(11, '2022_05_17_154400_create_permission_tables', 1),
(12, '2022_05_18_145530_create_licence_documents_table', 1),
(13, '2022_05_20_142026_create_contacts_table', 1),
(14, '2022_05_23_115344_create_nominations_table', 1),
(15, '2022_05_24_122235_create_nomination_people_table', 1),
(16, '2022_05_28_113414_create_licence_transfers_table', 1),
(17, '2022_05_30_131637_create_licence_renewals_table', 1),
(18, '2022_06_02_183041_create_alterations_table', 1),
(19, '2022_06_07_111509_create_tasks_table', 1),
(20, '2022_06_15_142343_create_consultants_table', 1),
(21, '2022_06_15_144201_create_company_consultant_table', 1),
(22, '2022_06_15_144202_create_temporaly_licences_table', 1),
(23, '2022_06_29_095204_create_company_people_table', 1),
(24, '2022_06_29_190509_create_people_documents_table', 1),
(25, '2022_06_30_004545_create_renewal_documents_table', 1),
(26, '2022_06_30_110102_create_nomination_documents_table', 1),
(27, '2022_07_14_123035_create_merged_documents_table', 1),
(28, '2022_07_30_125634_create_transfer_documents_table', 1),
(29, '2022_08_04_145206_create_temporal_licence_documents_table', 1),
(30, '2022_09_09_104012_create_licence_renewal_exports_table', 1),
(31, '2022_09_15_114505_create_emails_table', 1),
(32, '2022_10_10_113737_create_liquor_board_requests_table', 1),
(33, '2022_10_14_175701_create_transfer_exports_table', 1),
(34, '2022_10_15_221459_create_nomination_exports_table', 1),
(35, '2022_10_19_142001_create_new_app_exports_table', 1),
(36, '2022_10_20_003127_create_temporal_licence_exports_table', 1),
(37, '2022_10_23_210151_create_alteration_exports_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `model_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 5),
(1, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 12),
(2, 'App\\Models\\User', 13),
(2, 'App\\Models\\User', 16),
(2, 'App\\Models\\User', 20),
(2, 'App\\Models\\User', 22),
(2, 'App\\Models\\User', 28),
(3, 'App\\Models\\User', 14),
(3, 'App\\Models\\User', 30),
(3, 'App\\Models\\User', 32),
(3, 'App\\Models\\User', 33),
(3, 'App\\Models\\User', 34),
(3, 'App\\Models\\User', 36),
(3, 'App\\Models\\User', 38);

-- --------------------------------------------------------

--
-- Table structure for table `nominations`
--

CREATE TABLE `nominations` (
  `id` bigint(20) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `licence_id` bigint(20) NOT NULL,
  `status` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `client_paid_date` date DEFAULT NULL,
  `nomination_lodged_at` date DEFAULT NULL,
  `nomination_issued_at` date DEFAULT NULL,
  `nomination_delivered_at` date DEFAULT NULL,
  `payment_to_liquor_board_at` date DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `nominations`
--

INSERT INTO `nominations` (`id`, `year`, `document`, `licence_id`, `status`, `client_paid_date`, `nomination_lodged_at`, `nomination_issued_at`, `nomination_delivered_at`, `payment_to_liquor_board_at`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2023, NULL, 3, '200', NULL, '2024-01-13', NULL, NULL, NULL, 'f2600e2af75e74ae784550d90f3c507b291c0a5f', NULL, '2024-01-31 20:54:27.000000', '2024-02-04 16:18:54.000000');

-- --------------------------------------------------------

--
-- Table structure for table `nomination_documents`
--

CREATE TABLE `nomination_documents` (
  `id` bigint(20) NOT NULL,
  `nomination_id` bigint(20) NOT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `doc_type` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nomination_documents`
--

INSERT INTO `nomination_documents` (`id`, `nomination_id`, `document_name`, `date`, `document`, `doc_type`, `path`, `created_at`, `updated_at`) VALUES
(3, 1, 'null_issued_at.pdf', NULL, 'mrnlabs/85d...null_issued_at.pdf', 'Police Clearances', 'mrnlabs/85d...null_issued_at.pdf', '2024-01-31 21:56:22.000000', '2024-01-31 21:56:22.000000'),
(4, 1, 'null_issued_at.pdf', NULL, 'mrnlabs/5d0...null_issued_at.pdf', 'Nomination Forms', 'mrnlabs/5d0...null_issued_at.pdf', '2024-01-31 21:56:37.000000', '2024-01-31 21:56:37.000000'),
(5, 1, 'licences (3).pdf', NULL, 'mrnlabs/771...licences_(3).pdf', 'ID Document', 'mrnlabs/771...licences_(3).pdf', '2024-01-31 21:56:41.000000', '2024-01-31 21:56:41.000000'),
(6, 1, 'licences (2).pdf', NULL, 'mrnlabs/eee...licences_(2).pdf', 'Latest Renewal/Licence', 'mrnlabs/eee...licences_(2).pdf', '2024-01-31 21:56:45.000000', '2024-01-31 21:56:45.000000'),
(7, 1, 'licences (2).pdf', NULL, 'mrnlabs/995...licences_(2).pdf', 'Power of Attorney', 'mrnlabs/995...licences_(2).pdf', '2024-01-31 21:56:49.000000', '2024-01-31 21:56:49.000000'),
(8, 1, 'licences (2).pdf', NULL, 'mrnlabs/00f...licences_(2).pdf', 'Payment To The Liquor Board', 'mrnlabs/00f...licences_(2).pdf', '2024-01-31 21:57:14.000000', '2024-01-31 21:57:14.000000');

-- --------------------------------------------------------

--
-- Table structure for table `nomination_people`
--

CREATE TABLE `nomination_people` (
  `id` bigint(20) NOT NULL,
  `nomination_id` bigint(20) NOT NULL,
  `people_id` bigint(20) NOT NULL,
  `relationship` varchar(255) DEFAULT NULL,
  `terminated_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nomination_people`
--

INSERT INTO `nomination_people` (`id`, `nomination_id`, `people_id`, `relationship`, `terminated_at`, `created_at`, `updated_at`) VALUES
(2, 1, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `id` bigint(20) NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `active` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '1',
  `date_of_birth` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_or_passport` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_address_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_address_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cell_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `passport_valid_until` date DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `full_name`, `active`, `date_of_birth`, `id_number`, `id_or_passport`, `email_address_1`, `email_address_2`, `cell_number`, `telephone`, `passport_valid_until`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Mazisi Msebele', '1', '01-01-1970', NULL, '1992090908888', 'mazisimsebele18@gmail.com', 'mazisimsebele18@gmail.com', '0680424068', '09876555444', NULL, 'a4ca62866ccc464f763c7e381808ea32b07178a5', NULL, '2024-01-30 21:42:55.000000', '2024-02-01 21:58:55.000000');

-- --------------------------------------------------------

--
-- Table structure for table `people_documents`
--

CREATE TABLE `people_documents` (
  `id` bigint(20) NOT NULL,
  `people_id` bigint(20) NOT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `expiry_date` varchar(255) DEFAULT NULL,
  `doc_type` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `people_documents`
--

INSERT INTO `people_documents` (`id`, `people_id`, `document_name`, `document`, `expiry_date`, `doc_type`, `path`, `slug`, `created_at`, `updated_at`) VALUES
(4, 1, '5905...dummy.pdf', '5905...dummy.pdf', NULL, 'ID Document', 'mrnlabs/5905...dummy.pdf', '51601f9e9cc6ad887ab817dd5f95c386b0582314', '2024-02-03 19:26:41.000000', '2024-02-03 19:26:41.000000');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tokenable_id` bigint(20) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `abilities` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_used_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `renewal_documents`
--

CREATE TABLE `renewal_documents` (
  `id` bigint(20) NOT NULL,
  `licence_renewal_id` bigint(20) NOT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `doc_type` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `renewal_documents`
--

INSERT INTO `renewal_documents` (`id`, `licence_renewal_id`, `document_name`, `date`, `document`, `doc_type`, `path`, `created_at`, `updated_at`) VALUES
(1, 18, 'Alexander Shvets Design Patterns Explained Simply.pdf', NULL, 'mrnlabs/e91...Alexander_Shvets_Design_Patterns_Explained_Simply.pdf', 'Client Quoted', 'mrnlabs/e91...Alexander_Shvets_Design_Patterns_Explained_Simply.pdf', '2023-10-07 18:54:20.000000', '2023-10-07 18:54:20.000000'),
(2, 10010635, 'dummy.pdf', NULL, 'mrnlabs/c26...dummy.pdf', 'Client Invoiced', 'mrnlabs/c26...dummy.pdf', '2024-01-22 21:29:13.000000', '2024-01-22 21:29:13.000000'),
(3, 1, 'null_licence_date.pdf', NULL, 'mrnlabs/919...null_licence_date.pdf', 'Client Quoted', 'mrnlabs/919...null_licence_date.pdf', '2024-01-31 22:57:25.000000', '2024-01-31 22:57:25.000000');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'company-admin', 'web', '2022-10-05 12:43:56.000000', '2022-10-05 12:43:56.000000'),
(2, 'slowtow-admin', 'web', '2022-10-27 12:48:27.000000', '2022-10-05 12:43:56.000000'),
(3, 'slowtow-user', 'web', '2022-11-09 09:40:48.000000', '2022-11-09 09:40:48.000000'),
(4, 'SuperAdmin', 'web', '2023-01-10 08:27:56.000000', '2023-01-10 08:27:56.000000'),
(5, 'Admin', 'web', '2023-01-10 08:52:19.000000', '2023-01-10 08:52:19.000000'),
(6, 'Test', 'web', '2023-01-16 15:07:27.000000', '2023-01-16 15:07:27.000000');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `model_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime(6) DEFAULT current_timestamp(6),
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `model_type`, `model_id`, `date`, `body`, `created_at`, `updated_at`) VALUES
(2, 1, 'Duplicate-Originals', '2', '2024-01-31 19:12:58.999749', 'Hey', '2024-01-31 19:12:58.000000', '2024-01-31 19:12:58.000000'),
(3, 1, 'Transfer', '1', '2024-01-31 20:49:49.158653', 'Test', '2024-01-31 20:49:49.000000', '2024-01-31 20:49:49.000000'),
(4, 1, 'Alteration', '1', '2024-01-31 22:31:16.304870', 'Hey', '2024-01-31 22:31:16.000000', '2024-01-31 22:31:16.000000'),
(5, 1, 'Licence Renewal', '1', '2024-01-31 22:57:34.392350', 'Test', '2024-01-31 22:57:34.000000', '2024-01-31 22:57:34.000000'),
(6, 1, 'Temporal Licence', '1', '2024-02-01 19:34:21.013765', 'Hey', '2024-02-01 19:34:21.000000', '2024-02-01 19:34:21.000000');

-- --------------------------------------------------------

--
-- Table structure for table `temporal_licences`
--

CREATE TABLE `temporal_licences` (
  `id` bigint(20) NOT NULL,
  `belongs_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_id` bigint(20) DEFAULT NULL,
  `people_id` bigint(20) DEFAULT NULL,
  `event_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `liquor_licence_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` int(3) NOT NULL DEFAULT 1,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `client_paid_at` date DEFAULT NULL,
  `latest_lodgment_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `application_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reg_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_to_liquor_board_at` date DEFAULT NULL,
  `logded_at` date DEFAULT NULL,
  `issued_at` date DEFAULT NULL,
  `delivered_at` date DEFAULT NULL,
  `merged_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `temporal_licences`
--

INSERT INTO `temporal_licences` (`id`, `belongs_to`, `company_id`, `people_id`, `event_name`, `liquor_licence_number`, `active`, `status`, `start_date`, `end_date`, `client_paid_at`, `latest_lodgment_date`, `address`, `application_type`, `reg_number`, `id_number`, `payment_to_liquor_board_at`, `logded_at`, `issued_at`, `delivered_at`, `merged_document`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Company', 1, NULL, 'TestTemp', NULL, 1, '900', '2024-02-10', '2024-02-17', '2024-02-24', '1/27/2024', 'Ekurhuleni', 'Off-Consumption', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a3ae8503d13161700156f7c6658c624eb610807d', NULL, '2024-02-01 19:12:36.000000', '2024-02-01 19:48:29.000000');

-- --------------------------------------------------------

--
-- Table structure for table `temporal_licence_documents`
--

CREATE TABLE `temporal_licence_documents` (
  `id` bigint(20) NOT NULL,
  `temporal_licence_id` bigint(20) NOT NULL,
  `document_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `doc_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `belongs_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `temporal_licence_documents`
--

INSERT INTO `temporal_licence_documents` (`id`, `temporal_licence_id`, `document_name`, `document`, `doc_type`, `belongs_to`, `num`, `slug`, `created_at`, `updated_at`) VALUES
(14, 1, 'licences (3).pdf', 'mrnlabs/4af...licences_(3).pdf', 'Client Invoiced', NULL, NULL, '4af4012092512d4e063a9af9554b73c3fac47f7b', '2024-02-01 19:27:40.000000', '2024-02-01 19:27:40.000000'),
(15, 1, 'null_issued_at.pdf', 'mrnlabs/090...null_issued_at.pdf', 'CIPC Certificate', 'Company', 600, '0908d3223d21748c864fb6f06a0701d18ab3a544', '2024-02-01 19:29:28.000000', '2024-02-01 19:29:28.000000'),
(16, 1, 'null_issued_at.pdf', 'mrnlabs/0c5...null_issued_at.pdf', 'Annexure B', 'Company', 400, '0c5acc68738195373e47654cb25b104694953378', '2024-02-01 19:30:57.000000', '2024-02-01 19:30:57.000000'),
(17, 1, 'null_issued_at.pdf', 'mrnlabs/515...null_issued_at.pdf', 'POA And RES', 'Company', 300, '515abac378756368a416f9698da5b42764608289', '2024-02-01 19:31:02.000000', '2024-02-01 19:31:02.000000'),
(18, 1, 'null_issued_at.pdf', 'mrnlabs/6e0...null_issued_at.pdf', 'Application Form', 'Company', 100, '6e0d705e2fe9b02ad993c2a14b4d3100a0dae150', '2024-02-01 19:31:06.000000', '2024-02-01 19:31:06.000000'),
(19, 1, 'null_licence_date.pdf', 'mrnlabs/aee...null_licence_date.pdf', 'Plan/Maps', 'Company', 1200, 'aeea82821ed880073cd863ccc7c677d4b1b75d69', '2024-02-01 19:31:12.000000', '2024-02-01 19:31:12.000000'),
(20, 1, 'null_licence_date.pdf', 'mrnlabs/63e...null_licence_date.pdf', 'Security Letter', 'Company', 1000, '63ec5302792055874e320e44b52616b1945b51ee', '2024-02-01 19:31:16.000000', '2024-02-01 19:31:16.000000'),
(21, 1, 'licences (2).pdf', 'mrnlabs/aca...licences_(2).pdf', 'Landlord Letter', 'Company', 900, 'acac8a1d9e7bd6515e6c730097ddb20b3777b506', '2024-02-01 19:31:21.000000', '2024-02-01 19:31:21.000000'),
(22, 1, 'null_licence_date.pdf', 'mrnlabs/6d8...null_licence_date.pdf', 'Representations', 'Company', 800, '6d85f23a6f1256f856c70595a7ae68565ff85e2f', '2024-02-01 19:31:45.000000', '2024-02-01 19:31:45.000000'),
(23, 1, 'null_issued_at.pdf', 'mrnlabs/198...null_issued_at.pdf', 'ID Document', 'Company', 700, '198e91b242f3057469bfb333c3439011fa945ebb', '2024-02-01 19:31:50.000000', '2024-02-01 19:31:50.000000'),
(24, 1, 'licences (2).pdf', 'mrnlabs/de8...licences_(2).pdf', 'Advert/Blurb', 'Company', 1100, 'de82450c00e792c542de9b02386285f8bed233e4', '2024-02-01 19:31:54.000000', '2024-02-01 19:31:54.000000'),
(27, 1, 'null_licence_date.pdf', 'mrnlabs/0ce...null_licence_date.pdf', 'Payment To The Liquor Board', NULL, NULL, '0cec07a12658fda2a5d4e801e8a8f07c11fedea4', '2024-02-01 19:48:03.000000', '2024-02-01 19:48:03.000000');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_documents`
--

CREATE TABLE `transfer_documents` (
  `id` bigint(20) NOT NULL,
  `licence_transfer_id` bigint(20) NOT NULL,
  `document_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `doc_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `belongs_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `transfer_documents`
--

INSERT INTO `transfer_documents` (`id`, `licence_transfer_id`, `document_name`, `document`, `doc_type`, `belongs_to`, `num`, `slug`, `created_at`, `updated_at`) VALUES
(32, 1, 'null_issued_at.pdf', 'mrnlabs/b8f...null_issued_at.pdf', 'Transfer Forms', 'Old Licence Holder', 300, 'b8ffa8efb55992e6cee94315ad6b80a8d993dc9e', '2024-01-31 20:47:36.000000', '2024-01-31 20:47:36.000000'),
(33, 1, 'null_issued_at.pdf', 'mrnlabs/8ae...null_issued_at.pdf', 'Index Page', 'Old Licence Holder', 100, '8ae4801f9e42f9f4e2c5d2cff8350419a8319eb3', '2024-01-31 20:47:45.000000', '2024-01-31 20:47:45.000000'),
(34, 1, 'null_issued_at.pdf', 'mrnlabs/175...null_issued_at.pdf', 'Payment To The Liquor Board', NULL, 200, '17544b68c91f77ad2974e2c4cdbdce739f78c7bc', '2024-01-31 20:47:54.000000', '2024-01-31 20:47:54.000000'),
(35, 1, 'null_issued_at.pdf', 'mrnlabs/77e...null_issued_at.pdf', 'Latest Renewal', 'Current Licence Holder', 900, '77e552fb82fafa2f3e2da3293c74d76db15898ed', '2024-01-31 20:48:03.000000', '2024-01-31 20:48:03.000000'),
(36, 1, 'null_issued_at.pdf', 'mrnlabs/298...null_issued_at.pdf', 'Lease/Landlord Letter', 'Current Licence Holder', 1900, '2987b74ea1eec9df2f119632e72c245f58a88b01', '2024-01-31 20:48:08.000000', '2024-01-31 20:48:08.000000'),
(37, 1, 'null_issued_at.pdf', 'mrnlabs/5c4...null_issued_at.pdf', 'Representation', 'Old Licence Holder', 800, '5c43d66a011a77f677b9dcc050481958fb74f5ec', '2024-01-31 20:48:12.000000', '2024-01-31 20:48:12.000000'),
(38, 1, 'null_licence_date.pdf', 'mrnlabs/3ed...null_licence_date.pdf', 'Financial Interests', 'Current Licence Holder', 1800, '3ed1d9181c950234e54db07381a45b048d67d739', '2024-01-31 20:48:20.000000', '2024-01-31 20:48:20.000000'),
(39, 1, 'licences.pdf', 'mrnlabs/f00...licences.pdf', 'LTA Certificate', 'Current Licence Holder', 1700, 'f0031df8226ddfb69eb291bc8688af52f4dab09d', '2024-01-31 20:48:24.000000', '2024-01-31 20:48:24.000000'),
(40, 1, 'licences (1).pdf', 'mrnlabs/9cc...licences_(1).pdf', 'Tax Clearance', 'Current Licence Holder', 1600, '9ccc27effe9319bc89eb26d6aad2f0ba719fef4c', '2024-01-31 20:48:28.000000', '2024-01-31 20:48:28.000000'),
(41, 1, 'licences.pdf', 'mrnlabs/c63...licences.pdf', 'Police Clearances', 'Current Licence Holder', 1500, 'c636755416e817a30c2a5923fddbe97824074b7c', '2024-01-31 20:48:32.000000', '2024-01-31 20:48:32.000000'),
(42, 1, 'licences.pdf', 'mrnlabs/092...licences.pdf', 'Company Documents', 'Current Licence Holder', NULL, '092332e787768b21e7fad7ce952bd3b3ad68bdf9', '2024-01-31 20:49:23.000000', '2024-01-31 20:49:23.000000'),
(43, 1, 'licences.pdf', 'mrnlabs/d62...licences.pdf', 'ID Documents', 'Current Licence Holder', 1400, 'd6224f74f30f2c6abd874d0b5faadb2881a26fe9', '2024-01-31 20:49:26.000000', '2024-01-31 20:49:26.000000'),
(44, 1, 'licences.pdf', 'mrnlabs/f52...licences.pdf', 'CIPC Certificate', 'Current Licence Holder', 1300, 'f523a8af467fb530b0ef5b5bde9eccfcbef3a26c', '2024-01-31 20:49:30.000000', '2024-01-31 20:49:30.000000'),
(45, 1, 'licences.pdf', 'mrnlabs/98f...licences.pdf', 'Shareholding', 'Current Licence Holder', 1200, '98f9cd2101e4d8d1ed0877e7c2c357d5b3e3f811', '2024-01-31 20:49:34.000000', '2024-01-31 20:49:34.000000'),
(46, 1, 'licences.pdf', 'mrnlabs/f69...licences.pdf', 'POA & RES', 'Current Licence Holder', 700, 'f693e1e9f4969c333cc2479d55f5716bb17b1c33', '2024-01-31 20:49:37.000000', '2024-01-31 20:49:37.000000');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `people_id` bigint(20) DEFAULT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_verified_at` datetime(6) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `picture` varchar(200) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `people_id`, `contact`, `email_verified_at`, `password`, `remember_token`, `created_at`, `last_activity_at`, `is_active`, `picture`, `updated_at`) VALUES
(1, 'Developer Account', 'developer@slotow.co.za', NULL, '089 678 5467', '2022-10-05 12:40:00.000000', '$2y$10$dKrPFf8V7xVdk2tsagFxJ.KDsfSOnu2o42GKJGc2ExhiUe42W2NOO', 'M3k3VP61VOLsn5CsyrRjY03OeW7J5W6ZB9hAPC0OzhRBqXgyauWBOs1FE6qp', '2022-10-05 12:40:00.000000', '2024-02-04 15:00:46', 1, NULL, '2024-02-04 17:00:46.000000'),
(11, 'Nadine Meyerowitz', 'nadine@slotow.co.za', NULL, NULL, NULL, '$2y$10$dUz2ZWSVR4zjEowAax5woePD00NJkHkA6pRRXaE2zw/ACWQ2YEA.6', 'HxayN406w7hUxRZA6eCNPN2BLK6XskSfPMvsFuQXWx8TLwjqu3OY6LoC77aH', '2023-01-10 08:27:56.000000', '2023-06-02 07:21:57', 1, NULL, '2023-06-02 09:21:57.000000'),
(12, 'Jessica Deans', 'jessica@slotow.co.za', NULL, NULL, NULL, '$2y$10$jxUK5IXpzCMajKvXiN1/uO6v1j.HQyBcfsTjt0HIP95bfzqhrJOkq', 'WQTgTZKeHBdREkkA4wPD1CHMwh6zDZjTIGeuT7bcZoDHhwwos60YmT6tFb1F', '2023-01-10 08:50:06.000000', '2023-06-05 06:34:08', 1, 'WhatsApp_Image_2023_04_24_at_11.36.26.jpg', '2023-06-05 08:34:08.000000'),
(13, 'Nikki Deans', 'nikki@slotow.co.za', NULL, NULL, NULL, '$2y$10$D1V0lqyWO.17CDRFA99JbuWLL39Zl0roL9atExCWMp.g6ajNWE//y', 'GaEWE2HTarNNJJVqvEIdQL6lKuDYSZE7snh0LdbP0j3jDwCFlsLDiz82PBjR', '2023-01-10 08:52:19.000000', '2023-06-05 06:45:37', 1, NULL, '2023-06-05 08:45:37.000000'),
(14, 'Support', 'support@slotow.co.za', NULL, NULL, NULL, '$2y$10$knP1HqI3yPnBmUPMWfHYY.pHlMVhxzOrAEjBR5LWVMHvlq/LTM/q2', 'OSoKuFLZUNfBGBftVluVjIjdLCYEKidX5FD1iC5Dhfa1diRajkJ6eJZIa2hL', '2023-01-12 15:29:28.000000', '2023-06-02 13:33:34', 1, NULL, '2023-06-02 15:33:34.000000'),
(30, 'Admin 2', 'admin2@slotow.co.za', NULL, NULL, NULL, '$2y$10$sP9tdV/aI/Hg16YjrRSsmuZWJhukAZHPxbRNY1pqvl0iSutd1Dbpq', 'f8baHR1PcDzF3mA2EyLdtfUyvIv4DOYLpcYazHl9r8OYSCqqzearVbCNOicz', '2023-01-20 09:24:38.000000', '2023-06-05 06:30:28', 1, NULL, '2023-06-05 08:30:28.000000'),
(32, 'Boiler Room', 'boilerroom@slotow.co.za', NULL, NULL, NULL, '$2y$10$SlDGlE/ZUlbwenIO5xvV/eDJxdGgN2NeQqM6jjNt9TT3imH39XSji', 'Z8C7RRkHigvayS5iZFXKrk2e1VZUx1jFZfZ6CfunX6XfxN2txIAjAVX4Lsj2', '2023-01-20 14:26:50.000000', '2023-06-05 06:38:17', 1, NULL, '2023-06-05 08:38:17.000000'),
(33, 'Sales', 'sales@slotow.co.za', NULL, NULL, NULL, '$2y$10$Ix4tul07C03D40rCMS0baOiFxM3XCP2n66Wft9Vd05FP7Pk56s8TW', 'Kr7cQ9K5Xv8AiIz1tRHrqwpz6NnMSYBKu4h84ULj05QvrVhkX1wKbbmjiWgs', '2023-02-03 12:02:17.000000', '2023-06-05 06:13:15', 1, NULL, '2023-06-05 08:13:15.000000'),
(34, 'Accounts', 'accounts@slotow.co.za', NULL, NULL, NULL, '$2y$10$suK06KRvE.XwZQBj5RHYn.XEw4Zku.5ZvdJadmUMMAHGy2AM0Pj3e', '1BerUQO4FvbCtYSZnGB2T8O5ds4oyOeLTgnn2wcjZTQHKDFrrzxSe6wdnQZ2', '2023-02-08 10:49:00.000000', '2023-05-25 11:33:58', 1, NULL, '2023-05-25 13:33:58.000000'),
(36, 'Admin 3', 'admin3@slotow.co.za', NULL, NULL, NULL, '$2y$10$/s9ZVAdktrSr3SUj2PQsh.CYswoqW/YsN1BFhEgk0ttbKEE3Jbefa', '9yWAjaUy5AGkLAPSxLkkkd4KY7uX30jVDuJlvYgd765EjvR1xZ2wTT2ChpVc', '2023-04-19 14:20:36.000000', '2023-05-23 09:21:21', 1, NULL, '2023-12-13 20:46:53.000000'),
(38, 'Admin', 'admin@slotow.co.za', NULL, NULL, NULL, '$2y$10$bQwivAHK3szOavi8/j8JL.R2c2V4uLJNHgsBg6ygloDmUAWcxJf8C', 'lRve3GsNrdJ1rsedJQizoScmgTrTiT0xidhPfBd9QLte3VQjpnD5TNvrytOe', '2023-05-31 11:48:46.000000', '2023-06-05 06:11:46', 1, NULL, '2023-06-05 08:11:46.000000');

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

CREATE TABLE `years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `years`
--

INSERT INTO `years` (`id`, `year`) VALUES
(1, 2023),
(2, 2022),
(3, 2021),
(4, 2020),
(5, 2019),
(6, 2018),
(7, 2017),
(8, 2016),
(9, 2015),
(10, 2014),
(11, 2013),
(12, 2012),
(13, 2011),
(14, 2010),
(15, 2009),
(16, 2008),
(17, 2007),
(18, 2006),
(19, 2005),
(20, 2004),
(21, 2003),
(22, 2002),
(23, 2001),
(24, 2000);

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
-- Indexes for table `alterations`
--
ALTER TABLE `alterations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alterations_licence_id_foreign` (`licence_id`);

--
-- Indexes for table `alteration_dates`
--
ALTER TABLE `alteration_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alteration_id` (`alteration_id`);

--
-- Indexes for table `alteration_documents`
--
ALTER TABLE `alteration_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alteration_documents_alteration_id_foreign` (`alteration_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_documents`
--
ALTER TABLE `company_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_documents_company_id_foreign` (`company_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `company_people`
--
ALTER TABLE `company_people`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_people_company_id_foreign` (`company_id`),
  ADD KEY `company_people_people_id_foreign` (`people_id`);

--
-- Indexes for table `company_user`
--
ALTER TABLE `company_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_user_company_id_foreign` (`company_id`),
  ADD KEY `company_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `duplicate_originals`
--
ALTER TABLE `duplicate_originals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licence_id` (`licence_id`);

--
-- Indexes for table `duplicate_original_docs`
--
ALTER TABLE `duplicate_original_docs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `duplicate_original_id` (`duplicate_original_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `licences`
--
ALTER TABLE `licences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licences_company_id_foreign` (`company_id`),
  ADD KEY `licences_people_id_foreign` (`people_id`),
  ADD KEY `licences_licence_type_id_foreign` (`licence_type_id`);

--
-- Indexes for table `licence_dates`
--
ALTER TABLE `licence_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licence_id` (`licence_id`);

--
-- Indexes for table `licence_documents`
--
ALTER TABLE `licence_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licence_documents_licence_id_foreign` (`licence_id`);

--
-- Indexes for table `licence_renewals`
--
ALTER TABLE `licence_renewals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licence_renewals_licence_id_foreign` (`licence_id`);

--
-- Indexes for table `licence_transfers`
--
ALTER TABLE `licence_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licence_transfers_licence_id_foreign` (`licence_id`),
  ADD KEY `licence_transfers_company_id_foreign` (`company_id`),
  ADD KEY `people_id` (`people_id`),
  ADD KEY `old_people_id` (`old_people_id`);

--
-- Indexes for table `licence_types`
--
ALTER TABLE `licence_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `liquor_board_requests`
--
ALTER TABLE `liquor_board_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merged_documents`
--
ALTER TABLE `merged_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nomination_id` (`nomination_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_type`,`model_id`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_type`,`model_id`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `nominations`
--
ALTER TABLE `nominations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nominations_licence_id_foreign` (`licence_id`);

--
-- Indexes for table `nomination_documents`
--
ALTER TABLE `nomination_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nomination_id` (`nomination_id`);

--
-- Indexes for table `nomination_people`
--
ALTER TABLE `nomination_people`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nomination_id` (`nomination_id`),
  ADD KEY `people_id` (`people_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `people_documents`
--
ALTER TABLE `people_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `people_id` (`people_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `renewal_documents`
--
ALTER TABLE `renewal_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `renewal_documents_licence_renewal_id_foreign` (`licence_renewal_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_user_id_foreign` (`user_id`);

--
-- Indexes for table `temporal_licences`
--
ALTER TABLE `temporal_licences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temporal_licences_company_id_foreign` (`company_id`),
  ADD KEY `temporal_licences_people_id_foreign` (`people_id`);

--
-- Indexes for table `temporal_licence_documents`
--
ALTER TABLE `temporal_licence_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temporal_licence_documents_temporal_licence_id_foreign` (`temporal_licence_id`);

--
-- Indexes for table `transfer_documents`
--
ALTER TABLE `transfer_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_documents_licence_transfer_id_foreign` (`licence_transfer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_people_id_foreign` (`people_id`);

--
-- Indexes for table `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_docs`
--
ALTER TABLE `additional_docs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `alterations`
--
ALTER TABLE `alterations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `alteration_dates`
--
ALTER TABLE `alteration_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `alteration_documents`
--
ALTER TABLE `alteration_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company_documents`
--
ALTER TABLE `company_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_people`
--
ALTER TABLE `company_people`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_user`
--
ALTER TABLE `company_user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `duplicate_originals`
--
ALTER TABLE `duplicate_originals`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `duplicate_original_docs`
--
ALTER TABLE `duplicate_original_docs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `licences`
--
ALTER TABLE `licences`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `licence_dates`
--
ALTER TABLE `licence_dates`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `licence_documents`
--
ALTER TABLE `licence_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `licence_renewals`
--
ALTER TABLE `licence_renewals`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `licence_transfers`
--
ALTER TABLE `licence_transfers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `licence_types`
--
ALTER TABLE `licence_types`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `nominations`
--
ALTER TABLE `nominations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nomination_documents`
--
ALTER TABLE `nomination_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nomination_people`
--
ALTER TABLE `nomination_people`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `people_documents`
--
ALTER TABLE `people_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `renewal_documents`
--
ALTER TABLE `renewal_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `temporal_licences`
--
ALTER TABLE `temporal_licences`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temporal_licence_documents`
--
ALTER TABLE `temporal_licence_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `transfer_documents`
--
ALTER TABLE `transfer_documents`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `additional_docs`
--
ALTER TABLE `additional_docs`
  ADD CONSTRAINT `licence_id` FOREIGN KEY (`licence_id`) REFERENCES `licences` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `duplicate_original_docs`
--
ALTER TABLE `duplicate_original_docs`
  ADD CONSTRAINT `duplicate_original_id` FOREIGN KEY (`duplicate_original_id`) REFERENCES `duplicate_originals` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
