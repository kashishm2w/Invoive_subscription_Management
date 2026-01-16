-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 15, 2026 at 07:22 AM
-- Server version: 8.0.44-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Invoice_Subscription_Management`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `tax_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tax_rate` enum('18','0') NOT NULL DEFAULT '18',
  `tax_percent` decimal(5,2) DEFAULT '18.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `user_id`, `company_name`, `email`, `phone`, `address`, `tax_number`, `created_at`, `tax_rate`, `tax_percent`) VALUES
(1, 4, 'Invoice and sub', 'admin@gmail.com', '7408787382', 'Hisar ,op. jindal\r\n', '22', '2025-12-29 11:49:11', '18', 28.00);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `created_by` int NOT NULL,
  `client_id` int NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_type` enum('GST','NONE') NOT NULL,
  `tax_rate` decimal(5,2) DEFAULT '0.00',
  `tax_amount` decimal(10,2) DEFAULT '0.00',
  `discount` decimal(10,2) DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `due_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('Unpaid','Paid','Overdue','Partial') NOT NULL DEFAULT 'Unpaid',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `created_by`, `client_id`, `invoice_number`, `invoice_date`, `due_date`, `subtotal`, `tax_type`, `tax_rate`, `tax_amount`, `discount`, `total_amount`, `amount_paid`, `due_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(133, 1, 1, 'INV-20260114-044101', '2026-01-14', '2026-01-21', 12.10, 'GST', 2.98, 0.36, 0.00, 12.46, 0.00, 12.46, 'Unpaid', 'Generated from cart', '2026-01-14 04:41:01', '2026-01-14 04:42:17'),
