-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2021 at 12:14 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mms`
--

-- --------------------------------------------------------

--
-- Table structure for table `expanses`
--

CREATE TABLE `expanses` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(25) COLLATE utf8mb4_bin NOT NULL,
  `member_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `total_amount` double(50,2) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `status` tinyint(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `expanses`
--

INSERT INTO `expanses` (`id`, `invoice_no`, `member_id`, `category_id`, `total_amount`, `date`, `month`, `status`, `created_at`, `updated_at`) VALUES
(14, '276170', 35, 6, 760.00, '2021-05-12', 'Jun', 1, '2021-07-03 05:19:52', '2021-07-06'),
(15, '404691', 41, 5, 325.00, '2021-05-15', 'May', 1, '2021-07-04 08:35:01', '2021-07-04'),
(16, '155994', 41, 5, 485.00, '2021-05-22', 'May', 0, '2021-07-04 11:30:52', '2021-07-07'),
(17, '272695', 41, 5, 355.00, '2021-05-14', 'May', 0, '2021-07-07 08:34:06', '2021-07-07'),
(18, '919537', 35, 5, 355.00, '2021-07-09', 'Jul', 1, '2021-07-08 14:25:50', '2021-07-08'),
(19, '563123', 41, 6, 550.00, '2021-07-09', 'Jul', 1, '2021-07-08 14:26:25', '2021-07-08');

-- --------------------------------------------------------

--
-- Table structure for table `expanse_details`
--

CREATE TABLE `expanse_details` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(25) COLLATE utf8mb4_bin NOT NULL,
  `member_id` tinyint(5) NOT NULL,
  `item_name_id` tinyint(5) NOT NULL,
  `weight` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `amount` double(10,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `expanse_details`
--

INSERT INTO `expanse_details` (`id`, `invoice_no`, `member_id`, `item_name_id`, `weight`, `amount`, `date`, `created_at`, `updated_at`) VALUES
(24, '276170', 35, 5, '3kg', 600.00, '2021-07-03', '2021-07-03 05:19:52', '2021-07-03'),
(25, '276170', 35, 6, '1kg', 160.00, '2021-06-04', '2021-07-03 05:19:52', '2021-07-03'),
(26, '404691', 41, 7, '5kg', 325.00, '2021-05-15', '2021-07-04 08:35:00', '2021-07-04'),
(27, '155994', 41, 7, '5kg', 325.00, '2021-05-22', '2021-07-04 11:30:51', '2021-07-07'),
(28, '155994', 41, 9, '2kg', 160.00, '2021-05-22', '2021-07-04 11:30:52', '2021-07-04'),
(29, '272695', 41, 7, '3kg', 195.00, '2021-05-14', '2021-07-07 08:34:06', '2021-07-07'),
(30, '272695', 41, 9, '2kg', 160.00, '2021-05-14', '2021-07-07 08:34:06', '2021-07-07'),
(31, '919537', 35, 7, '3kg', 195.00, '2021-07-09', '2021-07-08 14:25:49', '2021-07-08'),
(32, '919537', 35, 9, '2kg', 160.00, '2021-07-09', '2021-07-08 14:25:49', '2021-07-08'),
(33, '563123', 41, 5, '1kg', 200.00, '2021-07-09', '2021-07-08 14:26:25', '2021-07-08'),
(34, '563123', 41, 6, '2kg', 350.00, '2021-07-09', '2021-07-08 14:26:25', '2021-07-08');

-- --------------------------------------------------------

--
-- Table structure for table `food_categories`
--

CREATE TABLE `food_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `photo` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `food_categories`
--

INSERT INTO `food_categories` (`id`, `category_name`, `photo`, `created_at`, `updated_at`) VALUES
(5, 'Chaldhal', '192703.jpg', '2021-04-22 04:30:37', '2021-07-03'),
(6, 'Fish', '121670.jpg', '2021-04-22 04:35:04', '2021-07-03');

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `food_category_id` int(5) NOT NULL,
  `item_name` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `item_description` varchar(2000) COLLATE utf8mb4_bin NOT NULL,
  `item_photo` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `food_category_id`, `item_name`, `item_description`, `item_photo`, `created_at`, `updated_at`) VALUES
(5, 6, 'Rui', '1kg rui fish 220tk.', '125468.jpg', '2021-04-22 04:41:49', '2021-04-22'),
(6, 6, 'Pangash', '1kg pangash fish 150tk.', '164729.png', '2021-04-22 04:42:23', '2021-04-22'),
(7, 5, 'miniket chal', '1kg miniket chal 65tk', '149666.png', '2021-04-22 04:43:00', '2021-07-03'),
(9, 5, 'dhals', '1kg dhal 80tk', '68445.jpg', '2021-07-04 11:08:35', '2021-07-04');

-- --------------------------------------------------------

--
-- Table structure for table `meals`
--

CREATE TABLE `meals` (
  `id` int(11) NOT NULL,
  `members_id` int(5) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `breakfast` tinyint(2) NOT NULL,
  `lunch` tinyint(2) NOT NULL,
  `dinner` tinyint(2) NOT NULL,
  `status` tinyint(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `meals`
--

INSERT INTO `meals` (`id`, `members_id`, `date`, `month`, `breakfast`, `lunch`, `dinner`, `status`, `created_at`, `updated_at`) VALUES
(4, 35, '2021-05-10', 'May', 1, 1, 1, 0, '2021-05-09 13:48:33', '2021-07-07'),
(5, 41, '2021-05-09', 'May', 1, 1, 2, 1, '2021-05-09 13:48:51', '2021-05-09'),
(11, 35, '2021-05-13', 'May', 1, 2, 1, 1, '2021-05-13 05:46:14', '2021-07-06'),
(12, 41, '2021-05-14', 'May', 1, 1, 1, 1, '2021-05-13 06:14:23', '2021-05-13'),
(13, 41, '2021-05-15', 'May', 1, 1, 1, 1, '2021-05-13 06:14:39', '2021-05-13'),
(17, 41, '2021-05-16', 'May', 1, 1, 1, 1, '2021-05-13 06:19:23', '2021-05-13'),
(24, 35, '2021-05-27', 'May', 1, 1, 1, 1, '2021-07-04 11:13:42', '2021-07-06'),
(26, 35, '2021-05-20', 'May', 2, 1, 1, 1, '2021-07-04 15:31:35', '2021-07-06'),
(27, 41, '2021-05-17', 'May', 2, 2, 2, 1, '2021-07-04 15:31:50', '2021-07-04'),
(28, 35, '2021-07-01', 'Jul', 1, 1, 1, 1, '2021-07-06 15:57:33', '2021-07-06'),
(29, 35, '2021-07-01', 'Jul', 1, 1, 1, 1, '2021-07-08 14:22:10', '2021-07-08'),
(30, 41, '2021-07-01', 'Jul', 1, 1, 1, 1, '2021-07-08 14:22:24', '2021-07-08'),
(31, 35, '2021-07-02', 'Jul', 1, 2, 1, 1, '2021-07-08 14:22:38', '2021-07-08'),
(32, 41, '2021-07-02', 'Jul', 2, 1, 1, 1, '2021-07-08 14:22:52', '2021-07-08'),
(33, 41, '2021-07-10', 'Jul', 2, 1, 1, 1, '2021-07-08 17:19:54', '2021-07-08');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `full_name` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `phone_no` int(11) NOT NULL,
  `address` varchar(300) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `photo` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `nid_photo` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `role_id` tinyint(5) NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `status` tinyint(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `full_name`, `phone_no`, `address`, `email`, `password`, `photo`, `nid_photo`, `role_id`, `role_name`, `status`, `created_at`, `updated_at`) VALUES
(35, 'Nur Mohammad', 1768476053, 'Dhaka,Bangladesh', 'nur.mohd1996@gmail.com', '$2y$10$SlEtgK0GP9Vb9GsCKURtsO2OAxtFh0MsszS.bYjG8C.sE1T52u/Oy', '46021.JPG', '71011.jpg', 0, 'User', 1, '2021-03-03 12:34:36', '2021-07-08'),
(41, 'Ripon Ahmed', 1985964113, 'dhaka,bangladesh', 'riponahmed@gmail.com', '$2y$10$km4BRYsDIRnNmEEqixApBu4SGuN4PwxEJikrwGeBDnBzB/QmuWt0m', '32083.jpg', '32083.jpg', 0, 'User', 1, '2021-05-05 08:39:53', '2021-07-07'),
(45, 'Hasib', 1768476053, 'dhaka,Bangladesh', 'hasib@gmail.com', '$2y$10$km4BRYsDIRnNmEEqixApBu4SGuN4PwxEJikrwGeBDnBzB/QmuWt0m', '84820.jpg', '84820.jpg', 0, 'User', 1, '2021-07-05 14:23:06', '2021-07-05'),
(47, 'Nur', 1609072754, 'mirpur,dhaka,bangladesh', 'admin@gmail.com', '$2y$10$ePZJFlwTcQE0O3XtG1lSpebmwMD/im9eo5pQmN1S/Lx0cELBfcB9.', '52563.JPG', '116601.jpg', 1, 'Admin', 1, '2021-07-06 13:53:22', '2021-07-09'),
(49, 'Meheraz', 1679095634, 'Mirpu,Dhaka,Bangladesh', 'meheraz@gmail.com', '$2y$10$R6tIrNODGeRqpkqJOIyIC.CFVHfG1ULfglnoeATkBu1ZEBQrv0dBW', '43215.jpg', '43215.jpg', 0, 'User', 1, '2021-07-06 10:07:38', '2021-07-06');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `payment_amount` double(20,2) NOT NULL,
  `date` date NOT NULL,
  `month` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `status` tinyint(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `member_id`, `payment_amount`, `date`, `month`, `status`, `created_at`, `updated_at`) VALUES
(2, 35, 1000.00, '2021-05-12', 'May', 0, '2021-05-04 17:40:14', '2021-07-06'),
(6, 41, 500.00, '2021-05-19', 'May', 1, '2021-07-03 07:17:38', '2021-07-04'),
(10, 35, 1000.00, '2021-05-15', 'May', 1, '2021-07-04 13:35:47', '2021-07-04'),
(11, 41, 500.00, '2021-05-22', 'May', 1, '2021-07-04 15:29:54', '2021-07-04'),
(12, 35, 1200.00, '2021-05-15', 'May', 0, '2021-07-07 06:05:16', '2021-07-07'),
(13, 35, 1500.00, '2021-07-09', 'Jul', 1, '2021-07-08 14:17:48', '2021-07-08'),
(14, 35, 1000.00, '2021-07-08', 'Jul', 0, '2021-07-08 14:19:59', '2021-07-08'),
(15, 41, 1000.00, '2021-07-09', 'Jul', 1, '2021-07-08 14:31:38', '2021-07-08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expanses`
--
ALTER TABLE `expanses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expanse_details`
--
ALTER TABLE `expanse_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_categories`
--
ALTER TABLE `food_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meals`
--
ALTER TABLE `meals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expanses`
--
ALTER TABLE `expanses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `expanse_details`
--
ALTER TABLE `expanse_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `food_categories`
--
ALTER TABLE `food_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `meals`
--
ALTER TABLE `meals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
