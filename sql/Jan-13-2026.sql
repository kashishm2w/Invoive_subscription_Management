-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 13, 2026 at 06:39 AM
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
(44, 1, 1, 'INV-20251229-101733', '2025-12-29', '2026-01-05', 15.00, 'GST', 18.00, 2.70, 0.00, 17.70, 0.00, 0.00, 'Overdue', 'Generated from cart', '2025-12-29 10:17:33', '2026-01-08 04:34:51'),
(53, 1, 1, 'INV-20251230-115847', '2025-12-30', '2026-01-06', 574.00, 'GST', 1.41, 8.07, 0.00, 582.07, 0.00, 0.00, 'Overdue', 'Generated from cart', '2025-12-30 11:58:47', '2026-01-08 04:34:51'),
(54, 1, 1, 'INV-20251230-130519', '2025-12-30', '2026-01-06', 426.50, 'GST', 1.46, 6.23, 0.00, 432.73, 0.00, 0.00, 'Overdue', 'Generated from cart', '2025-12-30 13:05:19', '2026-01-08 04:34:51'),
(55, 1, 1, 'INV-20251230-131537', '2025-12-30', '2026-01-06', 14.00, 'GST', 2.00, 0.28, 0.00, 14.28, 0.00, 0.00, 'Overdue', 'Generated from cart', '2025-12-30 13:15:37', '2026-01-08 04:34:51'),
(57, 11, 11, 'INV-20251231-053511', '2025-12-31', '2026-01-07', 104.50, 'GST', 5.69, 5.95, 0.00, 110.45, 0.00, 0.00, 'Overdue', 'Generated from cart', '2025-12-31 05:35:11', '2026-01-08 04:34:51'),
(58, 1, 1, 'INV-20251231-055114', '2025-12-31', '2026-01-07', 31.00, 'GST', 18.00, 5.58, 0.00, 36.58, 0.00, 0.00, 'Overdue', 'Generated from cart', '2025-12-31 05:51:14', '2026-01-08 04:34:51'),
(59, 1, 1, 'INV-20251231-055147', '2025-12-31', '2025-12-31', 31.00, 'GST', 18.00, 5.58, 0.00, 36.58, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SkIJyFA4kAFMB5R0wETTrtx', '2025-12-31 05:51:47', '2025-12-31 05:51:47'),
(61, 1, 1, 'INV-20251231-063522', '2025-12-31', '2026-01-07', 31.00, 'GST', 18.00, 5.58, 0.00, 36.58, 0.00, 0.00, 'Overdue', 'Generated from cart', '2025-12-31 06:35:22', '2026-01-08 04:34:51'),
(62, 1, 1, 'INV-20251231-064901', '2025-12-31', '2025-12-31', 29.50, 'GST', 1.25, 0.37, 0.00, 29.87, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SkJDMFA4kAFMB5R0cEAJPMr', '2025-12-31 06:49:01', '2025-12-31 06:49:01'),
(63, 14, 14, 'INV-20251231-065152', '2025-12-31', '2025-12-31', 87.50, 'GST', 0.74, 0.65, 0.00, 88.15, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SkJG7FA4kAFMB5R1OgpeEA6', '2025-12-31 06:51:52', '2025-12-31 06:51:52'),
(65, 1, 1, 'INV-20251231-115917', '2025-12-31', '2026-01-07', 117.70, 'GST', 5.45, 6.42, 0.00, 124.12, 0.00, 0.00, 'Overdue', 'Generated from cart', '2025-12-31 11:59:17', '2026-01-08 04:34:51'),
(66, 1, 1, 'INV-20251231-131836', '2025-12-31', '2025-12-31', 89.70, 'GST', 6.53, 5.86, 0.00, 95.56, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SkPINFA4kAFMB5R0coFldVv', '2025-12-31 13:18:36', '2025-12-31 13:18:36'),
(67, 1, 1, 'INV-20260105-042050', '2026-01-05', '2026-01-05', 31.00, 'GST', 18.00, 5.58, 0.00, 36.58, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sm5HhFA4kAFMB5R1pelEsz4', '2026-01-05 04:20:50', '2026-01-05 04:20:50'),
(68, 1, 1, 'INV-20260105-054132', '2026-01-05', '2026-01-05', 46.00, 'GST', 3.00, 1.38, 0.00, 47.38, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sm6XnFA4kAFMB5R1IGB6c57', '2026-01-05 05:41:32', '2026-01-05 05:41:32'),
(72, 16, 16, 'INV-20260105-114441', '2026-01-05', '2026-01-12', 109.00, 'GST', 2.73, 2.98, 0.00, 111.98, 0.00, 0.00, 'Overdue', 'Generated from cart', '2026-01-05 11:44:41', '2026-01-13 04:00:55'),
(73, 16, 16, 'INV-20260105-120211', '2026-01-05', '2026-01-12', 1079.00, 'GST', 2.70, 29.18, 0.00, 1108.18, 0.00, 0.00, 'Overdue', 'Generated from cart', '2026-01-05 12:02:11', '2026-01-13 04:00:55'),
(75, 11, 11, 'INV-20260106-045549', '2026-01-06', '2026-01-06', 348.00, 'GST', 20.24, 70.42, 0.00, 418.42, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmSJ6FA4kAFMB5R0gTLupnS', '2026-01-06 04:55:49', '2026-01-06 04:55:49'),
(77, 17, 17, 'INV-20260106-090300', '2026-01-06', '2026-01-06', 51.00, 'GST', 7.49, 3.82, 0.00, 54.82, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmWAKFA4kAFMB5R1VvYew1n', '2026-01-06 09:03:00', '2026-01-06 09:03:00'),
(78, 11, 11, 'INV-20260106-092304', '2026-01-06', '2026-01-13', 933.00, 'GST', 2.95, 27.54, 0.00, 960.54, 0.00, 0.00, 'Paid', 'Generated from cart', '2026-01-06 09:23:04', '2026-01-08 13:03:39'),
(79, 1, 1, 'INV-20260106-111847', '2026-01-06', '2026-01-06', 174.00, 'GST', 16.68, 29.02, 0.00, 203.02, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmYHiFA4kAFMB5R1zx3iMCY', '2026-01-06 11:18:47', '2026-01-06 11:18:47'),
(80, 17, 17, 'INV-20260106-112817', '2026-01-06', '2026-01-06', 45.00, 'GST', 18.91, 8.51, 0.00, 53.51, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmYQuFA4kAFMB5R0K64QrlC', '2026-01-06 11:28:17', '2026-01-06 11:28:17'),
(81, 17, 17, 'INV-20260106-112939', '2026-01-06', '2026-01-06', 46.00, 'GST', 3.00, 1.38, 0.00, 47.38, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmYSEFA4kAFMB5R1kbUT9FX', '2026-01-06 11:29:39', '2026-01-06 11:29:39'),
(82, 17, 17, 'INV-20260106-113505', '2026-01-06', '2026-01-06', 80.00, 'GST', 3.00, 2.40, 0.00, 82.40, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmYXUFA4kAFMB5R1i5dPAxY', '2026-01-06 11:35:05', '2026-01-06 11:35:05'),
(90, 17, 17, 'INV-20260107-063213', '2026-01-07', '2026-01-07', 204.00, 'GST', 2.00, 4.08, 0.00, 208.08, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmqHwFA4kAFMB5R1Q2sGdDm', '2026-01-07 06:32:14', '2026-01-07 06:32:14'),
(91, 17, 17, 'INV-20260107-063803', '2026-01-07', '2026-01-07', 76.00, 'GST', 5.32, 4.04, 0.00, 80.04, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmqNaFA4kAFMB5R1439o0QO', '2026-01-07 06:38:03', '2026-01-07 06:38:03'),
(94, 1, 1, 'INV-20260107-111413', '2026-01-07', '2026-01-07', 76.00, 'GST', 5.76, 4.38, 0.00, 80.38, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmugqFA4kAFMB5R1hmk14RW', '2026-01-07 11:14:13', '2026-01-07 11:14:13'),
(95, 1, 1, 'INV-20260107-114413', '2026-01-07', '2026-01-07', 66.00, 'GST', 2.52, 1.66, 0.00, 67.66, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Smv9sFA4kAFMB5R15rETm4m', '2026-01-07 11:44:13', '2026-01-07 11:44:13'),
(96, 1, 1, 'INV-20260107-123655', '2026-01-07', '2026-01-07', 76.00, 'GST', 5.32, 4.04, 0.00, 80.04, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SmvysFA4kAFMB5R1J3NpBaA', '2026-01-07 12:36:55', '2026-01-07 12:36:55'),
(98, 1, 1, 'INV-20260108-043808', '2026-01-08', '2026-01-15', 76.00, 'GST', 5.32, 4.04, 0.00, 80.04, 0.00, 0.00, 'Paid', 'Generated from cart', '2026-01-08 04:38:08', '2026-01-08 09:30:24'),
(99, 1, 1, 'INV-20260108-050144', '2026-01-08', '2026-01-15', 44.00, 'GST', 7.73, 3.40, 0.00, 47.40, 0.00, 0.00, 'Paid', 'Generated from cart', '2026-01-08 05:01:44', '2026-01-08 09:30:42'),
(100, 1, 1, 'INV-20260108-052442', '2026-01-08', '2026-01-15', 100.00, 'GST', 0.00, 0.00, 0.00, 100.00, 0.00, 0.00, 'Unpaid', 'Generated from cart', '2026-01-08 05:24:42', '2026-01-08 05:24:42'),
(101, 1, 1, 'INV-20260108-052505', '2026-01-08', '2026-01-15', 100.00, 'GST', 0.00, 0.00, 0.00, 100.00, 0.00, 0.00, 'Unpaid', 'Generated from cart', '2026-01-08 05:25:05', '2026-01-08 05:25:05'),
(102, 1, 1, 'INV-20260108-052529', '2026-01-08', '2026-01-15', 100.00, 'GST', 0.00, 0.00, 0.00, 100.00, 0.00, 0.00, 'Unpaid', 'Generated from cart', '2026-01-08 05:25:29', '2026-01-08 05:25:29'),
(103, 1, 1, 'INV-20260108-052544', '2026-01-08', '2026-01-15', 100.00, 'GST', 0.00, 0.00, 0.00, 100.00, 0.00, 0.00, 'Paid', 'Generated from cart', '2026-01-08 05:25:44', '2026-01-09 05:58:07'),
(104, 1, 1, 'INV-20260108-053456', '2026-01-08', '2026-01-08', 210.00, 'GST', 3.14, 6.60, 0.00, 216.60, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SnBs3FA4kAFMB5R2ENA2e8Z', '2026-01-08 05:34:56', '2026-01-08 05:34:56'),
(107, 11, 11, 'INV-20260108-130305', '2026-01-08', '2026-01-08', 378.10, 'GST', 1.35, 5.12, 0.00, 383.22, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SnIrkFA4kAFMB5R0IwsSfxm', '2026-01-08 13:03:05', '2026-01-08 13:03:05'),
(110, 18, 18, 'INV-20260109-042337', '2026-01-09', '2026-01-16', 180.00, 'GST', 2.00, 3.60, 0.00, 183.60, 0.00, 0.00, 'Paid', 'Generated from cart', '2026-01-09 04:23:37', '2026-01-09 05:29:01'),
(111, 1, 1, 'SUB-20260109-044645', '2026-01-09', '2026-01-09', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 0.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SnXayFA4kAFMB5R1DRGj55x', '2026-01-09 04:46:45', '2026-01-09 04:46:45'),
(112, 1, 1, 'SUB-20260109-050208', '2026-01-09', '2026-01-09', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 0.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SnXprFA4kAFMB5R1BhFUhZQ', '2026-01-09 05:02:08', '2026-01-09 05:02:08'),
(113, 18, 18, 'SUB-20260109-050701', '2026-01-09', '2026-01-09', 294.00, 'NONE', 0.00, 0.00, 0.00, 294.00, 0.00, 0.00, 'Paid', 'Subscription Payment - Pro | Transaction ID: ch_3SnXuZFA4kAFMB5R1U6E0v1u', '2026-01-09 05:07:01', '2026-01-09 05:07:01'),
(114, 18, 18, 'SUB-20260109-053210', '2026-01-09', '2026-01-09', 294.00, 'NONE', 0.00, 0.00, 0.00, 294.00, 0.00, 0.00, 'Paid', 'Subscription Payment - Pro | Transaction ID: ch_3SnYIvFA4kAFMB5R2OdcEfxp', '2026-01-09 05:32:10', '2026-01-09 05:32:10'),
(115, 1, 1, 'SUB-20260109-060921', '2026-01-09', '2026-01-09', 100.00, 'NONE', 0.00, 0.00, 0.00, 100.00, 0.00, 0.00, 'Paid', 'Subscription Payment - Basic | Transaction ID: ch_3SnYsuFA4kAFMB5R2Q2YpHao', '2026-01-09 06:09:21', '2026-01-09 06:09:21'),
(116, 18, 18, 'INV-20260109-065934', '2026-01-09', '2026-01-09', 671.00, 'GST', 4.19, 27.00, 33.55, 637.45, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SnZfVFA4kAFMB5R2iUP2OoR | Subscription Discount: 5%', '2026-01-09 06:59:34', '2026-01-09 06:59:34'),
(117, 14, 14, 'SUB-20260109-071631', '2026-01-09', '2026-01-09', 294.00, 'NONE', 0.00, 0.00, 0.00, 294.00, 0.00, 0.00, 'Paid', 'Subscription Payment - Pro | Transaction ID: ch_3SnZvuFA4kAFMB5R2C7jKUj7', '2026-01-09 07:16:31', '2026-01-09 07:16:31'),
(118, 14, 14, 'INV-20260109-071735', '2026-01-09', '2026-01-16', 167.00, 'GST', 0.00, 0.00, 0.00, 167.00, 0.00, 0.00, 'Unpaid', 'Generated from cart', '2026-01-09 07:17:35', '2026-01-09 07:17:35'),
(119, 14, 14, 'SUB-20260113-044440', '2026-01-13', '2026-01-13', 294.00, 'NONE', 0.00, 0.00, 0.00, 294.00, 0.00, 0.00, 'Paid', 'Subscription Payment - Pro | Transaction ID: ch_3SozT9FA4kAFMB5R2IAne4aw', '2026-01-13 04:44:40', '2026-01-13 04:44:40'),
(120, 14, 14, 'INV-20260113-051055', '2026-01-13', '2026-01-20', 55.00, 'GST', 0.00, 0.00, 0.00, 55.00, 0.00, 0.00, 'Unpaid', 'Generated from cart', '2026-01-13 05:10:55', '2026-01-13 05:10:55'),
(121, 14, 14, 'INV-20260113-051116', '2026-01-13', '2026-01-20', 75.00, 'GST', 0.53, 0.40, 0.00, 75.40, 0.00, 0.00, 'Unpaid', 'Generated from cart', '2026-01-13 05:11:16', '2026-01-13 05:11:16'),
(122, 14, 14, 'INV-20260113-051345', '2026-01-13', '2026-01-13', 67.00, 'GST', 0.00, 0.00, 3.35, 63.65, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3SozvIFA4kAFMB5R1S7mlIuG | Subscription Discount: 5%', '2026-01-13 05:13:45', '2026-01-13 05:13:45'),
(123, 14, 14, 'INV-20260113-051626', '2026-01-13', '2026-01-20', 67.00, 'GST', 0.00, 0.00, 0.00, 67.00, 0.00, 0.00, 'Unpaid', 'Generated from cart', '2026-01-13 05:16:26', '2026-01-13 05:16:26'),
(124, 14, 14, 'INV-20260113-055037', '2026-01-13', '2026-01-20', 486.00, 'GST', 0.00, 0.00, 0.00, 486.00, 0.00, 0.00, 'Unpaid', 'Generated from cart', '2026-01-13 05:50:37', '2026-01-13 05:50:37'),
(125, 1, 1, 'INV-20260113-063105', '2026-01-13', '2026-01-13', 173.04, 'GST', 3.00, 5.04, 0.00, 173.04, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sp188FA4kAFMB5R2rf7ddHB', '2026-01-13 06:31:05', '2026-01-13 06:31:05'),
(126, 1, 1, 'INV-20260113-063434', '2026-01-13', '2026-01-13', 173.04, 'GST', 3.00, 5.04, 0.00, 173.04, 0.00, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sp1BVFA4kAFMB5R1Z0mAfym', '2026-01-13 06:34:34', '2026-01-13 06:34:34'),
(127, 1, 1, 'INV-20260113-063623', '2026-01-13', '2026-01-13', 173.04, 'GST', 3.00, 5.04, 0.00, 173.04, 173.04, 0.00, 'Paid', 'Paid via Stripe | Transaction ID: ch_3Sp1DHFA4kAFMB5R1Gciap9D', '2026-01-13 06:36:23', '2026-01-13 06:36:23');

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
(116, 53, 'Maggie', 1, 14.00, 14.00),
(117, 53, 'sugar', 8, 44.00, 352.00),
(118, 53, 'Salt -TATA', 6, 29.50, 177.00),
(119, 53, 'salt', 1, 31.00, 31.00),
(120, 54, 'Salt -TATA', 1, 29.50, 29.50),
(121, 54, 'sugar', 8, 44.00, 352.00),
(122, 54, 'salt', 1, 31.00, 31.00),
(123, 54, 'Maggie', 1, 14.00, 14.00),
(124, 55, 'Maggie', 1, 14.00, 14.00),
(126, 57, 'salt', 1, 31.00, 31.00),
(127, 57, 'sugar', 1, 44.00, 44.00),
(128, 57, 'Salt -TATA', 1, 29.50, 29.50),
(129, 58, 'salt', 1, 31.00, 31.00),
(130, 59, 'salt', 1, 31.00, 31.00),
(132, 61, 'salt', 1, 31.00, 31.00),
(133, 62, 'Salt -TATA', 1, 29.50, 29.50),
(134, 63, 'Maggie', 1, 14.00, 14.00),
(135, 63, 'Salt -TATA', 1, 29.50, 29.50),
(136, 63, 'sugar', 1, 44.00, 44.00),
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
(160, 75, 'wsijcbibedc', 25, 12.00, 300.00),
(161, 75, 'Raghav', 1, 2.00, 2.00),
(162, 75, 'chowmin', 1, 46.00, 46.00),
(164, 77, 'wsssss', 1, 1.00, 1.00),
(165, 77, 'kdhk', 1, 2.00, 2.00),
(166, 77, 'wsijcbibedc', 1, 12.00, 12.00),
(167, 77, 'wwwwww', 1, 2.00, 2.00),
(168, 77, 'Namkeen', 1, 34.00, 34.00),
(169, 78, 'Namkeen', 27, 34.00, 918.00),
(170, 78, 'wsssss', 15, 1.00, 15.00),
(171, 79, 'wsijcbibedc', 10, 12.00, 120.00),
(172, 79, 'Namkeen', 1, 34.00, 34.00),
(173, 79, 'soup', 1, 20.00, 20.00),
(174, 80, 'kdhk', 1, 22.00, 22.00),
(175, 80, 'whekkkkkkkkkkkkkkdgli', 1, 23.00, 23.00),
(176, 81, 'chowmin', 1, 46.00, 46.00),
(177, 82, 'Namkeen', 1, 34.00, 34.00),
(178, 82, 'chowmin', 1, 46.00, 46.00),
(186, 90, 'wwwwww', 17, 12.00, 204.00),
(187, 91, 'wwwwww', 1, 12.00, 12.00),
(188, 91, 'wsijcbibedc', 1, 12.00, 12.00),
(189, 91, 'Raghav', 1, 32.00, 32.00),
(190, 91, 'soup', 1, 20.00, 20.00),
(193, 94, 'sjjsgwgj', 1, 30.00, 30.00),
(194, 94, 'chowmin', 1, 46.00, 46.00),
(195, 95, 'Namkeen', 1, 34.00, 34.00),
(196, 95, 'soup', 1, 20.00, 20.00),
(197, 95, 'wwwwww', 1, 12.00, 12.00),
(198, 96, 'soup', 2, 20.00, 40.00),
(199, 96, 'wwwwww', 2, 12.00, 24.00),
(200, 96, 'wsijcbibedc', 1, 12.00, 12.00),
(202, 98, 'wsijcbibedc', 1, 12.00, 12.00),
(203, 98, 'soup', 1, 20.00, 20.00),
(204, 98, 'wwwwww', 1, 12.00, 12.00),
(205, 98, 'Raghav', 1, 32.00, 32.00),
(206, 99, 'wwwwww', 1, 12.00, 12.00),
(207, 99, 'soup', 1, 20.00, 20.00),
(208, 99, 'wsijcbibedc', 1, 12.00, 12.00),
(209, 103, 'kdhk', 1, 22.00, 22.00),
(210, 103, 'wsssss', 1, 78.00, 78.00),
(211, 104, 'soup', 9, 20.00, 180.00),
(212, 104, 'sjjsgwgj', 1, 30.00, 30.00),
(215, 107, 'saviya', 1, 0.10, 0.10),
(216, 107, 'eiuguydf', 1, 55.00, 55.00),
(217, 107, 'tyjtyj', 1, 67.00, 67.00),
(218, 107, 'Raghav', 8, 32.00, 256.00),
(221, 110, 'soup', 3, 20.00, 60.00),
(222, 110, 'wwwwww', 2, 12.00, 24.00),
(223, 110, 'Raghav', 3, 32.00, 96.00),
(224, 111, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(225, 112, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(226, 113, 'Subscription: Pro (Monthly)', 1, 294.00, 294.00),
(227, 114, 'Subscription: Pro (Monthly)', 1, 294.00, 294.00),
(228, 115, 'Subscription: Basic (Monthly)', 1, 100.00, 100.00),
(229, 116, 'sjjsgwgj', 9, 30.00, 270.00),
(230, 116, 'kdhk', 17, 22.00, 374.00),
(231, 117, 'Subscription: Pro (Monthly)', 1, 294.00, 294.00),
(232, 118, 'tyjtyj', 1, 67.00, 67.00),
(233, 118, 'wsssss', 1, 78.00, 78.00),
(234, 118, 'kdhk', 1, 22.00, 22.00),
(235, 119, 'Subscription: Pro (Monthly)', 1, 294.00, 294.00),
(236, 120, 'eiuguydf', 1, 55.00, 55.00),
(237, 121, 'eiuguydf', 1, 55.00, 55.00),
(238, 121, 'soup', 1, 20.00, 20.00),
(239, 122, 'tyjtyj', 1, 67.00, 67.00),
(240, 123, 'tyjtyj', 1, 67.00, 67.00),
(241, 124, 'saviya', 20, 0.10, 2.00),
(242, 124, 'kdhk', 22, 22.00, 484.00),
(243, 125, 'saviya', 14, 12.00, 168.00),
(244, 126, 'saviya', 14, 12.00, 168.00),
(245, 127, 'saviya', 14, 12.00, 168.00);

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
(1, 127, 1, 173.04, 'stripe', 'ch_3Sp1DHFA4kAFMB5R1Gciap9D', 'completed', 'Full payment via Stripe', '2026-01-13 06:36:23');

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
(98, 1, 5, '2026-01-07', '2026-02-08', 'expired', 1, '2026-01-07 04:08:15'),
(99, 1, 5, '2026-01-07', '2026-01-08', 'expired', 1, '2026-01-07 04:08:15'),
(100, 18, 8, '2026-01-09', '2026-01-10', 'cancelled', 1, '2026-01-09 05:07:01'),
(101, 18, 8, '2026-01-09', '2026-01-10', 'expired', 1, '2026-01-09 05:32:10'),
(102, 1, 5, '2026-01-09', '2026-01-10', 'expired', 1, '2026-01-09 06:09:21'),
(103, 14, 8, '2026-01-09', '2026-01-10', 'expired', 1, '2026-01-09 07:16:31'),
(104, 14, 8, '2026-01-13', '2026-01-14', 'active', 1, '2026-01-13 04:44:40');

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
(8, 'Pro', 294.00, 5, 'monthly', 'extra feature with basic plan', '2025-12-30 05:04:06'),
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
(16, 'Gaurav', 'gaurav21@gmail.com', '$2y$12$4uBk/sdGmkDheGG/lNhMy.s6rS/RuxILkfSTdQQomO8rzhYAlq5WO', '2026-01-05 17:14:21', 'user'),
(17, 'Amit', 'amitbatra121@yopmail.com', '$2y$12$4/aDubgqY0.EsfMIh0n2aO4N6T00R5s0QVsYTS8wfI1ZwgOuuFUwy', '2026-01-06 11:25:37', 'user'),
(18, 'Chnadan', 'chandan21@yopmail.com', '$2y$12$1j0yqbuu5Uzwqo1naiA3tup.wFU69DchBGn/yTzXsGpoPlSyj9PFu', '2026-01-07 17:36:03', 'user');

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
(18, 14, 'Raghav', '8787867676', 'mind 2 web', 'moali', 'punjab', '235178', 0, '2026-01-13 05:19:45');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