(134, 1, 1, 'INV-20260114-044124', '2026-01-14', '2026-01-21', 32.00, 'GST', 2.00, 0.64, 0.00, 32.64, 0.00, 32.64, 'Unpaid', 'Generated from cart', '2026-01-14 04:41:24', '2026-01-14 04:42:17'),
(135, 1, 1, 'INV-20260114-044141', '2026-01-14', '2026-01-21', 94.00, 'GST', 4.55, 4.28, 0.00, 98.28, 0.00, 98.28, 'Unpaid', 'Generated from cart', '2026-01-14 04:41:41', '2026-01-14 04:42:17'),
(136, 1, 1, 'INV-20260114-044209', '2026-01-14', '2026-01-21', 110.00, 'GST', 0.58, 0.64, 0.00, 110.64, 0.00, 110.64, 'Unpaid', 'Generated from cart', '2026-01-14 04:42:09', '2026-01-14 04:42:17'),
(137, 1, 1, 'INV-20260114-044235', '2026-01-14', '2026-01-21', 12.10, 'GST', 2.98, 0.36, 0.00, 12.46, 0.00, 12.46, 'Unpaid', 'Generated from cart', '2026-01-14 04:42:35', '2026-01-14 04:43:02'),
(138, 1, 1, 'INV-20260114-044254', '2026-01-14', '2026-01-21', 32.00, 'GST', 2.00, 0.64, 0.00, 32.64, 0.00, 32.64, 'Unpaid', 'Generated from cart', '2026-01-14 04:42:54', '2026-01-14 04:43:02'),
(139, 1, 1, 'INV-20260114-044314', '2026-01-14', '2026-01-21', 12.10, 'GST', 2.98, 0.36, 0.00, 12.46, 12.46, 0.00, 'Paid', 'Generated from cart', '2026-01-14 04:43:14', '2026-01-14 06:18:56'),
(140, 1, 1, 'INV-20260114-044329', '2026-01-14', '2026-01-21', 55.10, 'GST', 0.00, 0.00, 0.00, 55.10, 0.00, 55.10, 'Unpaid', 'Generated from cart', '2026-01-14 04:43:29', '2026-01-14 04:44:23'),
(141, 1, 1, 'INV-20260114-044345', '2026-01-14', '2026-01-21', 32.00, 'GST', 2.00, 0.64, 0.00, 32.64, 0.00, 32.64, 'Unpaid', 'Generated from cart', '2026-01-14 04:43:45', '2026-01-14 04:44:23'),
(142, 1, 1, 'INV-20260114-044402', '2026-01-14', '2026-01-21', 32.00, 'GST', 2.00, 0.64, 0.00, 32.64, 0.00, 32.64, 'Unpaid', 'Generated from cart', '2026-01-14 04:44:02', '2026-01-14 04:44:23'),
(143, 1, 1, 'INV-20260114-044415', '2026-01-14', '2026-01-21', 32.00, 'GST', 2.00, 0.64, 0.00, 32.64, 22.64, 10.00, 'Partial', 'Generated from cart', '2026-01-14 04:44:15', '2026-01-14 04:57:07'),
(144, 1, 1, 'SUB-20260114-050813', '2026-01-14', '2026-01-14', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SpMJUFA4kAFMB5R1hqMiTCC', '2026-01-14 05:08:13', '2026-01-14 05:08:45'),
(145, 14, 14, 'SUB-20260114-051113', '2026-01-14', '2026-01-14', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SpMMOFA4kAFMB5R2RIMYGa2', '2026-01-14 05:11:13', '2026-01-14 05:11:13'),
(146, 19, 19, 'SUB-20260114-051508', '2026-01-14', '2026-01-14', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SpMQBFA4kAFMB5R1gUtIpRH', '2026-01-14 05:15:08', '2026-01-14 05:15:08'),
(147, 20, 20, 'INV-20260114-062639', '2026-01-14', '2026-01-21', 307.10, 'GST', 4.07, 12.00, 0.00, 307.10, 125.00, 182.10, 'Partial', 'Partial payment via Stripe | Transaction ID: ch_3SpNXOFA4kAFMB5R189DBYPY', '2026-01-14 06:26:39', '2026-01-14 06:26:39'),
(148, 1, 1, 'INV-20260114-092428', '2026-01-14', '2026-01-21', 65.28, 'GST', 2.00, 1.28, 1.31, 63.97, 50.00, 13.97, 'Partial', 'Partial payment via Stripe | Transaction ID: ch_3SpQJTFA4kAFMB5R130Sugef | Subscription Discount: 2%', '2026-01-14 09:24:28', '2026-01-14 09:24:28'),
(149, 1, 1, 'INV-20260114-093556', '2026-01-14', '2026-01-21', 55.10, 'GST', 0.00, 0.00, 1.10, 54.00, 20.00, 34.00, 'Partial', 'Partial payment via Stripe | Transaction ID: ch_3SpQUZFA4kAFMB5R194mlKBX | Subscription Discount: 2%', '2026-01-14 09:35:56', '2026-01-14 09:35:56'),
(150, 1, 1, 'INV-20260114-093921', '2026-01-14', '2026-01-21', 12.10, 'GST', 2.98, 0.36, 0.00, 12.46, 0.00, 12.46, 'Unpaid', 'Generated from cart', '2026-01-14 09:39:21', '2026-01-14 09:48:12'),
(151, 1, 1, 'INV-20260114-094002', '2026-01-14', '2026-01-21', 67.00, 'GST', 0.54, 0.36, 0.00, 67.36, 67.36, 0.00, 'Paid', 'Generated from cart', '2026-01-14 09:40:02', '2026-01-14 13:56:57'),
(152, 20, 20, 'SUB-20260114-095029', '2026-01-14', '2026-01-14', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SpQieFA4kAFMB5R2f0Dn41H', '2026-01-14 09:50:29', '2026-01-14 09:50:29'),
(153, 20, 20, 'SUB-20260114-095657', '2026-01-14', '2026-01-14', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SpQouFA4kAFMB5R1YmnRg4i', '2026-01-14 09:56:57', '2026-01-14 09:56:57'),
(154, 20, 20, 'SUB-20260114-100043', '2026-01-14', '2026-01-14', 1999.00, 'NONE', 0.00, 0.00, 0.00, 1999.00, 1999.00, 0.00, 'Paid', 'Subscription Payment - ultra  | Transaction ID: ch_3SpQsYFA4kAFMB5R1In4ZMI5', '2026-01-14 10:00:43', '2026-01-14 10:00:43'),
(155, 20, 20, 'INV-20260114-100215', '2026-01-14', '2026-01-21', 167.10, 'GST', 0.00, 0.00, 25.06, 142.04, 90.00, 52.04, 'Partial', 'Partial payment via Stripe | Transaction ID: ch_3SpQu2FA4kAFMB5R05oqZIIg | Subscription Discount: 15%', '2026-01-14 10:02:15', '2026-01-14 10:03:49'),
(156, 14, 14, 'INV-20260114-110125', '2026-01-14', '2026-01-21', 145.00, 'GST', 0.00, 0.00, 0.00, 145.00, 0.00, 145.00, 'Unpaid', 'Generated from cart', '2026-01-14 11:01:25', '2026-01-14 11:14:08'),
(157, 14, 14, 'INV-20260114-110222', '2026-01-14', '2026-01-21', 189.88, 'GST', 0.47, 0.88, 0.00, 189.88, 50.00, 139.88, 'Partial', 'Partial payment via Stripe | Transaction ID: ch_3SpRqDFA4kAFMB5R0HpTfdDS', '2026-01-14 11:02:22', '2026-01-14 11:02:22'),
(158, 14, 14, 'INV-20260114-111301', '2026-01-14', '2026-01-21', 120.64, 'GST', 3.11, 3.64, 0.00, 120.64, 100.00, 20.64, 'Partial', 'Partial payment via Stripe | Transaction ID: ch_3SpS0WFA4kAFMB5R0QfkR8XM', '2026-01-14 11:13:01', '2026-01-14 11:13:01'),
(159, 1, 1, 'INV-20260114-134756', '2026-01-14', '2026-01-21', 220.28, 'GST', 0.58, 1.28, 4.41, 215.87, 215.87, 0.00, 'Paid', 'Partial payment via Stripe | Transaction ID: ch_3SpUQRFA4kAFMB5R2pmwPjIY | Subscription Discount: 2%', '2026-01-14 13:47:56', '2026-01-14 13:56:26'),
(160, 1, 1, 'SUB-20260114-134928', '2026-01-14', '2026-01-14', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SpURvFA4kAFMB5R1j1p5NwV', '2026-01-14 13:49:28', '2026-01-14 13:49:28'),
(161, 1, 1, 'SUB-20260114-135055', '2026-01-14', '2026-01-14', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SpUTKFA4kAFMB5R1TeaHILR', '2026-01-14 13:50:55', '2026-01-14 13:50:55'),
(162, 1, 1, 'SUB-20260114-135224', '2026-01-14', '2026-01-14', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SpUUmFA4kAFMB5R2yys4gXL', '2026-01-14 13:52:24', '2026-01-14 13:52:24'),
(163, 1, 1, 'SUB-20260114-135541', '2026-01-14', '2026-01-14', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SpUXwFA4kAFMB5R0sKR5MLO', '2026-01-14 13:55:41', '2026-01-14 13:55:41'),
(164, 1, 1, 'INV-20260114-135749', '2026-01-14', '2026-01-14', 67.36, 'GST', 0.54, 0.36, 0.00, 67.36, 67.36, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SpUa0FA4kAFMB5R0MxcTtpg', '2026-01-14 13:57:49', '2026-01-14 13:57:49'),
(165, 1, 1, 'SUB-20260115-042247', '2026-01-15', '2026-01-15', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3Spi54FA4kAFMB5R0LB15AlX', '2026-01-15 04:22:47', '2026-01-15 04:22:47'),
(166, 1, 1, 'SUB-20260115-042527', '2026-01-15', '2026-01-15', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3Spi7eFA4kAFMB5R1iqvB7sz', '2026-01-15 04:25:27', '2026-01-15 04:25:27'),
(167, 1, 1, 'SUB-20260115-042835', '2026-01-15', '2026-01-15', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SpiAgFA4kAFMB5R2iGDqGwK', '2026-01-15 04:28:35', '2026-01-15 04:28:35'),
(168, 13, 13, 'SUB-20260115-043048', '2026-01-15', '2026-01-15', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SpiCpFA4kAFMB5R1FTTjrpC', '2026-01-15 04:30:48', '2026-01-15 04:30:48'),
(169, 1, 1, 'SUB-20260115-062110', '2026-01-15', '2026-01-15', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SpjvdFA4kAFMB5R1urBsfBK', '2026-01-15 06:21:10', '2026-01-15 06:21:10');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `item_name`, `quantity`, `price`, `total`) VALUES
(266, 133, 'saviya', 1, 12.00, 12.00),
(267, 133, 'saviya', 1, 0.10, 0.10),
(268, 134, 'soup', 1, 20.00, 20.00),
(269, 134, 'wwwwww', 1, 12.00, 12.00),
(270, 135, 'soup', 1, 20.00, 20.00),
(271, 135, 'wwwwww', 1, 12.00, 12.00),
(272, 135, 'Raghav', 1, 32.00, 32.00),
(273, 135, 'sjjsgwgj', 1, 30.00, 30.00),
(274, 136, 'soup', 1, 20.00, 20.00),
(275, 136, 'wwwwww', 1, 12.00, 12.00),
(276, 136, 'wsssss', 1, 78.00, 78.00),
(277, 137, 'saviya', 1, 12.00, 12.00),
(278, 137, 'saviya', 1, 0.10, 0.10),
(279, 138, 'soup', 1, 20.00, 20.00),
(280, 138, 'wwwwww', 1, 12.00, 12.00),
(281, 139, 'saviya', 1, 12.00, 12.00),
(282, 139, 'saviya', 1, 0.10, 0.10),
(283, 140, 'saviya', 1, 0.10, 0.10),
(284, 140, 'eiuguydf', 1, 55.00, 55.00),
(285, 141, 'soup', 1, 20.00, 20.00),
(286, 141, 'wwwwww', 1, 12.00, 12.00),
(287, 142, 'soup', 1, 20.00, 20.00),
(288, 142, 'wwwwww', 1, 12.00, 12.00),
(289, 143, 'soup', 1, 20.00, 20.00),
(290, 143, 'wwwwww', 1, 12.00, 12.00),
(291, 144, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(292, 145, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(293, 146, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(294, 147, 'saviya', 1, 0.10, 0.10),
(295, 147, 'eiuguydf', 1, 55.00, 55.00),
(296, 147, 'Macroni', 4, 60.00, 240.00),
(297, 148, 'soup', 1, 20.00, 20.00),
(298, 148, 'wwwwww', 1, 12.00, 12.00),
(299, 148, 'Raghav', 1, 32.00, 32.00),
(300, 149, 'eiuguydf', 1, 55.00, 55.00),
(301, 149, 'saviya', 1, 0.10, 0.10),
(302, 150, 'saviya', 1, 12.00, 12.00),
(303, 150, 'saviya', 1, 0.10, 0.10),
(304, 151, 'eiuguydf', 1, 55.00, 55.00),
(305, 151, 'saviya', 1, 12.00, 12.00),
(306, 152, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(307, 153, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(308, 154, 'Subscription: ultra  (Yearly)', 1, 1999.00, 1999.00),
(309, 155, 'saviya', 1, 0.10, 0.10),
(310, 155, 'kdhk', 1, 22.00, 22.00),
(311, 155, 'tyjtyj', 1, 67.00, 67.00),
(312, 155, 'wsssss', 1, 78.00, 78.00),
(313, 156, 'tyjtyj', 1, 67.00, 67.00),
(314, 156, 'wsssss', 1, 78.00, 78.00),
(315, 157, 'tyjtyj', 1, 67.00, 67.00),
(316, 157, 'wsssss', 1, 78.00, 78.00),
(317, 157, 'wwwwww', 1, 12.00, 12.00),
(318, 157, 'Raghav', 1, 32.00, 32.00),
(319, 158, 'eiuguydf', 1, 55.00, 55.00),
(320, 158, 'sjjsgwgj', 1, 30.00, 30.00),
(321, 158, 'Raghav', 1, 32.00, 32.00),
(322, 159, 'tyjtyj', 1, 67.00, 67.00),
(323, 159, 'wsssss', 1, 78.00, 78.00),
(324, 159, 'kdhk', 1, 10.00, 10.00),
(325, 159, 'Raghav', 1, 32.00, 32.00),
(326, 159, 'wwwwww', 1, 12.00, 12.00),
(327, 159, 'soup', 1, 20.00, 20.00),
(328, 160, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(329, 161, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(330, 162, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(331, 163, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(332, 164, 'saviya', 1, 12.00, 12.00),
(333, 164, 'eiuguydf', 1, 55.00, 55.00),
(334, 165, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(335, 166, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(336, 167, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(337, 168, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(338, 169, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('stripe','cod') DEFAULT 'stripe',
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'completed',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `user_id`, `amount`, `payment_method`, `transaction_id`, `status`, `notes`, `created_at`) VALUES
(13, 133, 1, 12.46, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:41:01'),
(14, 134, 1, 32.64, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:41:24'),
(15, 135, 1, 98.28, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:41:41'),
(16, 136, 1, 110.64, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:42:09'),
(17, 137, 1, 12.46, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:42:35'),
(18, 138, 1, 32.64, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:42:54'),
(19, 139, 1, 12.46, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:43:14'),
(20, 140, 1, 55.10, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:43:29'),
(21, 141, 1, 32.64, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:43:45'),
(22, 142, 1, 32.64, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:44:02'),
(23, 143, 1, 32.64, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:44:15'),
(24, 143, 1, 22.64, 'stripe', 'ch_3SpM8kFA4kAFMB5R1a0SiMx6', 'completed', 'Partial payment', '2026-01-14 04:57:07'),
(25, 144, 1, 100.00, 'stripe', 'ch_3SpMK0FA4kAFMB5R1R7LQw1E', 'completed', 'Full payment', '2026-01-14 05:08:45'),
(26, 146, 19, 555.00, 'stripe', 'ch_3SpMQBFA4kAFMB5R1gUtIpRH', 'completed', 'Subscription Payment - Pro1', '2026-01-14 05:15:12'),
(27, 139, 1, 12.46, 'stripe', 'ch_3SpNPvFA4kAFMB5R2EXcRrMT', 'completed', 'Full payment', '2026-01-14 06:18:56'),
(28, 147, 20, 125.00, 'stripe', 'ch_3SpNXOFA4kAFMB5R189DBYPY', 'completed', 'Partial payment via Stripe', '2026-01-14 06:26:39'),
(29, 148, 1, 50.00, 'stripe', 'ch_3SpQJTFA4kAFMB5R130Sugef', 'completed', 'Partial payment via Stripe', '2026-01-14 09:24:28'),
(30, 149, 1, 20.00, 'stripe', 'ch_3SpQUZFA4kAFMB5R194mlKBX', 'completed', 'Partial payment via Stripe', '2026-01-14 09:35:56'),
(31, 150, 1, 12.46, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 09:39:21'),
(32, 151, 1, 67.36, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 09:40:02'),
(33, 152, 20, 555.00, 'stripe', 'ch_3SpQieFA4kAFMB5R2f0Dn41H', 'completed', 'Subscription Payment - Pro1', '2026-01-14 09:50:33'),
(34, 153, 20, 555.00, 'stripe', 'ch_3SpQouFA4kAFMB5R1YmnRg4i', 'completed', 'Subscription Payment - Pro1', '2026-01-14 09:57:02'),
(35, 154, 20, 1999.00, 'stripe', 'ch_3SpQsYFA4kAFMB5R1In4ZMI5', 'completed', 'Subscription Payment - ultra ', '2026-01-14 10:00:47'),
(36, 155, 20, 50.00, 'stripe', 'ch_3SpQu2FA4kAFMB5R05oqZIIg', 'completed', 'Partial payment via Stripe', '2026-01-14 10:02:15'),
(37, 155, 20, 40.00, 'stripe', 'ch_3SpQvYFA4kAFMB5R2mQ22Xv7', 'completed', 'Partial payment', '2026-01-14 10:03:49'),
(38, 156, 14, 145.00, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 11:01:25'),
(39, 157, 14, 50.00, 'stripe', 'ch_3SpRqDFA4kAFMB5R0HpTfdDS', 'completed', 'Partial payment via Stripe', '2026-01-14 11:02:22'),
(40, 158, 14, 100.00, 'stripe', 'ch_3SpS0WFA4kAFMB5R0QfkR8XM', 'completed', 'Partial payment via Stripe', '2026-01-14 11:13:01'),
(41, 159, 1, 215.87, 'stripe', 'ch_3SpUQRFA4kAFMB5R2pmwPjIY', 'completed', 'Partial payment via Stripe', '2026-01-14 13:47:56'),
(42, 160, 1, 100.00, 'stripe', 'ch_3SpURvFA4kAFMB5R1j1p5NwV', 'completed', 'Subscription Payment - Basic', '2026-01-14 13:49:32'),
(43, 161, 1, 100.00, 'stripe', 'ch_3SpUTKFA4kAFMB5R1TeaHILR', 'completed', 'Subscription Payment - Basic', '2026-01-14 13:50:59'),
(44, 162, 1, 100.00, 'stripe', 'ch_3SpUUmFA4kAFMB5R2yys4gXL', 'completed', 'Subscription Payment - Basic', '2026-01-14 13:52:28'),
(45, 163, 1, 100.00, 'stripe', 'ch_3SpUXwFA4kAFMB5R0sKR5MLO', 'completed', 'Subscription Payment - Basic', '2026-01-14 13:55:46'),
(46, 151, 1, 67.36, 'stripe', 'ch_3SpUZAFA4kAFMB5R2jhzLaSO', 'completed', 'Full payment', '2026-01-14 13:56:57'),
(47, 164, 1, 67.36, 'stripe', 'ch_3SpUa0FA4kAFMB5R0MxcTtpg', 'completed', 'Full payment via Stripe', '2026-01-14 13:57:49'),
(48, 165, 1, 555.00, 'stripe', 'ch_3Spi54FA4kAFMB5R0LB15AlX', 'completed', 'Subscription Payment - Pro1', '2026-01-15 04:22:51'),
(49, 166, 1, 555.00, 'stripe', 'ch_3Spi7eFA4kAFMB5R1iqvB7sz', 'completed', 'Subscription Payment - Pro1', '2026-01-15 04:25:30'),
(50, 167, 1, 100.00, 'stripe', 'ch_3SpiAgFA4kAFMB5R2iGDqGwK', 'completed', 'Subscription Payment - Basic', '2026-01-15 04:28:39'),
(51, 168, 13, 555.00, 'stripe', 'ch_3SpiCpFA4kAFMB5R1FTTjrpC', 'completed', 'Subscription Payment - Pro1', '2026-01-15 04:30:52'),
(52, 169, 1, 100.00, 'stripe', 'ch_3SpjvdFA4kAFMB5R1urBsfBK', 'completed', 'Subscription Payment - Basic', '2026-01-15 06:21:14');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `quantity` int DEFAULT '1',
  `poster` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_tax_free` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `quantity`, `poster`, `created_at`, `updated_at`, `is_tax_free`) VALUES
(10, 'Maggie', 'bwfwghxcwhxvchedxhwikx1`jvwj', 15.00, 94, '1767615260_maggie.jpg', '2025-12-30 04:29:06', '2026-01-05 12:14:20', 0),
(11, 'Macroni', 'Macaroni Classic curved pasta made from premium durum wheat. Perfect for creamy sauces baked dishes and quick comfort meals.', 60.00, 100, '1767176516_macroni.jpeg', '2025-12-31 07:41:58', '2025-12-31 10:21:56', 0),
(13, 'Besan', 'Finely ground gram flour made from quality chickpeas, ideal for snacks, curries, and traditional recipes.', 30.00, 99, '1767177494_besan.jpg', '2025-12-31 07:47:05', '2025-12-31 11:05:45', 0),
(15, 'saviya', 'Saviya  Thin vermicelli made from quality wheat perfect for sweet kheer upma and quick meals', 29.00, 56, '1767177564_saviya.jpg', '2025-12-31 08:00:38', '2026-01-05 12:17:56', 0),
(16, 'chowmin', 'Chowmein is a popular IndoChinese dish made with stirfried noodles fresh vegetable and savory sauces The noodles are cooked until tender  then tossed in a hot wok with ingredients like cabbage carrots capsicum spring onions and flavored with soy sauce garlic ginger and a hint of vinegar', 46.00, 67, '1767184498_chowmin.png', '2025-12-31 12:14:03', '2025-12-31 12:34:58', 0),
(17, 'Namkeen', 'namkeeenheghdgigdih1', 34.00, 99, '1767184566_Namkeen.png', '2025-12-31 12:36:06', '2026-01-05 12:17:49', 0),
(18, 'soup', 'duhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgv', 20.00, 14, '1767616067_slat.jpg', '2026-01-05 12:27:47', '2026-01-13 04:36:27', 0),
(19, 'wwwwww', 'bjdgehfdukew', 12.00, 17, '1767616089_chowmin.png', '2026-01-05 12:28:09', '2026-01-07 09:54:07', 0),
(21, 'Raghav', 'snsxwbxfweygxhwgu', 32.00, 17, '1767616521_saviya.jpg', '2026-01-05 12:35:21', '2026-01-06 11:17:07', 0),
(23, 'sjjsgwgj', 'wghgxjffhx', 30.00, 20, '1767674894_besan.jpg', '2026-01-06 04:48:14', '2026-01-06 11:17:21', 0),
(24, 'kdhk', 'wzxsxdedcfwerf', 10.00, 22, '1767675059_maggie.jpg', '2026-01-06 04:50:59', '2026-01-14 10:13:59', 0),
(25, 'wsssss', 'ejskdhhgtc', 78.00, 21, '1767675091_chowmin.png', '2026-01-06 04:51:31', '2026-01-07 09:54:20', 0),
(27, 'tyjtyj', 'yfujjyuuuuuu', 67.00, 1, '1767701220_macroni.jpeg', '2026-01-06 12:07:00', '2026-01-06 12:07:00', 0),
(28, 'eiuguydf', 'yueuqefdoegduqg', 55.00, 1, '1767785287_sugar.jpg', '2026-01-07 11:28:07', '2026-01-15 07:01:54', 1),
(30, 'saviya', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 12.00, 19, '1767877526_macroni.jpeg', '2026-01-08 13:05:26', '2026-01-15 07:12:22', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `auto_renew` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_id`, `start_date`, `end_date`, `status`, `auto_renew`, `created_at`) VALUES
(106, 1, 5, '2026-01-14', '2026-02-14', 'cancelled', 1, '2026-01-14 05:08:13'),
(107, 14, 10, '2026-01-14', '2026-02-14', 'cancelled', 1, '2026-01-14 05:11:13'),
(108, 19, 10, '2026-01-14', '2026-02-14', 'active', 1, '2026-01-14 05:15:08'),
(109, 20, 10, '2025-12-14', '2026-01-14', 'expired', 1, '2026-01-14 16:50:29'),
(110, 20, 10, '2025-12-15', '2026-01-13', 'expired', 1, '2026-01-13 16:56:57'),
(111, 20, 2, '2026-01-14', '2026-01-16', 'active', 1, '2026-01-14 10:00:43'),
(112, 1, 5, '2026-01-14', '2026-01-13', 'expired', 1, '2026-01-14 13:49:28'),
(113, 1, 5, '2026-01-14', '2026-02-14', 'expired', 1, '2026-01-14 13:50:55'),
(114, 1, 5, '2026-01-14', '2026-01-13', 'expired', 1, '2026-01-14 13:52:24'),
(115, 1, 5, '2026-01-01', '2026-02-05', 'expired', 1, '2026-01-14 13:55:41'),
(116, 1, 10, '2026-01-15', '2026-02-15', 'cancelled', 1, '2026-01-15 04:22:47'),
(117, 1, 10, '2025-12-15', '2026-01-15', 'cancelled', 1, '2025-12-15 04:25:27'),
(118, 1, 5, '2026-01-15', '2026-02-15', 'expired', 1, '2026-01-15 04:28:35'),
(119, 13, 10, '2026-01-15', '2026-02-15', 'cancelled', 1, '2026-01-15 04:30:48'),
(120, 1, 5, '2026-01-15', '2026-02-15', 'active', 1, '2026-01-15 06:21:10');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_percent` int NOT NULL DEFAULT '0',
  `billing_cycle` enum('monthly','yearly') NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `plan_name`, `price`, `discount_percent`, `billing_cycle`, `description`, `created_at`) VALUES
(2, 'ultra ', 1999.00, 15, 'yearly', 'Advance feature plans ', '2025-12-26 09:23:51'),
(5, 'Basic', 100.00, 2, 'monthly', 'basic plan', '2025-12-26 12:59:23'),
(10, 'Pro1', 555.00, 8, 'monthly', 'test', '2026-01-05 11:18:34'),
(12, 'Pro n', 555.00, -1, 'monthly', 'Pro1', '2026-01-14 09:52:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Raghav', 'raghav21@yopmail.com', '$2y$12$Ob1EAvzG7Kq9IJ3/CDiuyexO27IGyTCy5Vv0xH/NHDJ/Gd3hdH06C', '2025-12-19 16:35:57', 'user'),
(4, 'Admin', 'admin@gmail.com', '$2y$12$gEJQUCBcksnEamwI/JWKJu.ez.mUWrDGquUvZ.djTXnUVOpTlQRc6', '2025-12-19 17:43:27', 'admin'),
(7, 'Geeta', 'geeta14@gmail.com', '$2y$12$Adhho6mxGe6tn4FVsDAllOys.Uozitft7.4n4TqpY0QBIjyD/WAuO', '2025-12-25 16:37:55', 'user'),
(8, 'garima', 'g@gmail.co', '$2y$12$ObBfXZBgyzpIAqTxrdblr.fc.puJwCkup.3xAWU7yW0HC.nqgZCzS', '2025-12-26 17:45:28', 'user'),
(9, 'Garima', 'garima21@gmail.com', '$2y$12$Ib0vR9vyVu5f4ysbMf6nIOFa8kjta/C3rPLTHuLpI2LADMtUsVdPG', '2025-12-26 17:48:11', 'user'),
(10, 'Kashish Mittal', 'kashumittal0201@gmail.com', '$2y$12$jXC1z.SyJbizem8dQsmxqejO2w1a2SXoWpQTU5cb6tejZE8eR1aIu', '2025-12-29 12:31:04', 'user'),
(11, 'Vishal', 'vishal1533rana@gmail.com', '$2y$12$OG94f3ktYiodkk/eqrfFWuflXphZ.To8Lz/OQ7urJwliNOAS0tr2m', '2025-12-29 12:42:45', 'user'),
(12, 'Liza', 'liza21@gmail.com', '$2y$12$xPcUoCeGCtjYSOm1xtURQuSTxgF1EaFqF9WdamwlMrgZUwxKJDtS2', '2025-12-30 16:10:35', 'user'),
(13, 'Yash', 'yash21@gmail.com', '$2y$12$WThSX8/QYx.hBoY1pGlQb.Gel4kYYg.pTHj3XTH8VufjZvPOAs5zG', '2025-12-30 16:19:33', 'user'),
(14, 'Liza Gupta', 'liza21@yopmail.com', '$2y$12$NDP.9a0F4fFrt/qgmvfqfOB/C0tN4Va.OxUK8T9IK2Zz84F5AiZ36', '2025-12-31 12:20:59', 'user'),
(16, 'Gaurav', 'gaurav21@yopmail.com', '$2y$12$4uBk/sdGmkDheGG/lNhMy.s6rS/RuxILkfSTdQQomO8rzhYAlq5WO', '2026-01-05 17:14:21', 'user'),
(17, 'Amit', 'amitbatra121@yopmail.com', '$2y$12$4/aDubgqY0.EsfMIh0n2aO4N6T00R5s0QVsYTS8wfI1ZwgOuuFUwy', '2026-01-06 11:25:37', 'user'),
(18, 'Chnadan', 'chandan21@yopmail.com', '$2y$12$1j0yqbuu5Uzwqo1naiA3tup.wFU69DchBGn/yTzXsGpoPlSyj9PFu', '2026-01-07 17:36:03', 'user'),
(19, 'Yash', 'yash21@yopmail.com', '$2y$12$1wxY8I3C3ZeESFTDnyPrfeytcs4SgoHBqbGBuzkKwlm4Zo1wP3BwC', '2026-01-14 10:44:22', 'user'),
(20, 'Amit', 'amitbatra141@yopmail.com', '$2y$12$mhCQqAcGjYe8VDwE0sjDXuqcrTdlavYn0ZxHv4DAvRj0MmKDKr2.S', '2026-01-14 11:51:14', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `full_name`, `phone`, `address`, `city`, `state`, `pincode`, `is_default`, `created_at`) VALUES
(1, 1, 'Raghav', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-06 07:58:36'),
(2, 17, 'Amit', '7393872897', 'mind 2 web', 'moali', 'punjab', '235178', 1, '2026-01-06 09:01:53'),
(3, 17, 'amit', '8787867676', '2353,sector71', 'moali', 'punjab', '235178', 0, '2026-01-06 09:07:36'),
(4, 11, 'vishal', '7389965388', '3567, sector 3', 'panipat', 'haryana', '229272', 0, '2026-01-06 09:23:00'),
(5, 11, '456464', '5555555555', 'fvvtghb', '35535', '235', '234453', 0, '2026-01-06 09:26:22'),
(6, 11, '45wwwwwwwwwwwww', '5444444444', 't54wwwwwwwwwwwwwwww', 't54', '45wt', '222222', 0, '2026-01-06 09:26:59'),
(7, 11, 'vishal', '7389965388', '3567, sector 3', 'panipat', 'haryana', '229272', 1, '2026-01-06 09:36:19'),
(8, 1, 'vishal', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-06 09:59:54'),
(9, 18, 'chandan', '9373657363', 'jindal chowk', 'hisar', 'Haryana', '125022', 1, '2026-01-09 04:23:33'),
(12, 14, 'liza', '7675786968', '3567, sector 3', 'panipat', 'haryana', '229272', 1, '2026-01-13 05:10:11'),
(13, 14, 'Raghav', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:16'),
(14, 14, 'vishal', '7389965388', '3567, sector 3', 'panipat', 'haryana', '229272', 0, '2026-01-13 05:19:20'),
(15, 14, 'amit', '8787867676', '2353,sector71', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:24'),
(16, 14, 'Amit', '7393872897', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:28'),
(17, 14, 'Raghav', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:41'),
(18, 14, 'Raghav', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:45'),
(19, 16, 'Gaurav', '9723534232', 'mulatn colony', 'hansi', 'haryana', '125033', 1, '2026-01-13 10:04:33'),
(20, 20, 'Amit', '9878464654', 'test', 'Chandigarh', 'Chandigarh', '160019', 0, '2026-01-14 06:24:13'),
(21, 20, 'AmitHome', '9846545646', 'tesing', 'Mohali', 'Punjab', '198825', 1, '2026-01-14 06:25:03'),
(22, 1, 'Raghav Yadav', '8793649873', 'test', 'mohali', 'punjab', '132566', 1, '2026-01-14 09:07:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=339;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
