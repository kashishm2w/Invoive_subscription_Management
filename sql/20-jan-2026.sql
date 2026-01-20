-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 20, 2026 at 04:20 AM
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
(1, 4, 'Invoice and sub', 'admin1@gmail.com', '7408787384', 'Hisar ,op. jindal\r\n', '22AAAAA0000A1Z5', '2025-12-29 11:49:11', '18', 12.00);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `created_by` int NOT NULL,
  `client_id` int NOT NULL,
  `address_id` int DEFAULT NULL,
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

INSERT INTO `invoices` (`id`, `created_by`, `client_id`, `address_id`, `invoice_number`, `invoice_date`, `due_date`, `subtotal`, `tax_type`, `tax_rate`, `tax_amount`, `discount`, `total_amount`, `amount_paid`, `due_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(143, 1, 1, NULL, 'INV-20260114-044415', '2026-01-14', '2026-01-21', 32.00, 'GST', 2.00, 0.64, 0.00, 32.64, 22.64, 10.00, 'Partial', 'Generated from cart', '2026-01-14 04:44:15', '2026-01-14 04:57:07'),
(144, 1, 1, NULL, 'SUB-20260114-050813', '2026-01-14', '2026-01-14', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SpMJUFA4kAFMB5R1hqMiTCC', '2026-01-14 05:08:13', '2026-01-14 05:08:45'),
(145, 14, 14, NULL, 'SUB-20260114-051113', '2026-01-14', '2026-01-14', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SpMMOFA4kAFMB5R2RIMYGa2', '2026-01-14 05:11:13', '2026-01-14 05:11:13'),
(146, 19, 19, NULL, 'SUB-20260114-051508', '2026-01-14', '2026-01-14', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SpMQBFA4kAFMB5R1gUtIpRH', '2026-01-14 05:15:08', '2026-01-14 05:15:08'),
(147, 20, 20, NULL, 'INV-20260114-062639', '2026-01-14', '2026-01-21', 307.10, 'GST', 4.07, 12.00, 0.00, 307.10, 125.00, 182.10, 'Partial', 'Partial payment via Stripe | Transaction ID: ch_3SpNXOFA4kAFMB5R189DBYPY', '2026-01-14 06:26:39', '2026-01-14 06:26:39'),
(155, 20, 20, NULL, 'INV-20260114-100215', '2026-01-14', '2026-01-21', 167.10, 'GST', 0.00, 0.00, 25.06, 142.04, 90.00, 52.04, 'Partial', 'Partial payment via Stripe | Transaction ID: ch_3SpQu2FA4kAFMB5R05oqZIIg | Subscription Discount: 15%', '2026-01-14 10:02:15', '2026-01-14 10:03:49'),
(173, 20, 20, NULL, 'INV-20260115-110821', '2026-01-15', '2026-01-22', 226.10, 'GST', 13.05, 26.10, 58.79, 167.31, 167.31, 0.00, 'Paid', 'Partial payment via Stripe | Transaction ID: ch_3SpoPYFA4kAFMB5R0Xvho1fc | Subscription Discount: 26%', '2026-01-15 11:08:21', '2026-01-15 11:11:44'),
(180, 1, 1, 8, 'INV-20260116-063336', '2026-01-16', '2026-01-16', 218.52, 'GST', 8.72, 17.52, 0.00, 218.52, 218.52, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sq6bDFA4kAFMB5R0IoqgzrL', '2026-01-16 06:33:36', '2026-01-16 06:33:36'),
(181, 1, 1, 22, 'INV-20260116-065351', '2026-01-16', '2026-01-16', 131.16, 'GST', 6.63, 8.16, 0.00, 131.16, 131.16, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sq6uoFA4kAFMB5R1ODfrH1V', '2026-01-16 06:53:51', '2026-01-16 06:53:51'),
(183, 1, 1, 23, 'INV-20260116-091656', '2026-01-16', '2026-01-23', 6698.84, 'GST', 11.89, 711.84, 535.91, 6162.93, 3000.00, 3162.93, 'Partial', 'Partial payment via Stripe | Transaction ID: ch_3Sq99HFA4kAFMB5R0Ar7IJ7r | Subscription Discount: 8%', '2026-01-16 09:16:56', '2026-01-16 09:16:56'),
(184, 1, 1, 22, 'INV-20260116-120829', '2026-01-16', '2026-01-23', 153.36, 'GST', 11.13, 15.36, 12.27, 141.09, 141.09, 0.00, 'Paid', 'Partial payment via Stripe | Transaction ID: ch_3SqBpIFA4kAFMB5R0a7E7rXd | Subscription Discount: 8%', '2026-01-16 12:08:29', '2026-01-16 12:08:40'),
(185, 1, 1, 22, 'INV-20260116-121304', '2026-01-16', '2026-01-23', 241.00, 'GST', 8.76, 21.12, 0.00, 262.12, 0.00, 262.12, 'Unpaid', 'Generated from cart', '2026-01-16 12:13:04', '2026-01-16 12:13:11'),
(186, 1, 1, 22, 'INV-20260116-121346', '2026-01-16', '2026-01-23', 100.00, 'GST', 12.00, 12.00, 0.00, 112.00, 0.00, 112.00, 'Unpaid', 'Generated from cart', '2026-01-16 12:13:46', '2026-01-16 12:13:52'),
(187, 1, 1, 22, 'INV-20260116-121530', '2026-01-16', '2026-01-23', 123.00, 'GST', 6.63, 8.16, 0.00, 131.16, 0.00, 131.16, 'Unpaid', 'Generated from cart', '2026-01-16 12:15:30', '2026-01-16 12:15:36'),
(188, 1, 1, NULL, 'SUB-20260116-121907', '2026-01-16', '2026-01-16', 1999.00, 'NONE', 0.00, 0.00, 0.00, 1999.00, 1999.00, 0.00, 'Paid', 'Subscription Payment - ultra | Transaction ID: ch_3SqBzaFA4kAFMB5R1ZtHkB2D', '2026-01-16 12:19:07', '2026-01-16 12:19:07'),
(189, 1, 1, 22, 'INV-20260116-122048', '2026-01-16', '2026-01-23', 292.24, 'GST', 11.54, 30.24, 289.32, 2.92, 2.92, 0.00, 'Paid', 'Partial payment via Stripe | Transaction ID: ch_3SqC1DFA4kAFMB5R24uvcKca | Subscription Discount: 99%', '2026-01-16 12:20:48', '2026-01-16 12:20:54'),
(190, 21, 21, NULL, 'SUB-20260116-131733', '2026-01-16', '2026-01-16', 1999.00, 'NONE', 0.00, 0.00, 0.00, 1999.00, 1999.00, 0.00, 'Paid', 'Subscription Payment - ultra | Transaction ID: ch_3SqCu8FA4kAFMB5R1tbvoGpd', '2026-01-16 13:17:33', '2026-01-16 13:17:33'),
(191, 21, 21, NULL, 'SUB-20260116-131834', '2026-01-16', '2026-01-16', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SqCv7FA4kAFMB5R0TejALne', '2026-01-16 13:18:34', '2026-01-16 13:18:34'),
(192, 21, 21, NULL, 'SUB-20260116-131906', '2026-01-16', '2026-01-16', 223.00, 'NONE', 0.00, 0.00, 0.00, 223.00, 223.00, 0.00, 'Paid', 'Subscription Payment - j390h | Transaction ID: ch_3SqCveFA4kAFMB5R053IzIK2', '2026-01-16 13:19:06', '2026-01-16 13:19:06'),
(193, 21, 21, NULL, 'SUB-20260116-131957', '2026-01-16', '2026-01-16', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SqCwTFA4kAFMB5R0rGI6pp9', '2026-01-16 13:19:57', '2026-01-16 13:19:57'),
(194, 21, 21, 24, 'INV-20260116-132214', '2026-01-16', '2026-01-23', 280.88, 'GST', 10.58, 26.88, 33.71, 247.17, 247.17, 0.00, 'Paid', 'Partial payment via Stripe | Transaction ID: ch_3SqCyfFA4kAFMB5R0mgjoMkt | Subscription Discount: 12%', '2026-01-16 13:22:14', '2026-01-16 13:25:55'),
(195, 21, 21, 24, 'INV-20260116-132915', '2026-01-16', '2026-01-23', 172.48, 'GST', 12.00, 18.48, 20.70, 151.78, 151.78, 0.00, 'Paid', 'Partial payment via Stripe | Transaction ID: ch_3SqD5SFA4kAFMB5R2u0UuOML | Subscription Discount: 12%', '2026-01-16 13:29:15', '2026-01-16 13:29:26'),
(196, 21, 21, 25, 'INV-20260116-134151', '2026-01-16', '2026-01-23', 22.40, 'GST', 12.00, 2.40, 2.69, 19.71, 19.71, 0.00, 'Paid', 'Partial payment via Stripe | Transaction ID: ch_3SqDHeFA4kAFMB5R1mzZbn5R | Subscription Discount: 12%', '2026-01-16 13:41:51', '2026-01-16 13:42:00'),
(197, 23, 23, 27, 'INV-20260119-042156', '2026-01-19', '2026-01-19', 109.76, 'GST', 12.00, 11.76, 0.00, 109.76, 109.76, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sr9yRFA4kAFMB5R1pRELWla', '2026-01-19 04:21:56', '2026-01-19 04:21:56'),
(198, 23, 23, 26, 'INV-20260119-042315', '2026-01-19', '2026-01-19', 10.00, 'GST', 0.00, 0.00, 0.00, 10.00, 10.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sr9ziFA4kAFMB5R2Y0NAq1W', '2026-01-19 04:23:15', '2026-01-19 04:23:15'),
(199, 23, 23, 26, 'INV-20260119-042455', '2026-01-19', '2026-01-26', 132.00, 'GST', 12.00, 15.84, 0.00, 147.84, 147.84, 0.00, 'Paid', 'Generated from cart', '2026-01-19 04:24:55', '2026-01-19 04:25:35'),
(200, 29, 29, 28, 'INV-20260119-115804', '2026-01-19', '2026-01-19', 131.04, 'GST', 12.00, 14.04, 0.00, 131.04, 131.04, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SrH5rFA4kAFMB5R0ZslZE0i', '2026-01-19 11:58:04', '2026-01-19 11:58:04'),
(201, 31, 31, 29, 'INV-20260119-121246', '2026-01-19', '2026-01-26', 90.00, 'GST', 12.00, 10.80, 0.00, 100.80, 55.00, 45.80, 'Partial', 'Generated from cart', '2026-01-19 12:12:46', '2026-01-19 12:15:31'),
(202, 31, 31, NULL, 'SUB-20260119-121724', '2026-01-19', '2026-01-19', 223.00, 'NONE', 0.00, 0.00, 0.00, 223.00, 223.00, 0.00, 'Paid', 'Subscription Payment - j390h | Transaction ID: ch_3SrHOZFA4kAFMB5R0YDsg5Gv', '2026-01-19 12:17:24', '2026-01-19 12:17:24'),
(203, 31, 31, 29, 'INV-20260119-122025', '2026-01-19', '2026-01-19', 133.28, 'GST', 12.00, 14.28, 34.65, 98.63, 98.63, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SrHRUFA4kAFMB5R0sQGzFXN | Subscription Discount: 26%', '2026-01-19 12:20:25', '2026-01-19 12:20:25'),
(204, 1, 1, NULL, 'SUB-20260120-040502', '2026-01-20', '2026-01-20', 223.00, 'NONE', 0.00, 0.00, 0.00, 223.00, 223.00, 0.00, 'Paid', 'Subscription Payment - j390h | Transaction ID: ch_3SrWBdFA4kAFMB5R0BNtyJIq', '2026-01-20 04:05:02', '2026-01-20 04:05:02');

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
(289, 143, 'soup', 1, 20.00, 20.00),
(290, 143, 'wwwwww', 1, 12.00, 12.00),
(291, 144, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(292, 145, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(293, 146, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(294, 147, 'saviya', 1, 0.10, 0.10),
(295, 147, 'eiuguydf', 1, 55.00, 55.00),
(296, 147, 'Macroni', 4, 60.00, 240.00),
(309, 155, 'saviya', 1, 0.10, 0.10),
(310, 155, 'kdhk', 1, 22.00, 22.00),
(311, 155, 'tyjtyj', 1, 67.00, 67.00),
(312, 155, 'wsssss', 1, 78.00, 78.00),
(344, 173, 'eiuguydf', 1, 55.00, 55.00),
(345, 173, 'tyjtyj', 1, 67.00, 67.00),
(346, 173, 'wsssss', 1, 78.00, 78.00),
(359, 180, 'wsssss', 1, 78.00, 78.00),
(360, 180, 'tyjtyj', 1, 68.00, 68.00),
(361, 180, 'eiuguydf', 1, 55.00, 55.00),
(362, 181, 'eiuguydf', 1, 55.00, 55.00),
(363, 181, 'tyjtyj', 1, 68.00, 68.00),
(365, 183, 'wsssss', 19, 78.00, 1482.00),
(366, 183, 'tyjtyj', 1, 68.00, 68.00),
(367, 183, 'eiuguydf', 1, 55.00, 55.00),
(368, 183, 'Raghav', 17, 32.00, 544.00),
(369, 183, 'wwwwww', 16, 12.00, 192.00),
(370, 183, 'soup', 14, 20.00, 280.00),
(371, 183, 'Namkeen', 99, 34.00, 3366.00),
(372, 184, 'sjjsgwgj', 1, 30.00, 30.00),
(373, 184, 'kdhk', 1, 10.00, 10.00),
(374, 184, 'Rajma', 1, 32.00, 32.00),
(375, 184, 'wwwwww', 1, 12.00, 12.00),
(376, 184, 'soup', 1, 20.00, 20.00),
(377, 184, 'Namkeen', 1, 34.00, 34.00),
(378, 185, 'eiuguydf', 1, 55.00, 55.00),
(379, 185, 'tyjtyj', 1, 68.00, 68.00),
(380, 185, 'wsssss', 1, 78.00, 78.00),
(381, 185, 'kdhk', 1, 10.00, 10.00),
(382, 185, 'sjjsgwgj', 1, 30.00, 30.00),
(383, 186, 'chowmin', 1, 46.00, 46.00),
(384, 186, 'Namkeen', 1, 34.00, 34.00),
(385, 186, 'soup', 1, 20.00, 20.00),
(386, 187, 'eiuguydf', 1, 55.00, 55.00),
(387, 187, 'tyjtyj', 1, 68.00, 68.00),
(388, 188, 'Subscription: ultra (Yearly)', 1, 1999.00, 1999.00),
(389, 189, 'wsssss', 1, 78.00, 78.00),
(390, 189, 'kdhk', 1, 10.00, 10.00),
(391, 189, 'sjjsgwgj', 1, 30.00, 30.00),
(392, 189, 'Rajma', 1, 32.00, 32.00),
(393, 189, 'wwwwww', 1, 12.00, 12.00),
(394, 189, 'soup', 1, 20.00, 20.00),
(395, 189, 'chowmin', 1, 46.00, 46.00),
(396, 189, 'Namkeen', 1, 34.00, 34.00),
(397, 190, 'Subscription: ultra (Yearly)', 1, 1999.00, 1999.00),
(398, 191, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(399, 192, 'Subscription: j390h (Monthly)', 1, 223.00, 223.00),
(400, 193, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00),
(401, 194, 'tyjtyj', 1, 68.00, 68.00),
(402, 194, 'wsssss', 2, 78.00, 156.00),
(403, 194, 'kdhk', 3, 10.00, 30.00),
(404, 195, 'Namkeen', 1, 34.00, 34.00),
(405, 195, 'Besan', 4, 30.00, 120.00),
(406, 196, 'soup', 1, 20.00, 20.00),
(407, 197, 'Namkeen', 1, 34.00, 34.00),
(408, 197, 'soup', 1, 20.00, 20.00),
(409, 197, 'wwwwww', 1, 12.00, 12.00),
(410, 197, 'Rajma', 1, 32.00, 32.00),
(411, 198, 'kdhk', 1, 10.00, 10.00),
(412, 199, 'wsssss', 1, 78.00, 78.00),
(413, 199, 'Namkeen', 1, 34.00, 34.00),
(414, 199, 'soup', 1, 20.00, 20.00),
(415, 200, 'wwwwww', 1, 12.00, 12.00),
(416, 200, 'sjjsgwgj', 1, 30.00, 30.00),
(417, 200, 'chowmin', 1, 46.00, 46.00),
(418, 200, 'saviya', 1, 29.00, 29.00),
(419, 201, 'Macroni', 1, 60.00, 60.00),
(420, 201, 'Besan', 1, 30.00, 30.00),
(421, 202, 'Subscription: j390h (Monthly)', 1, 223.00, 223.00),
(422, 203, 'Macroni', 1, 60.00, 60.00),
(423, 203, 'Besan', 1, 30.00, 30.00),
(424, 203, 'saviya', 1, 29.00, 29.00),
(425, 204, 'Subscription: j390h (Monthly)', 1, 223.00, 223.00);

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
(23, 143, 1, 32.64, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-14 04:44:15'),
(24, 143, 1, 22.64, 'stripe', 'ch_3SpM8kFA4kAFMB5R1a0SiMx6', 'completed', 'Partial payment', '2026-01-14 04:57:07'),
(25, 144, 1, 100.00, 'stripe', 'ch_3SpMK0FA4kAFMB5R1R7LQw1E', 'completed', 'Full payment', '2026-01-14 05:08:45'),
(26, 146, 19, 555.00, 'stripe', 'ch_3SpMQBFA4kAFMB5R1gUtIpRH', 'completed', 'Subscription Payment - Pro1', '2026-01-14 05:15:12'),
(28, 147, 20, 125.00, 'stripe', 'ch_3SpNXOFA4kAFMB5R189DBYPY', 'completed', 'Partial payment via Stripe', '2026-01-14 06:26:39'),
(36, 155, 20, 50.00, 'stripe', 'ch_3SpQu2FA4kAFMB5R05oqZIIg', 'completed', 'Partial payment via Stripe', '2026-01-14 10:02:15'),
(37, 155, 20, 40.00, 'stripe', 'ch_3SpQvYFA4kAFMB5R2mQ22Xv7', 'completed', 'Partial payment', '2026-01-14 10:03:49'),
(56, 173, 20, 167.31, 'stripe', 'ch_3SpoPYFA4kAFMB5R0Xvho1fc', 'completed', 'Partial payment via Stripe', '2026-01-15 11:08:21'),
(63, 180, 1, 218.52, 'stripe', 'ch_3Sq6bDFA4kAFMB5R0IoqgzrL', 'completed', 'Full payment via Stripe', '2026-01-16 06:33:36'),
(64, 181, 1, 131.16, 'stripe', 'ch_3Sq6uoFA4kAFMB5R1ODfrH1V', 'completed', 'Full payment via Stripe', '2026-01-16 06:53:51'),
(69, 183, 1, 3000.00, 'stripe', 'ch_3Sq99HFA4kAFMB5R0Ar7IJ7r', 'completed', 'Partial payment via Stripe', '2026-01-16 09:16:56'),
(70, 184, 1, 141.09, 'stripe', 'ch_3SqBpIFA4kAFMB5R0a7E7rXd', 'completed', 'Partial payment via Stripe', '2026-01-16 12:08:29'),
(71, 185, 1, 262.12, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-16 12:13:04'),
(72, 186, 1, 112.00, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-16 12:13:46'),
(73, 187, 1, 131.16, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-16 12:15:30'),
(74, 188, 1, 1999.00, 'stripe', 'ch_3SqBzaFA4kAFMB5R1ZtHkB2D', 'completed', 'Subscription Payment - ultra', '2026-01-16 12:19:11'),
(75, 189, 1, 2.92, 'stripe', 'ch_3SqC1DFA4kAFMB5R24uvcKca', 'completed', 'Partial payment via Stripe', '2026-01-16 12:20:48'),
(76, 190, 21, 1999.00, 'stripe', 'ch_3SqCu8FA4kAFMB5R1tbvoGpd', 'completed', 'Subscription Payment - ultra', '2026-01-16 13:17:37'),
(77, 191, 21, 100.00, 'stripe', 'ch_3SqCv7FA4kAFMB5R0TejALne', 'completed', 'Subscription Payment - Basic', '2026-01-16 13:18:38'),
(78, 192, 21, 223.00, 'stripe', 'ch_3SqCveFA4kAFMB5R053IzIK2', 'completed', 'Subscription Payment - j390h', '2026-01-16 13:19:10'),
(79, 193, 21, 555.00, 'stripe', 'ch_3SqCwTFA4kAFMB5R0rGI6pp9', 'completed', 'Subscription Payment - Pro1', '2026-01-16 13:20:03'),
(80, 194, 21, 25.00, 'stripe', 'ch_3SqCyfFA4kAFMB5R0mgjoMkt', 'completed', 'Partial payment via Stripe', '2026-01-16 13:22:14'),
(81, 194, 21, 222.17, 'stripe', 'ch_3SqD2EFA4kAFMB5R0yigFqV8', 'completed', 'Full payment', '2026-01-16 13:25:55'),
(82, 195, 21, 151.78, 'stripe', 'ch_3SqD5SFA4kAFMB5R2u0UuOML', 'completed', 'Partial payment via Stripe', '2026-01-16 13:29:15'),
(83, 196, 21, 19.71, 'stripe', 'ch_3SqDHeFA4kAFMB5R1mzZbn5R', 'completed', 'Partial payment via Stripe', '2026-01-16 13:41:51'),
(84, 197, 23, 109.76, 'stripe', 'ch_3Sr9yRFA4kAFMB5R1pRELWla', 'completed', 'Full payment via Stripe', '2026-01-19 04:21:56'),
(85, 198, 23, 10.00, 'stripe', 'ch_3Sr9ziFA4kAFMB5R2Y0NAq1W', 'completed', 'Full payment via Stripe', '2026-01-19 04:23:15'),
(86, 199, 23, 147.84, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-19 04:24:55'),
(87, 199, 23, 100.00, 'stripe', 'ch_3SrA1iFA4kAFMB5R1UlPX6du', 'completed', 'Partial payment', '2026-01-19 04:25:19'),
(88, 199, 23, 47.84, 'stripe', 'ch_3SrA1yFA4kAFMB5R0dSNgMml', 'completed', 'Full payment', '2026-01-19 04:25:35'),
(89, 200, 29, 131.04, 'stripe', 'ch_3SrH5rFA4kAFMB5R0ZslZE0i', 'completed', 'Full payment via Stripe', '2026-01-19 11:58:04'),
(90, 201, 31, 100.80, 'cod', NULL, 'pending', 'Cash on Delivery - Payment pending', '2026-01-19 12:12:46'),
(91, 201, 31, 15.00, 'stripe', 'ch_3SrHKoFA4kAFMB5R1GzdcCel', 'completed', 'Partial payment', '2026-01-19 12:13:31'),
(92, 201, 31, 40.00, 'stripe', 'ch_3SrHMkFA4kAFMB5R0Bt0upKY', 'completed', 'Partial payment', '2026-01-19 12:15:31'),
(93, 202, 31, 223.00, 'stripe', 'ch_3SrHOZFA4kAFMB5R0YDsg5Gv', 'completed', 'Subscription Payment - j390h', '2026-01-19 12:17:27'),
(94, 203, 31, 98.63, 'stripe', 'ch_3SrHRUFA4kAFMB5R0sQGzFXN', 'completed', 'Full payment via Stripe', '2026-01-19 12:20:25'),
(95, 204, 1, 223.00, 'stripe', 'ch_3SrWBdFA4kAFMB5R0BNtyJIq', 'completed', 'Subscription Payment - j390h', '2026-01-20 04:05:06');

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
(17, 'Namkeen', 'namkeeenheghdgigdih1', 34.00, 99, 'default.png', '2025-12-31 12:36:06', '2026-01-20 03:50:46', 0),
(18, 'soup', 'duhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgv', 20.00, 14, '1767616067_slat.jpg', '2026-01-05 12:27:47', '2026-01-13 04:36:27', 0),
(19, 'wwwwww', 'bjdgehfdukew', 12.00, 17, 'default.png', '2026-01-05 12:28:09', '2026-01-19 13:16:37', 1),
(23, 'sjjsgwgj', 'wghgxjffhx', 30.00, 20, '1767674894_besan.jpg', '2026-01-06 04:48:14', '2026-01-06 11:17:21', 0),
(28, 'eiuguydf', 'yueuqefdoegduqg', 55.00, 1, 'default.png', '2026-01-07 11:28:07', '2026-01-19 04:43:32', 1),
(31, 'Magigiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 23.68, 10, 'default.png', '2026-01-16 13:32:48', '2026-01-19 13:16:25', 1);

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
(111, 20, 2, '2026-01-14', '2026-01-16', 'cancelled', 1, '2026-01-14 10:00:43'),
(112, 1, 5, '2026-01-14', '2026-01-13', 'expired', 1, '2026-01-14 13:49:28'),
(113, 1, 5, '2026-01-14', '2026-02-14', 'expired', 1, '2026-01-14 13:50:55'),
(114, 1, 5, '2026-01-14', '2026-01-13', 'expired', 1, '2026-01-14 13:52:24'),
(115, 1, 5, '2026-01-01', '2026-02-05', 'expired', 1, '2026-01-14 13:55:41'),
(116, 1, 10, '2026-01-15', '2026-02-15', 'cancelled', 1, '2026-01-15 04:22:47'),
(117, 1, 10, '2025-12-15', '2026-01-15', 'cancelled', 1, '2025-12-15 04:25:27'),
(118, 1, 5, '2026-01-15', '2026-02-15', 'expired', 1, '2026-01-15 04:28:35'),
(119, 13, 10, '2026-01-15', '2026-02-15', 'cancelled', 1, '2026-01-15 04:30:48'),
(120, 1, 5, '2026-01-15', '2026-01-14', 'expired', 1, '2026-01-15 06:21:10'),
(121, 1, 5, '2026-01-15', '2026-02-15', 'cancelled', 1, '2026-01-15 09:40:52'),
(122, 20, 15, '2026-01-15', '2026-02-15', 'cancelled', 1, '2026-01-15 11:07:21'),
(123, 1, 5, '2026-01-16', '2026-02-16', 'cancelled', 1, '2026-01-16 09:11:00'),
(124, 1, 2, '2026-01-16', '2027-01-16', 'cancelled', 1, '2026-01-16 12:19:07'),
(125, 21, 2, '2026-01-16', '2027-01-16', 'cancelled', 1, '2026-01-16 13:17:33'),
(126, 21, 5, '2026-01-16', '2026-02-16', 'cancelled', 1, '2026-01-16 13:18:34'),
(127, 21, 15, '2026-01-16', '2026-02-16', 'cancelled', 1, '2026-01-16 13:19:06'),
(128, 21, 10, '2026-01-16', '2026-02-16', 'active', 1, '2026-01-16 13:19:57'),
(129, 31, 15, '2026-01-19', '2026-02-19', 'active', 1, '2026-01-19 12:17:24'),
(130, 1, 15, '2026-01-05', '2026-01-18', 'expired', 1, '2026-01-20 04:05:02');

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
(2, 'ultra', 1999.00, 50, 'yearly', 'Advance feature plans ', '2025-12-26 09:23:51'),
(5, 'Basic', 100.00, 100, 'monthly', 'basic plan', '2025-12-26 12:59:23'),
(10, 'Pro1', 555.00, 12, 'monthly', 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest', '2026-01-05 11:18:34'),
(15, 'j390h', 223.00, 26, 'monthly', 'gwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwwwgwdhfwwwwwww', '2026-01-15 07:49:28');

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
(20, 'Amit', 'amitbatra141@yopmail.com', '$2y$12$mhCQqAcGjYe8VDwE0sjDXuqcrTdlavYn0ZxHv4DAvRj0MmKDKr2.S', '2026-01-14 11:51:14', 'user'),
(21, 'Amit', 'amitbatra151@yopmail.com', '$2y$12$oxDTAvr9mTECSnoi5RHhfuvLMXr0nO9EWGkzfvu1lF5m5QNaIyp7e', '2026-01-16 18:45:55', 'user'),
(22, 'Amit', 'amit161@gmail.com', '$2y$12$91JBFxJtTEbQDPvgMm.n1OxCS9GgWiC81tlACA3OY3DnVwqUlaquO', '2026-01-19 09:32:43', 'user'),
(23, 'Amit', 'amitbatra161@yopmail.com', '$2y$12$oeoYRUzFE1eL5ggXhM044e/kjldZJprkI5aLULEWAqE2qh9lZ2bpa', '2026-01-19 09:34:22', 'user'),
(24, 'Raghavvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv', 'hitesh21@gmail.com', '$2y$12$aN6jiJJZWvOHg3nhnVtf.usdnvwXqfKlugukWPPKrfMQ9tkfuXOKK', '2026-01-19 15:25:07', 'user'),
(25, 'Amit', 'amitbatra171@yopmail.com', '$2y$12$7HzSmD5d4BmUl4sOvUDI8.qUdnG9pBp4nB6Xo83KaKv6duiX84WGe', '2026-01-19 16:45:52', 'user'),
(26, 'chowmin', 'raghav211@yopmail.com', '$2y$12$UdlgGDqsTHq4PcmYesbMYOcxPPgLZY2S6r7UEj2hxMrkYGyxVXyb2', '2026-01-19 16:48:09', 'user'),
(27, 'Raghav', 'raghav31@yopmail.com', '$2y$12$TULhHCWBIkKD8m9hqHG96OcpvJYOSYNK9pVrSV7z1y50k4afLuJQy', '2026-01-19 16:48:49', 'user'),
(28, 'Raghav122', 'raghav22@yopmail.com', '$2y$12$GoCUT9z62qGTe9ejr1DNhOlmV3XPd9GMtVoN4n4xRu4C37f/sSGnS', '2026-01-19 16:54:24', 'user'),
(29, 'Hitesh', 'hitesh30@yopmail.com', '$2y$12$WtmH3qVCTw7JO021I2lrk.dE9kSHTLdqLun/Mem.2cpNXRl4sdiH2', '2026-01-19 17:22:07', 'user'),
(30, 'Raghav', 'dheiugfduy@gmail.com', '$2y$12$QWdvCToYnxOgTdz8YuXF9uGN3MUqdbLDHu.zhpQrZWOgHtJuGf87G', '2026-01-19 17:39:42', 'user'),
(31, 'saviyaQ', 'kashishm211@gmail.com', '$2y$12$kznZh5fveQKnzdIz4NATY.OGdxLC6bbCxBJZvZxrZNUPKWaCAGBF6', '2026-01-19 17:41:31', 'user'),
(32, 'Raghavvvvvvvvvvvvvvvvvvvvvvvvvv11vvvvvvvvvvvvvvvvvvvchopra', 'vishal111111111111111qqqqqqq1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111rana@yopmail.com', '$2y$12$Yc0n0x92em7lvqwRlnK33.u2.oOE21q/1QKl2NMLUSq/011trJ5PW', '2026-01-19 18:34:33', 'user');

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
(6, 11, '45ww', '5444444444', 't54wwwwwww', 't54', '45wt', '222222', 0, '2026-01-06 09:26:59'),
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
(22, 1, 'Raghav Yadav', '8793649873', 'test', 'mohali', 'punjab', '132566', 1, '2026-01-14 09:07:17'),
(23, 1, 'Kashish', '7097968327', 'krishna colony', 'panipat', 'haryana', '229272', 0, '2026-01-16 09:16:22'),
(24, 21, 'Rajan', '9848486464', '321', 'Mohali', 'Punjab', '160058', 1, '2026-01-16 13:21:43'),
(25, 21, 'Sanjay', '9746464515', '789', 'Chandigarh', 'Chandigarh', '160019', 0, '2026-01-16 13:26:57'),
(26, 23, 'Amit', '8787867676', 'bjkbdjcb', 'hansi', 'haryana', '125033', 1, '2026-01-19 04:05:10'),
(27, 23, 'kajal', '6967646187', 'bjkbdjcb', 'hansi', 'haryana', '125033', 0, '2026-01-19 04:05:38'),
(28, 29, 'hitesh', '8787867676', '645/13', 'hansi', 'haryana', '125033', 1, '2026-01-19 11:57:27'),
(29, 31, 'Amit', '7408787382', 'hyipbyvt', 'Chandigarh', 'Chandigarh', '125458', 1, '2026-01-19 12:12:41');

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
  ADD KEY `client_id` (`client_id`),
  ADD KEY `address_id` (`address_id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
  ADD CONSTRAINT `invoices_address_fk` FOREIGN KEY (`address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL,
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
