-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2025 at 01:30 PM
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
(102, 42, 'sugar', 5, 44.00, 220.00),
(103, 43, 'maggie', 2, 15.00, 30.00),
(104, 43, 'salt', 3, 31.00, 93.00),
(105, 44, 'maggie', 1, 15.00, 15.00),
(106, 45, 'biscuit', 9, 34.00, 306.00),
(107, 46, 'biscuit', 4, 34.00, 136.00),
(108, 46, 'Salt -TATA', 1, 29.50, 29.50),
(109, 47, 'Salt -TATA', 1, 29.50, 29.50),
(110, 47, 'sugar', 1, 44.00, 44.00),
(111, 48, 'Maggie', 4, 14.00, 56.00),
(112, 49, 'Salt -TATA', 6, 29.50, 177.00),
(113, 50, 'sugar', 8, 44.00, 352.00),
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
(144, 66, 'Maggie', 1, 14.00, 14.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
