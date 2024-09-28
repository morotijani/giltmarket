-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 28, 2024 at 10:42 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jspence`
--

-- --------------------------------------------------------

--
-- Table structure for table `jspence`
--

CREATE TABLE `jspence` (
  `company_name` varchar(300) DEFAULT NULL,
  `company_address` varchar(300) DEFAULT NULL,
  `company_phone1` varchar(20) DEFAULT NULL,
  `company_phone2` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jspence`
--

INSERT INTO `jspence` (`company_name`, `company_address`, `company_phone1`, `company_phone2`) VALUES
('J-Spence Company Limited', 'Box SN 17, SANTASI KUMASI', '+233 (0) 24 412 5900', '+233 (0) 50 414 6600');

-- --------------------------------------------------------

--
-- Table structure for table `jspence_admin`
--

CREATE TABLE `jspence_admin` (
  `id` int(11) NOT NULL,
  `admin_id` varchar(300) NOT NULL,
  `admin_fullname` varchar(255) NOT NULL,
  `admin_email` varchar(175) NOT NULL,
  `admin_phone` varchar(20) DEFAULT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_pin` int(11) DEFAULT 1234,
  `admin_profile` text DEFAULT NULL,
  `admin_joined_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_last_login` datetime DEFAULT NULL,
  `admin_permissions` varchar(255) NOT NULL,
  `admin_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_daily`
--

CREATE TABLE `jspence_daily` (
  `id` bigint(20) NOT NULL,
  `daily_id` varchar(300) DEFAULT NULL,
  `daily_capital` double(10,2) DEFAULT NULL,
  `daily_balance` double(10,2) DEFAULT NULL,
  `daily_profit` double(10,2) DEFAULT NULL,
  `daily_push` varchar(300) DEFAULT NULL,
  `daily_date` date DEFAULT NULL,
  `daily_to` varchar(300) DEFAULT NULL,
  `daily_capital_status` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_denomination`
--

CREATE TABLE `jspence_denomination` (
  `id` bigint(20) NOT NULL,
  `denominations_id` varchar(300) DEFAULT NULL,
  `denomination_capital` varchar(300) DEFAULT NULL,
  `denomination_by` varchar(300) DEFAULT NULL,
  `denomination_200c` int(11) DEFAULT 0,
  `denomination_200c_amt` double(10,2) DEFAULT NULL,
  `denomination_100c` int(11) DEFAULT 0,
  `denomination_100c_amt` double(10,2) DEFAULT NULL,
  `denomination_50c` int(11) DEFAULT 0,
  `denomination_50c_amt` double(10,2) DEFAULT NULL,
  `denomination_20c` int(11) DEFAULT 0,
  `denomination_20c_amt` double(10,2) DEFAULT NULL,
  `denomination_10c` int(11) DEFAULT 0,
  `denomination_10c_amt` double(10,2) DEFAULT NULL,
  `denomination_5c` int(11) DEFAULT 0,
  `denomination_5c_amt` double(10,2) DEFAULT NULL,
  `denomination_2c` int(11) DEFAULT 0,
  `denomination_2c_amt` double(10,2) DEFAULT NULL,
  `denomination_1c` int(11) DEFAULT 0,
  `denomination_1c_amt` double(10,2) DEFAULT NULL,
  `denomination_50p` int(11) DEFAULT 0,
  `denomination_50p_amt` double(10,2) DEFAULT NULL,
  `denomination_20p` int(11) DEFAULT 0,
  `denomination_20p_amt` double(10,2) DEFAULT NULL,
  `denomination_10p` int(11) DEFAULT 0,
  `denomination_10p_amt` double(10,2) DEFAULT NULL,
  `denomination_5p` int(11) DEFAULT 0,
  `denomination_5p_amt` double(10,2) DEFAULT NULL,
  `denomination_1p` int(11) DEFAULT 0,
  `denomination_1p_amt` double(10,2) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_logs`
--

CREATE TABLE `jspence_logs` (
  `id` bigint(20) NOT NULL,
  `log_id` varchar(300) DEFAULT NULL,
  `log_message` text DEFAULT NULL,
  `log_admin` varchar(300) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `log_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_pushes`
--

CREATE TABLE `jspence_pushes` (
  `id` bigint(20) NOT NULL,
  `push_id` varchar(300) DEFAULT NULL,
  `push_daily` varchar(300) DEFAULT NULL,
  `push_amount` double(10,2) NOT NULL,
  `push_from` varchar(300) DEFAULT NULL,
  `push_to` varchar(300) DEFAULT NULL,
  `push_date` date DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `push_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_sales`
--

CREATE TABLE `jspence_sales` (
  `id` bigint(20) NOT NULL,
  `sale_id` varchar(300) NOT NULL,
  `sale_gram` double(10,2) DEFAULT NULL,
  `sale_volume` double(10,2) DEFAULT NULL,
  `sale_density` double(10,2) DEFAULT NULL,
  `sale_pounds` double(10,2) DEFAULT NULL,
  `sale_carat` double(10,2) DEFAULT NULL,
  `sale_price` double(10,2) DEFAULT NULL,
  `sale_total_amount` double(10,2) DEFAULT NULL,
  `sale_customer_name` varchar(200) DEFAULT NULL,
  `sale_customer_contact` varchar(100) DEFAULT NULL,
  `sale_comment` text DEFAULT NULL,
  `sale_type` enum('in','out','exp') DEFAULT NULL,
  `sale_by` varchar(300) DEFAULT NULL,
  `sale_daily` varchar(300) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `sale_status` tinyint(4) NOT NULL DEFAULT 0,
  `sale_delete_request_reason` varchar(700) DEFAULT NULL,
  `sale_delete_request_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jspence_admin`
--
ALTER TABLE `jspence_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_email_2` (`admin_email`),
  ADD KEY `admin_status` (`admin_status`),
  ADD KEY `admin_permissions` (`admin_permissions`),
  ADD KEY `admin_last_login` (`admin_last_login`),
  ADD KEY `admin_email` (`admin_email`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `jspence_daily`
--
ALTER TABLE `jspence_daily`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_id` (`daily_id`),
  ADD KEY `daily_capital` (`daily_capital`),
  ADD KEY `daily_date` (`daily_date`),
  ADD KEY `createdAt` (`createdAt`),
  ADD KEY `status` (`status`),
  ADD KEY `daily_push` (`daily_push`);

--
-- Indexes for table `jspence_denomination`
--
ALTER TABLE `jspence_denomination`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jspence_logs`
--
ALTER TABLE `jspence_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createdAt` (`createdAt`),
  ADD KEY `log_status` (`log_status`),
  ADD KEY `log_admin` (`log_admin`),
  ADD KEY `log_id` (`log_id`);

--
-- Indexes for table `jspence_pushes`
--
ALTER TABLE `jspence_pushes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `capital_id` (`push_id`),
  ADD KEY `capital_daily` (`push_daily`),
  ADD KEY `capital_amount` (`push_amount`),
  ADD KEY `capital_added_by` (`push_to`),
  ADD KEY `createdAt` (`createdAt`),
  ADD KEY `capital_status` (`push_status`);

--
-- Indexes for table `jspence_sales`
--
ALTER TABLE `jspence_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `sale_total_amount` (`sale_total_amount`),
  ADD KEY `sale_by` (`sale_by`),
  ADD KEY `createdAt` (`createdAt`),
  ADD KEY `sale_status` (`sale_status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jspence_admin`
--
ALTER TABLE `jspence_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_daily`
--
ALTER TABLE `jspence_daily`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_denomination`
--
ALTER TABLE `jspence_denomination`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_logs`
--
ALTER TABLE `jspence_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_pushes`
--
ALTER TABLE `jspence_pushes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_sales`
--
ALTER TABLE `jspence_sales`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
