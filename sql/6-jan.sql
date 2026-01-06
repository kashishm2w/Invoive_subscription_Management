-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 06, 2026 at 10:33 AM
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `user_id`, `company_name`, `email`, `phone`, `address`, `tax_number`, `created_at`) VALUES
(1, 4, 'Invoice and sub', 'admin@gmail.com', '7408787382', 'Hisar ,op. jindal\r\n', '22AAAAA0000A1Z5', '2025-12-29 11:49:11');

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
  `status` enum('Unpaid','Paid','Overdue') NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `created_by`, `client_id`, `invoice_number`, `invoice_date`, `due_date`, `subtotal`, `tax_type`, `tax_rate`, `tax_amount`, `discount`, `total_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(44, 1, 1, 'INV-20251229-101733', '2025-12-29', '2026-01-05', 15.00, 'GST', 18.00, 2.70, 0.00, 17.70, 'Unpaid', 'Generated from cart', '2025-12-29 10:17:33', '2025-12-29 10:17:33'),
(51, 13, 13, 'SUB-20251230-105205', '2025-12-30', '2025-12-30', 100.00, 'GST', 18.00, 18.00, 0.00, 118.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3Sk0X2FA4kAFMB5R1igIIydU', '2025-12-30 10:52:05', '2025-12-30 10:52:05'),
(53, 1, 1, 'INV-20251230-115847', '2025-12-30', '2026-01-06', 574.00, 'GST', 1.41, 8.07, 0.00, 582.07, 'Unpaid', 'Generated from cart', '2025-12-30 11:58:47', '2025-12-30 11:58:47'),
(54, 1, 1, 'INV-20251230-130519', '2025-12-30', '2026-01-06', 426.50, 'GST', 1.46, 6.23, 0.00, 432.73, 'Unpaid', 'Generated from cart', '2025-12-30 13:05:19', '2025-12-30 13:05:19'),
(55, 1, 1, 'INV-20251230-131537', '2025-12-30', '2026-01-06', 14.00, 'GST', 2.00, 0.28, 0.00, 14.28, 'Unpaid', 'Generated from cart', '2025-12-30 13:15:37', '2025-12-30 13:15:37'),
(56, 11, 11, 'SUB-20251230-133035', '2025-12-30', '2025-12-30', 350.00, 'GST', 18.00, 63.00, 0.00, 413.00, 'Paid', 'Subscription Payment - Pro | Transaction ID: ch_3Sk30PFA4kAFMB5R299fSDnt', '2025-12-30 13:30:35', '2025-12-30 13:30:35'),
(57, 11, 11, 'INV-20251231-053511', '2025-12-31', '2026-01-07', 104.50, 'GST', 5.69, 5.95, 0.00, 110.45, 'Unpaid', 'Generated from cart', '2025-12-31 05:35:11', '2025-12-31 05:35:11'),
(58, 1, 1, 'INV-20251231-055114', '2025-12-31', '2026-01-07', 31.00, 'GST', 18.00, 5.58, 0.00, 36.58, 'Unpaid', 'Generated from cart', '2025-12-31 05:51:14', '2025-12-31 05:51:14'),
(59, 1, 1, 'INV-20251231-055147', '2025-12-31', '2025-12-31', 31.00, 'GST', 18.00, 5.58, 0.00, 36.58, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SkIJyFA4kAFMB5R0wETTrtx', '2025-12-31 05:51:47', '2025-12-31 05:51:47'),
(60, 1, 1, 'SUB-20251231-055215', '2025-12-31', '2025-12-31', 500.00, 'GST', 18.00, 90.00, 0.00, 590.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SkIKQFA4kAFMB5R18xiZCK0', '2025-12-31 05:52:15', '2025-12-31 05:52:15'),
(61, 1, 1, 'INV-20251231-063522', '2025-12-31', '2026-01-07', 31.00, 'GST', 18.00, 5.58, 0.00, 36.58, 'Unpaid', 'Generated from cart', '2025-12-31 06:35:22', '2025-12-31 06:35:22'),
(62, 1, 1, 'INV-20251231-064901', '2025-12-31', '2025-12-31', 29.50, 'GST', 1.25, 0.37, 0.00, 29.87, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SkJDMFA4kAFMB5R0cEAJPMr', '2025-12-31 06:49:01', '2025-12-31 06:49:01'),
(63, 14, 14, 'INV-20251231-065152', '2025-12-31', '2025-12-31', 87.50, 'GST', 0.74, 0.65, 0.00, 88.15, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SkJG7FA4kAFMB5R1OgpeEA6', '2025-12-31 06:51:52', '2025-12-31 06:51:52'),
(64, 14, 14, 'SUB-20251231-065236', '2025-12-31', '2025-12-31', 500.00, 'GST', 18.00, 90.00, 0.00, 590.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SkJGpFA4kAFMB5R2LP8v4KL', '2025-12-31 06:52:36', '2025-12-31 06:52:36'),
(65, 1, 1, 'INV-20251231-115917', '2025-12-31', '2026-01-07', 117.70, 'GST', 5.45, 6.42, 0.00, 124.12, 'Unpaid', 'Generated from cart', '2025-12-31 11:59:17', '2025-12-31 11:59:17'),
(66, 1, 1, 'INV-20251231-131836', '2025-12-31', '2025-12-31', 89.70, 'GST', 6.53, 5.86, 0.00, 95.56, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SkPINFA4kAFMB5R0coFldVv', '2025-12-31 13:18:36', '2025-12-31 13:18:36'),
(67, 1, 1, 'INV-20260105-042050', '2026-01-05', '2026-01-05', 31.00, 'GST', 18.00, 5.58, 0.00, 36.58, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sm5HhFA4kAFMB5R1pelEsz4', '2026-01-05 04:20:50', '2026-01-05 04:20:50'),
(68, 1, 1, 'INV-20260105-054132', '2026-01-05', '2026-01-05', 46.00, 'GST', 3.00, 1.38, 0.00, 47.38, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sm6XnFA4kAFMB5R1IGB6c57', '2026-01-05 05:41:32', '2026-01-05 05:41:32'),
(72, 16, 16, 'INV-20260105-114441', '2026-01-05', '2026-01-12', 109.00, 'GST', 2.73, 2.98, 0.00, 111.98, 'Unpaid', 'Generated from cart', '2026-01-05 11:44:41', '2026-01-05 11:44:41'),
(73, 16, 16, 'INV-20260105-120211', '2026-01-05', '2026-01-12', 1079.00, 'GST', 2.70, 29.18, 0.00, 1108.18, 'Unpaid', 'Generated from cart', '2026-01-05 12:02:11', '2026-01-05 12:02:11'),
(74, 16, 16, 'SUB-20260105-120315', '2026-01-05', '2026-01-05', 100.00, 'GST', 18.00, 18.00, 0.00, 118.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SmCVCFA4kAFMB5R0678u5vQ', '2026-01-05 12:03:15', '2026-01-05 12:03:15'),
(75, 11, 11, 'INV-20260106-045549', '2026-01-06', '2026-01-06', 348.00, 'GST', 20.24, 70.42, 0.00, 418.42, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmSJ6FA4kAFMB5R0gTLupnS', '2026-01-06 04:55:49', '2026-01-06 04:55:49'),
(76, 17, 17, 'SUB-20260106-063440', '2026-01-06', '2026-01-06', 294.00, 'GST', 18.00, 52.92, 0.00, 346.92, 'Paid', 'Subscription Payment - Pro | Transaction ID: ch_3SmTqlFA4kAFMB5R0v1nmIos', '2026-01-06 06:34:40', '2026-01-06 06:34:40'),
(77, 17, 17, 'INV-20260106-090300', '2026-01-06', '2026-01-06', 51.00, 'GST', 7.49, 3.82, 0.00, 54.82, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmWAKFA4kAFMB5R1VvYew1n', '2026-01-06 09:03:00', '2026-01-06 09:03:00'),
(78, 11, 11, 'INV-20260106-092304', '2026-01-06', '2026-01-13', 933.00, 'GST', 2.95, 27.54, 0.00, 960.54, 'Unpaid', 'Generated from cart', '2026-01-06 09:23:04', '2026-01-06 09:23:04');

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
(105, 44, 'maggie', 1, 15.00, 15.00),
(114, 51, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(116, 53, 'Maggie', 1, 14.00, 14.00),
(117, 53, 'sugar', 8, 44.00, 352.00),
(118, 53, 'Salt -TATA', 6, 29.50, 177.00),
(119, 53, 'salt', 1, 31.00, 31.00),
(120, 54, 'Salt -TATA', 1, 29.50, 29.50),
(121, 54, 'sugar', 8, 44.00, 352.00),
(122, 54, 'salt', 1, 31.00, 31.00),
(123, 54, 'Maggie', 1, 14.00, 14.00),
(124, 55, 'Maggie', 1, 14.00, 14.00),
(125, 56, 'Subscription: Pro (Monthly)', 1, 350.00, 350.00),
(126, 57, 'salt', 1, 31.00, 31.00),
(127, 57, 'sugar', 1, 44.00, 44.00),
(128, 57, 'Salt -TATA', 1, 29.50, 29.50),
(129, 58, 'salt', 1, 31.00, 31.00),
(130, 59, 'salt', 1, 31.00, 31.00),
(131, 60, 'Subscription: Pro1 (Monthly)', 1, 500.00, 500.00),
(132, 61, 'salt', 1, 31.00, 31.00),
(133, 62, 'Salt -TATA', 1, 29.50, 29.50),
(134, 63, 'Maggie', 1, 14.00, 14.00),
(135, 63, 'Salt -TATA', 1, 29.50, 29.50),
(136, 63, 'sugar', 1, 44.00, 44.00),
(137, 64, 'Subscription: Pro1 (Monthly)', 1, 500.00, 500.00),
(138, 65, 'saviya', 1, 28.00, 28.00),
(139, 65, 'salt', 1, 31.00, 31.00),
(140, 65, 'sugar', 1, 44.70, 44.70),
(141, 65, 'Maggie', 1, 14.00, 14.00),
(142, 66, 'salt', 1, 31.00, 31.00),
(143, 66, 'sugar', 1, 44.70, 44.70),
(144, 66, 'Maggie', 1, 14.00, 14.00),
(145, 67, 'salt', 1, 31.00, 31.00),
(146, 68, 'chowmin', 1, 46.00, 46.00),
(153, 72, 'Namkeen', 1, 34.00, 34.00),
(154, 72, 'chowmin', 1, 46.00, 46.00),
(155, 72, 'saviya', 1, 29.00, 29.00),
(156, 73, 'chowmin', 10, 46.00, 460.00),
(157, 73, 'saviya', 11, 29.00, 319.00),
(158, 73, 'Besan', 10, 30.00, 300.00),
(159, 74, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(160, 75, 'wsijcbibedc', 25, 12.00, 300.00),
(161, 75, 'Raghav', 1, 2.00, 2.00),
(162, 75, 'chowmin', 1, 46.00, 46.00),
(163, 76, 'Subscription: Pro (Monthly)', 1, 294.00, 294.00),
(164, 77, 'wsssss', 1, 1.00, 1.00),
(165, 77, 'kdhk', 1, 2.00, 2.00),
(166, 77, 'wsijcbibedc', 1, 12.00, 12.00),
(167, 77, 'wwwwww', 1, 2.00, 2.00),
(168, 77, 'Namkeen', 1, 34.00, 34.00),
(169, 78, 'Namkeen', 27, 34.00, 918.00),
(170, 78, 'wsssss', 15, 1.00, 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `tax_percent` decimal(5,2) DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` int DEFAULT '1',
  `poster` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `tax_percent`, `total_amount`, `quantity`, `poster`, `created_at`, `updated_at`) VALUES
