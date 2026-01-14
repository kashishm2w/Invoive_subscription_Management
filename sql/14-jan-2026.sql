-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 14, 2026 at 06:01 AM
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
(139, 1, 1, 'INV-20260114-044314', '2026-01-14', '2026-01-21', 12.10, 'GST', 2.98, 0.36, 0.00, 12.46, 0.00, 12.46, 'Unpaid', 'Generated from cart', '2026-01-14 04:43:14', '2026-01-14 04:44:23'),
(140, 1, 1, 'INV-20260114-044329', '2026-01-14', '2026-01-21', 55.10, 'GST', 0.00, 0.00, 0.00, 55.10, 0.00, 55.10, 'Unpaid', 'Generated from cart', '2026-01-14 04:43:29', '2026-01-14 04:44:23'),
(141, 1, 1, 'INV-20260114-044345', '2026-01-14', '2026-01-21', 32.00, 'GST', 2.00, 0.64, 0.00, 32.64, 0.00, 32.64, 'Unpaid', 'Generated from cart', '2026-01-14 04:43:45', '2026-01-14 04:44:23'),
(142, 1, 1, 'INV-20260114-044402', '2026-01-14', '2026-01-21', 32.00, 'GST', 2.00, 0.64, 0.00, 32.64, 0.00, 32.64, 'Unpaid', 'Generated from cart', '2026-01-14 04:44:02', '2026-01-14 04:44:23'),
(143, 1, 1, 'INV-20260114-044415', '2026-01-14', '2026-01-21', 32.00, 'GST', 2.00, 0.64, 0.00, 32.64, 22.64, 10.00, 'Partial', 'Generated from cart', '2026-01-14 04:44:15', '2026-01-14 04:57:07'),
(144, 1, 1, 'SUB-20260114-050813', '2026-01-14', '2026-01-14', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 100.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SpMJUFA4kAFMB5R1hqMiTCC', '2026-01-14 05:08:13', '2026-01-14 05:08:45'),
(145, 14, 14, 'SUB-20260114-051113', '2026-01-14', '2026-01-14', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SpMMOFA4kAFMB5R2RIMYGa2', '2026-01-14 05:11:13', '2026-01-14 05:11:13'),
(146, 19, 19, 'SUB-20260114-051508', '2026-01-14', '2026-01-14', 555.00, 'NONE', 0.00, 0.00, 0.00, 555.00, 555.00, 0.00, 'Paid', 'Subscription Payment - Pro1 | Transaction ID: ch_3SpMQBFA4kAFMB5R1gUtIpRH', '2026-01-14 05:15:08', '2026-01-14 05:15:08');

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
(293, 146, 'Subscription: Pro1 (Monthly)', 1, 555.00, 555.00);

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
(26, 146, 19, 555.00, 'stripe', 'ch_3SpMQBFA4kAFMB5R1gUtIpRH', 'completed', 'Subscription Payment - Pro1', '2026-01-14 05:15:12');

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
(18, 'soup', 'duhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgvduhevdhgvevdgv', 20.00, 2.00, 0.00, 14, '1767616067_slat.jpg', '2026-01-05 12:27:47', '2026-01-13 04:36:27'),
(19, 'wwwwww', 'bjdgehfdukew', 12.00, 2.00, 0.00, 17, '1767616089_chowmin.png', '2026-01-05 12:28:09', '2026-01-07 09:54:07'),
(21, 'Raghav', 'snsxwbxfweygxhwgu', 32.00, 2.00, 0.00, 17, '1767616521_saviya.jpg', '2026-01-05 12:35:21', '2026-01-06 11:17:07'),
(23, 'sjjsgwgj', 'wghgxjffhx', 30.00, 10.00, 0.00, 20, '1767674894_besan.jpg', '2026-01-06 04:48:14', '2026-01-06 11:17:21'),
(24, 'kdhk', 'wzxsxdedcfwerf', 22.00, 0.00, 0.00, 22, '1767675059_maggie.jpg', '2026-01-06 04:50:59', '2026-01-06 11:17:29'),
(25, 'wsssss', 'ejskdhhgtc', 78.00, 0.00, 0.00, 21, '1767675091_chowmin.png', '2026-01-06 04:51:31', '2026-01-07 09:54:20'),
(27, 'tyjtyj', 'yfujjyuuuuuu', 67.00, 0.00, 0.00, 1, '1767701220_macroni.jpeg', '2026-01-06 12:07:00', '2026-01-06 12:07:00'),
(28, 'eiuguydf', 'yueuqefdoegduqg', 55.00, 0.00, 0.00, 1, '1767785287_sugar.jpg', '2026-01-07 11:28:07', '2026-01-08 06:06:50'),
(29, 'saviya', 'fgggtrffdf', 0.10, 0.00, 0.00, 20, '1767787988_maggie.jpg', '2026-01-07 12:13:08', '2026-01-07 12:13:08'),
(30, 'saviya', 'bwehfqdljwegj', 12.00, 3.00, 0.00, 19, '1767877526_macroni.jpeg', '2026-01-08 13:05:26', '2026-01-08 13:05:26');

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
(106, 1, 5, '2026-01-14', '2026-02-14', 'active', 1, '2026-01-14 05:08:13'),
(107, 14, 10, '2026-01-14', '2026-02-14', 'active', 1, '2026-01-14 05:11:13'),
(108, 19, 10, '2026-01-14', '2026-02-14', 'active', 1, '2026-01-14 05:15:08');

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
(8, 'Pro1', 3657.00, 12, 'monthly', 'jhhhhhhhhhhhhh', '2025-12-30 05:04:06'),
(10, 'Pro1', 555.00, 8, 'monthly', 'test', '2026-01-05 11:18:34');

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
(19, 'Yash', 'yash21@yopmail.com', '$2y$12$1wxY8I3C3ZeESFTDnyPrfeytcs4SgoHBqbGBuzkKwlm4Zo1wP3BwC', '2026-01-14 10:44:22', 'user');

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
(8, 1, 'vishal', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-06 09:59:54'),
(9, 18, 'chandan', '9373657363', 'jindal chowk', 'hisar', 'Haryana', '125022', 1, '2026-01-09 04:23:33'),
(12, 14, 'liza', '7675786968', '3567, sector 3', 'panipat', 'haryana', '229272', 1, '2026-01-13 05:10:11'),
(13, 14, 'Raghav', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:16'),
(14, 14, 'vishal', '7389965388', '3567, sector 3', 'panipat', 'haryana', '229272', 0, '2026-01-13 05:19:20'),
(15, 14, 'amit', '8787867676', '2353,sector71', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:24'),
(16, 14, 'Amit', '7393872897', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:28'),
(17, 14, 'Raghav', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:41'),
(18, 14, 'Raghav', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:45'),
(19, 16, 'Gaurav', '9723534232', 'mulatn colony', 'hansi', 'haryana', '125033', 1, '2026-01-13 10:04:33');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
