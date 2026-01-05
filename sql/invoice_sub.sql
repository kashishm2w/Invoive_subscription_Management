-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 05, 2026 at 04:48 AM
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
(14, 'Liza Gupta', 'liza21@yopmail.com', '$2y$12$IetET2i4Lcmo7Y8LjZG9fOdZpBavR/uEgZoKm6DreK8T.aGj5O0iC', '2025-12-31 12:20:59', 'user', NULL, NULL);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