(10, 'Maggie', 'bwfwghxcwhxvchedxhwikx1`jvwj', 15.00, 2.00, 0.00, 94, '1767615260_maggie.jpg', '2025-12-30 04:29:06', '2026-01-05 12:14:20'),
(11, 'Macroni', 'Macaroni Classic curved pasta made from premium durum wheat. Perfect for creamy sauces baked dishes and quick comfort meals.', 60.00, 5.00, 0.00, 100, '1767176516_macroni.jpeg', '2025-12-31 07:41:58', '2025-12-31 10:21:56'),
(12, 'Poha', 'Light and healthy flattened rice, quick to cook and perfect for a tasty, nutritious breakfast.', 40.00, 4.00, 0.00, 100, '1767176228_poha.jpg', '2025-12-31 07:43:05', '2025-12-31 10:17:08'),
(13, 'Besan', 'Finely ground gram flour made from quality chickpeas, ideal for snacks, curries, and traditional recipes.', 30.00, 3.00, 0.00, 99, '1767177494_besan.jpg', '2025-12-31 07:47:05', '2025-12-31 11:05:45'),
(15, 'saviya', 'Saviya  Thin vermicelli made from quality wheat perfect for sweet kheer upma and quick meals', 29.00, 2.00, 0.00, 56, '1767177564_saviya.jpg', '2025-12-31 08:00:38', '2026-01-05 12:17:56'),
(16, 'chowmin', 'Chowmein is a popular IndoChinese dish made with stirfried noodles fresh vegetable and savory sauces The noodles are cooked until tender  then tossed in a hot wok with ingredients like cabbage carrots capsicum spring onions and flavored with soy sauce garlic ginger and a hint of vinegar', 46.00, 3.00, 0.00, 67, '1767184498_chowmin.png', '2025-12-31 12:14:03', '2025-12-31 12:34:58'),
(17, 'Namkeen', 'namkeeenheghdgigdih1', 34.00, 3.00, 0.00, 99, '1767184566_Namkeen.png', '2025-12-31 12:36:06', '2026-01-05 12:17:49'),
(18, 'Amit', 'duhevdhgvevdgv', 2.00, 2.00, 0.00, 14, '1767616067_slat.jpg', '2026-01-05 12:27:47', '2026-01-05 12:27:47'),
(19, 'wwwwww', 'wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww', 2.00, 2.00, 0.00, 17, '1767616089_chowmin.png', '2026-01-05 12:28:09', '2026-01-05 12:28:09'),
(20, 'wsijcbibedc', 'ejchhevhcv', 12.00, 23.00, 0.00, 48, '1767616150_Namkeen.png', '2026-01-05 12:29:10', '2026-01-05 12:29:10'),
(21, 'Raghav', 'snsxwbxfweygxhwgu', 2.00, 2.00, 0.00, 17, '1767616521_saviya.jpg', '2026-01-05 12:35:21', '2026-01-05 12:35:43'),
(22, 'whekkkkkkkkkkkkkkdgli', 'wjheedgkwgej', 23.00, 37.00, 0.00, 18, 'default.png', '2026-01-06 04:41:24', '2026-01-06 04:41:24'),
(23, 'sjjsgwgj', 'wghgxjffhx', 3.00, 10.00, 0.00, 20, '1767674894_besan.jpg', '2026-01-06 04:48:14', '2026-01-06 04:48:42'),
(24, 'kdhk', 'wzxsxdedcfwerf', 2.00, 0.00, 0.00, 22, '1767675059_maggie.jpg', '2026-01-06 04:50:59', '2026-01-06 04:50:59'),
(25, 'wsssss', 'ejskdhhgtc', 1.00, 0.00, 0.00, 21, '1767675091_chowmin.png', '2026-01-06 04:51:31', '2026-01-06 06:21:23'),
(26, '32222222222', 'wdfdffasfg', 2356.00, 0.00, 0.00, 19, '1767675140_maggie.jpg', '2026-01-06 04:52:20', '2026-01-06 10:04:55');

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
(59, 12, 2, '2025-12-30', '2026-01-30', 'active', 0, '2025-12-30 10:40:59'),
(60, 13, 5, '2025-12-30', '2026-01-30', 'active', 1, '2025-12-30 10:52:05'),
(62, 11, 8, '2025-12-30', '2026-01-30', 'active', 1, '2025-12-30 13:30:35'),
(63, 1, 2, '2025-12-31', '2026-01-31', 'active', 1, '2025-12-31 05:52:15'),
(64, 14, 2, '2025-12-31', '2026-01-31', 'active', 1, '2025-12-31 06:52:36'),
(67, 17, 8, '2026-01-06', '2026-02-06', 'active', 1, '2026-01-06 06:34:40');

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
(2, 'Pro1', 500.00, 0, 'monthly', 'Pro plan with extra features', '2025-12-26 09:23:51'),
(5, 'Basic', 100.00, 2, 'monthly', 'basic plan', '2025-12-26 12:59:23'),
(8, 'Pro', 294.00, 20, 'monthly', 'extra feature with basic plan', '2025-12-30 05:04:06'),
(10, 'Pro1', 555.00, 0, 'monthly', 'test', '2026-01-05 11:18:34');

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
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'user',
  `phone_no` varchar(20) DEFAULT NULL,
  `address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`, `phone_no`, `address`) VALUES
