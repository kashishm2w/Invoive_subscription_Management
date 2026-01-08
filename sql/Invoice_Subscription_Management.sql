-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 26, 2025 at 01:19 PM
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
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `tax_type` enum('GST','NONE') DEFAULT 'GST',
  `tax_rate` decimal(5,2) DEFAULT '0.00',
  `tax_amount` decimal(10,2) DEFAULT '0.00',
  `discount` decimal(10,2) DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('Unpaid','Sent','Paid','Overdue') NOT NULL DEFAULT 'Unpaid',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `created_by`, `client_id`, `invoice_number`, `invoice_date`, `due_date`, `subtotal`, `tax_type`, `tax_rate`, `tax_amount`, `discount`, `total_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'INV-20251224-060443', '2025-12-24', '2025-12-31', 258.00, 'GST', 5.42, 14.00, 0.00, 272.00, 'Unpaid', 'Generated from cart', '2025-12-24 06:04:43', '2025-12-25 05:18:43'),
(2, 1, 1, 'INV-20251224-060721', '2025-12-24', '2025-12-31', 279.50, 'GST', 0.66, 1.84, 0.00, 281.34, 'Unpaid', 'Generated from cart', '2025-12-24 06:07:21', '2025-12-25 05:18:43'),
(3, 1, 1, 'INV-20251224-065900', '2025-12-24', '2025-12-31', 493.00, 'GST', 8.28, 40.82, 0.00, 533.82, 'Unpaid', 'Generated from cart', '2025-12-24 06:59:00', '2025-12-25 05:18:43'),
(4, 1, 1, 'INV-20251224-071041', '2025-12-24', '2025-12-31', 88.00, 'GST', 0.00, 0.00, 0.00, 88.00, 'Unpaid', 'Generated from cart', '2025-12-24 07:10:41', '2025-12-25 05:18:43'),
(5, 1, 1, 'INV-20251224-071901', '2025-12-24', '2025-12-31', 352.00, 'GST', 0.00, 0.00, 0.00, 352.00, 'Unpaid', 'Generated from cart', '2025-12-24 07:19:01', '2025-12-25 05:18:43'),
(6, 1, 1, 'INV-20251224-072601', '2025-12-24', '2025-12-31', 249.50, 'GST', 0.15, 0.37, 0.00, 249.87, 'Unpaid', 'Generated from cart', '2025-12-24 07:26:01', '2025-12-25 05:18:43'),
(7, 1, 1, 'INV-20251224-074107', '2025-12-24', '2025-12-31', 147.50, 'GST', 1.25, 1.84, 0.00, 149.34, 'Unpaid', 'Generated from cart', '2025-12-24 07:41:07', '2025-12-25 05:18:43'),
(8, 1, 1, 'INV-20251224-100922', '2025-12-24', '2025-12-31', 1386.00, 'GST', 5.11, 70.79, 0.00, 1456.79, 'Unpaid', 'Generated from cart', '2025-12-24 10:09:22', '2025-12-25 05:18:43'),
(9, 1, 1, 'INV-20251224-103058', '2025-12-24', '2025-12-31', 910.00, 'GST', 5.69, 51.75, 0.00, 961.75, 'Unpaid', 'Generated from cart', '2025-12-24 10:30:58', '2025-12-25 05:18:43'),
(10, 1, 1, 'INV-20251224-104327', '2025-12-24', '2025-12-31', 962.00, 'GST', 5.93, 57.08, 0.00, 1019.08, 'Unpaid', 'Generated from cart', '2025-12-24 10:43:27', '2025-12-25 05:18:43'),
(11, 1, 1, 'INV-20251224-104558', '2025-12-24', '2025-12-31', 1114.00, 'GST', 5.38, 59.91, 0.00, 1173.91, 'Unpaid', 'Generated from cart', '2025-12-24 10:45:58', '2025-12-25 05:18:43'),
(12, 1, 1, 'INV-20251224-104843', '2025-12-24', '2025-12-31', 545.50, 'GST', 1.86, 10.12, 0.00, 555.62, 'Unpaid', 'Generated from cart', '2025-12-24 10:48:43', '2025-12-25 05:18:43'),
(13, 1, 1, 'INV-20251224-104928', '2025-12-24', '2025-12-31', 579.50, 'GST', 1.98, 11.48, 0.00, 590.98, 'Unpaid', 'Generated from cart', '2025-12-24 10:49:28', '2025-12-25 05:18:43'),
(14, 1, 1, 'INV-20251224-111342', '2025-12-24', '2025-12-31', 124.00, 'GST', 18.00, 22.32, 0.00, 146.32, 'Unpaid', 'Generated from cart', '2025-12-24 11:13:42', '2025-12-25 05:18:43'),
(15, 1, 1, 'INV-20251224-111510', '2025-12-24', '2025-12-31', 44.00, 'GST', 0.00, 0.00, 0.00, 44.00, 'Unpaid', 'Generated from cart', '2025-12-24 11:15:10', '2025-12-25 05:18:43'),
(16, 1, 1, 'INV-20251224-113209', '2025-12-24', '2025-12-31', 903.00, 'GST', 1.90, 17.17, 0.00, 920.17, 'Unpaid', 'Generated from cart', '2025-12-24 11:32:09', '2025-12-25 05:18:43'),
(17, 1, 1, 'INV-20251225-045009', '2025-12-25', '2026-01-01', 0.00, 'GST', 0.00, 0.00, 0.00, 0.00, 'Unpaid', 'Generated from cart', '2025-12-25 04:50:09', '2025-12-25 05:18:43'),
(18, 1, 1, 'INV-20251225-045254', '2025-12-25', '2026-01-01', 835.00, 'GST', 1.73, 14.45, 0.00, 849.45, 'Unpaid', 'Generated from cart', '2025-12-25 04:52:54', '2025-12-25 05:18:43'),
(19, 1, 1, 'INV-20251225-060404', '2025-12-25', '2026-01-01', 309.50, 'GST', 3.48, 10.77, 0.00, 320.27, 'Unpaid', 'Generated from cart', '2025-12-25 06:04:04', '2025-12-25 06:04:04'),
(21, 1, 1, 'INV-20251225-071825', '2025-12-25', '2026-01-01', 592.50, 'GST', 3.14, 18.58, 0.00, 611.08, 'Unpaid', 'Generated from cart', '2025-12-25 07:18:25', '2025-12-25 07:18:25'),
(22, 1, 1, 'INV-20251225-104117', '2025-12-25', '2026-01-01', 8194.00, 'GST', 4.00, 327.76, 0.00, 8521.76, 'Unpaid', 'Generated from cart', '2025-12-25 10:41:17', '2025-12-25 10:41:17'),
(23, 1, 1, 'INV-20251225-104146', '2025-12-25', '2026-01-01', 352.00, 'GST', 0.00, 0.00, 0.00, 352.00, 'Unpaid', 'Generated from cart', '2025-12-25 10:41:46', '2025-12-25 10:41:46'),
(24, 1, 1, 'INV-20251225-104525', '2025-12-25', '2026-01-01', 29.50, 'GST', 1.25, 0.37, 0.00, 29.87, 'Unpaid', 'Generated from cart', '2025-12-25 10:45:25', '2025-12-25 10:45:25'),
(25, 1, 1, 'INV-20251225-104631', '2025-12-25', '2026-01-01', 29.50, 'GST', 1.25, 0.37, 0.00, 29.87, 'Unpaid', 'Generated from cart', '2025-12-25 10:46:31', '2025-12-25 10:46:31'),
(26, 7, 7, 'INV-20251225-110824', '2025-12-25', '2026-01-01', 88.50, 'GST', 1.25, 1.11, 0.00, 89.61, 'Unpaid', 'Generated from cart', '2025-12-25 11:08:24', '2025-12-25 11:08:24'),
(27, 7, 7, 'INV-20251225-114849', '2025-12-25', '2026-01-01', 177.00, 'GST', 1.25, 2.21, 0.00, 179.21, 'Unpaid', 'Generated from cart', '2025-12-25 11:48:49', '2025-12-25 11:48:49'),
(28, 7, 7, 'INV-20251225-115416', '2025-12-25', '2026-01-01', 801.00, 'GST', 1.63, 13.09, 0.00, 814.09, 'Unpaid', 'Generated from cart', '2025-12-25 11:54:16', '2025-12-25 11:54:16'),
(29, 7, 7, 'INV-20251225-115711', '2025-12-25', '2026-01-01', 744.50, 'GST', 6.17, 45.94, 0.00, 790.44, 'Unpaid', 'Generated from cart', '2025-12-25 11:57:11', '2025-12-25 11:57:11'),
(30, 1, 1, 'INV-20251226-041251', '2025-12-26', '2026-01-02', 1036.00, 'GST', 5.65, 58.55, 0.00, 1094.55, 'Unpaid', 'Generated from cart', '2025-12-26 04:12:51', '2025-12-26 04:12:51'),
(31, 1, 1, 'INV-20251226-043749', '2025-12-26', '2026-01-02', 29.50, 'GST', 1.25, 0.37, 0.00, 29.87, 'Unpaid', 'Generated from cart', '2025-12-26 04:37:49', '2025-12-26 04:37:49'),
(32, 1, 1, 'INV-20251226-043846', '2025-12-26', '2026-01-02', 73.50, 'GST', 0.50, 0.37, 0.00, 73.87, 'Unpaid', 'Generated from cart', '2025-12-26 04:38:46', '2025-12-26 04:38:46'),
(33, 9, 9, 'INV-20251226-122035', '2025-12-26', '2026-01-02', 272.00, 'GST', 4.00, 10.88, 0.00, 282.88, 'Unpaid', 'Generated from cart', '2025-12-26 12:20:35', '2025-12-26 12:20:35');

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
(1, 1, 'sugar', 1, 44.00, 44.00),
(2, 1, 'Salt -TATA', 4, 29.50, 118.00),
(3, 1, 'salt', 2, 31.00, 62.00),
(4, 1, 'biscuit', 1, 34.00, 34.00),
(5, 2, 'Salt -TATA', 5, 29.50, 147.50),
(6, 2, 'sugar', 3, 44.00, 132.00),
(7, 3, 'maggie', 3, 15.00, 45.00),
(8, 3, 'salt', 5, 31.00, 155.00),
(9, 3, 'sugar', 3, 44.00, 132.00),
(10, 3, 'Salt -TATA', 2, 29.50, 59.00),
(11, 3, 'biscuit', 3, 34.00, 102.00),
(12, 4, 'sugar', 2, 44.00, 88.00),
(13, 5, 'sugar', 8, 44.00, 352.00),
(14, 6, 'Salt -TATA', 1, 29.50, 29.50),
(15, 6, 'sugar', 5, 44.00, 220.00),
(16, 7, 'Salt -TATA', 5, 29.50, 147.50),
(17, 8, 'sugar', 8, 44.00, 352.00),
(18, 8, 'salt', 5, 31.00, 155.00),
(19, 8, 'maggie', 6, 15.00, 90.00),
(20, 8, 'Salt -TATA', 6, 29.50, 177.00),
(21, 8, 'biscuit', 18, 34.00, 612.00),
(22, 9, 'biscuit', 4, 34.00, 136.00),
(23, 9, 'Salt -TATA', 6, 29.50, 177.00),
(24, 9, 'sugar', 8, 44.00, 352.00),
(25, 9, 'salt', 5, 31.00, 155.00),
(26, 9, 'maggie', 6, 15.00, 90.00),
(27, 10, 'maggie', 6, 15.00, 90.00),
(28, 10, 'salt', 5, 31.00, 155.00),
(29, 10, 'sugar', 8, 44.00, 352.00),
(30, 10, 'Salt -TATA', 2, 29.50, 59.00),
(31, 10, 'biscuit', 9, 34.00, 306.00),
(32, 11, 'biscuit', 10, 34.00, 340.00),
(33, 11, 'Salt -TATA', 6, 29.50, 177.00),
(34, 11, 'sugar', 8, 44.00, 352.00),
(35, 11, 'salt', 5, 31.00, 155.00),
(36, 11, 'maggie', 6, 15.00, 90.00),
(37, 12, 'Salt -TATA', 5, 29.50, 147.50),
(38, 12, 'sugar', 8, 44.00, 352.00),
(39, 12, 'salt', 1, 31.00, 31.00),
(40, 12, 'maggie', 1, 15.00, 15.00),
(41, 13, 'biscuit', 1, 34.00, 34.00),
(42, 13, 'Salt -TATA', 5, 29.50, 147.50),
(43, 13, 'sugar', 8, 44.00, 352.00),
(44, 13, 'salt', 1, 31.00, 31.00),
(45, 13, 'maggie', 1, 15.00, 15.00),
(46, 14, 'salt', 4, 31.00, 124.00),
(47, 15, 'sugar', 1, 44.00, 44.00),
(48, 16, 'biscuit', 11, 34.00, 374.00),
(49, 16, 'Salt -TATA', 6, 29.50, 177.00),
(50, 16, 'sugar', 8, 44.00, 352.00),
(51, 17, 'biscuit', 0, 34.00, 0.00),
(52, 18, 'biscuit', 9, 34.00, 306.00),
(53, 18, 'Salt -TATA', 6, 29.50, 177.00),
(54, 18, 'sugar', 8, 44.00, 352.00),
(55, 19, 'salt', 1, 31.00, 31.00),
(56, 19, 'sugar', 2, 44.00, 88.00),
(57, 19, 'Salt -TATA', 3, 29.50, 88.50),
(58, 19, 'biscuit', 3, 34.00, 102.00),
(60, 21, 'Salt -TATA', 5, 29.50, 147.50),
(61, 21, 'sugar', 8, 44.00, 352.00),
(62, 21, 'salt', 3, 31.00, 93.00),
(63, 22, 'biscuit', 241, 34.00, 8194.00),
(64, 23, 'sugar', 8, 44.00, 352.00),
(65, 24, 'Salt -TATA', 1, 29.50, 29.50),
(66, 25, 'Salt -TATA', 1, 29.50, 29.50),
(67, 26, 'Salt -TATA', 3, 29.50, 88.50),
(68, 27, 'Salt -TATA', 6, 29.50, 177.00),
(69, 28, 'biscuit', 8, 34.00, 272.00),
(70, 28, 'Salt -TATA', 6, 29.50, 177.00),
(71, 28, 'sugar', 8, 44.00, 352.00),
(72, 29, 'maggie', 6, 15.00, 90.00),
(73, 29, 'salt', 5, 31.00, 155.00),
(74, 29, 'sugar', 8, 44.00, 352.00),
(75, 29, 'Salt -TATA', 5, 29.50, 147.50),
(76, 29, 'biscuit', 0, 34.00, 0.00),
(77, 30, 'maggie', 6, 15.00, 90.00),
(78, 30, 'salt', 5, 31.00, 155.00),
(79, 30, 'sugar', 7, 44.00, 308.00),
(80, 30, 'Salt -TATA', 6, 29.50, 177.00),
(81, 30, 'biscuit', 9, 34.00, 306.00),
(82, 31, 'Salt -TATA', 1, 29.50, 29.50),
(83, 32, 'Salt -TATA', 1, 29.50, 29.50),
(84, 32, 'sugar', 1, 44.00, 44.00),
(85, 33, 'biscuit', 8, 34.00, 272.00),
(86, 33, 'Salt -TATA', 0, 29.50, 0.00);

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
(4, 'maggie', 'dxeeeeeeeeeeernvjvuy', 15.00, 18.00, 0.00, 6, 'default.png', '2025-12-22 11:21:47', '2025-12-22 11:21:47'),
(5, 'salt', 'edddddddddddddddddddddddd', 31.00, 18.00, 0.00, 5, 'default.png', '2025-12-22 11:22:08', '2025-12-22 11:22:08'),
(6, 'sugar', '', 44.00, 0.00, 0.00, 8, 'default.png', '2025-12-22 11:22:34', '2025-12-22 11:34:23'),
(8, 'Salt -TATA', 'Tata Salt – India’s trusted “Desh ka Namak” for over 40 years. Vacuum evaporated iodised salt, hygienically packed and untouched by hand. Delivers balanced taste in every meal and provides the right amount of iodine to support mental development*. Ideal for everyday cooking.', 29.50, 1.25, 0.00, 6, 'default.png', '2025-12-23 05:16:14', '2025-12-23 06:37:10'),
(9, 'biscuit', 'xbjhvdvcg fryryfhv vvfy v', 34.00, 4.00, 0.00, 15, 'default.png', '2025-12-23 07:37:35', '2025-12-23 07:37:35');

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
(1, 1, 2, '2025-12-26', '2026-01-26', 'active', 0, '2025-12-26 09:24:10'),
(2, 7, 1, '2025-12-26', '2026-01-26', 'active', 0, '2025-12-26 11:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `billing_cycle` enum('monthly','yearly') NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `plan_name`, `price`, `billing_cycle`, `description`, `created_at`) VALUES
(1, 'ultra', 1500.00, 'yearly', 'ultra plans', '2025-12-26 09:23:51'),
(2, 'Pro', 500.00, 'monthly', 'Pro plan with extra features', '2025-12-26 09:23:51'),
(5, 'Basic', 100.00, 'monthly', 'basic plan', '2025-12-26 12:59:23');

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
(1, 'Raghav', 'raghav@gmail.com', '$2y$12$16rdZcncHE0VEWf/gvBh/eOnUDEJcMJWgdlZBAxnAer237fLMTHPS', '2025-12-19 16:35:57', 'user', '9728389339', 'E-195, Mind2Web,Mohali,Punjab'),
(4, 'Admin', 'admin@gmail.com', '$2y$12$gEJQUCBcksnEamwI/JWKJu.ez.mUWrDGquUvZ.djTXnUVOpTlQRc6', '2025-12-19 17:43:27', 'admin', NULL, NULL),
(5, 'Hitesh', 'hitesh30@gmail.com', '$2y$12$RruG8QA/bxWrfEhG7LZpYuHmlIFvwJcB54LTM1mNrjHnhuDOy0zDK', '2025-12-25 16:34:13', 'admin', NULL, NULL),
(6, 'Rajesh', 'rajesh28@gmail.com', '$2y$12$wQsAa7APkpo/rMHy10e/AupKZfTsaCbTF1FxJhMrP35bMmPzAdRT6', '2025-12-25 16:35:53', 'admin', NULL, NULL),
(7, 'Geeta', 'geeta14@gmail.com', '$2y$12$Adhho6mxGe6tn4FVsDAllOys.Uozitft7.4n4TqpY0QBIjyD/WAuO', '2025-12-25 16:37:55', 'user', NULL, NULL),
(8, 'garima', 'g@gmail.co', '$2y$12$ObBfXZBgyzpIAqTxrdblr.fc.puJwCkup.3xAWU7yW0HC.nqgZCzS', '2025-12-26 17:45:28', 'user', NULL, NULL),
(9, 'Garima', 'garima21@gmail.com', '$2y$12$Ib0vR9vyVu5f4ysbMf6nIOFa8kjta/C3rPLTHuLpI2LADMtUsVdPG', '2025-12-26 17:48:11', 'user', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



 