(1, 'Raghav', 'raghav@gmail.com', '$2y$12$16rdZcncHE0VEWf/gvBh/eOnUDEJcMJWgdlZBAxnAer237fLMTHPS', '2025-12-19 16:35:57', 'user', '9728389332', 'E-195, Mind2Web,Mohali,Punjab'),
(4, 'Admin', 'admin@gmail.com', '$2y$12$gEJQUCBcksnEamwI/JWKJu.ez.mUWrDGquUvZ.djTXnUVOpTlQRc6', '2025-12-19 17:43:27', 'admin', '7408787382', 'Hisar ,op. jindal\r\n'),
(7, 'Geeta', 'geeta14@gmail.com', '$2y$12$Adhho6mxGe6tn4FVsDAllOys.Uozitft7.4n4TqpY0QBIjyD/WAuO', '2025-12-25 16:37:55', 'user', NULL, NULL),
(8, 'garima', 'g@gmail.co', '$2y$12$ObBfXZBgyzpIAqTxrdblr.fc.puJwCkup.3xAWU7yW0HC.nqgZCzS', '2025-12-26 17:45:28', 'user', NULL, NULL),
(9, 'Garima', 'garima21@gmail.com', '$2y$12$Ib0vR9vyVu5f4ysbMf6nIOFa8kjta/C3rPLTHuLpI2LADMtUsVdPG', '2025-12-26 17:48:11', 'user', NULL, NULL),
(10, 'Kashish Mittal', 'kashumittal0201@gmail.com', '$2y$12$jXC1z.SyJbizem8dQsmxqejO2w1a2SXoWpQTU5cb6tejZE8eR1aIu', '2025-12-29 12:31:04', 'user', NULL, NULL),
(11, 'Vishal', 'vishal1533rana@gmail.com', '$2y$12$OG94f3ktYiodkk/eqrfFWuflXphZ.To8Lz/OQ7urJwliNOAS0tr2m', '2025-12-29 12:42:45', 'user', NULL, NULL),
(12, 'Liza', 'liza21@gmail.com', '$2y$12$xPcUoCeGCtjYSOm1xtURQuSTxgF1EaFqF9WdamwlMrgZUwxKJDtS2', '2025-12-30 16:10:35', 'user', NULL, NULL),
(13, 'Yash', 'yash21@gmail.com', '$2y$12$WThSX8/QYx.hBoY1pGlQb.Gel4kYYg.pTHj3XTH8VufjZvPOAs5zG', '2025-12-30 16:19:33', 'user', NULL, NULL),
(14, 'Liza Gupta', 'liza21@yopmail.com', '$2y$12$IetET2i4Lcmo7Y8LjZG9fOdZpBavR/uEgZoKm6DreK8T.aGj5O0iC', '2025-12-31 12:20:59', 'user', NULL, NULL),
(16, 'Gaurav', 'gaurav21@gmail.com', '$2y$12$4uBk/sdGmkDheGG/lNhMy.s6rS/RuxILkfSTdQQomO8rzhYAlq5WO', '2026-01-05 17:14:21', 'user', '9728389339', 'mkdjnjWHFGURG'),
(17, 'Amit', 'amitbatra121@yopmail.com', '$2y$12$4/aDubgqY0.EsfMIh0n2aO4N6T00R5s0QVsYTS8wfI1ZwgOuuFUwy', '2026-01-06 11:25:37', 'user', NULL, NULL);

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
(1, 1, 'Raghav', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 1, '2026-01-06 07:58:36'),
(2, 17, 'Amit', '7393872897', 'mind 2 web', 'moali', 'punjab', '235178', 1, '2026-01-06 09:01:53'),
(3, 17, 'amit', '8787867676', '2353,sector71', 'moali', 'punjab', '235178', 0, '2026-01-06 09:07:36'),
(4, 11, 'vishal', '7389965388', '3567, sector 3', 'panipat', 'haryana', '229272', 0, '2026-01-06 09:23:00'),
(5, 11, '456464', '5555555555', 'fvvtghb', '35535', '235', '234453', 0, '2026-01-06 09:26:22'),
(6, 11, '45wwwwwwwwwwwww', '5444444444', 't54wwwwwwwwwwwwwwww', 't54', '45wt', '222222', 0, '2026-01-06 09:26:59'),
(7, 11, 'vishal', '7389965388', '3567, sector 3', 'panipat', 'haryana', '229272', 1, '2026-01-06 09:36:19'),
(8, 1, 'vishal', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-06 09:59:54');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
