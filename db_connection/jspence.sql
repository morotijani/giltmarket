-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 16, 2024 at 01:35 AM
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
  `admin_password` varchar(255) NOT NULL,
  `admin_pin` int(11) DEFAULT 1234,
  `admin_profile` text DEFAULT NULL,
  `admin_joined_date` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_last_login` datetime DEFAULT NULL,
  `admin_permissions` varchar(255) NOT NULL,
  `admin_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jspence_admin`
--

INSERT INTO `jspence_admin` (`id`, `admin_id`, `admin_fullname`, `admin_email`, `admin_password`, `admin_pin`, `admin_profile`, `admin_joined_date`, `admin_last_login`, `admin_permissions`, `admin_status`) VALUES
(1, '234234234', 'alhaji priest babson', 'admin@jspence.com', '$2y$10$dHoeddyBK4Z23jqePowDO.JPAeXDugtyZN6.Zwc8hy.033Z1/5vbq', 1234, 'dist/media/admin-profiles/961e39eea7a28892e44874f9d34eaa28.jpg', '2020-02-21 21:01:31', '2024-09-15 22:58:17', 'admin,salesperson,supervisor', 0),
(11, '16acd24f-0ad7-42d9-a565-a8863f4a8fa2', 'tijani moro', 'tijani@jspence.com', '$2y$10$6VM4wWjd3Ts2snR4KDRS9On2bRxzXJ0V/TXplZHs0ZL93y.G/RqWu', 1234, NULL, '2024-06-28 05:48:12', '2024-09-15 22:49:51', 'salesperson', 1),
(12, 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', 'inuwa mohammed umar', 'inuwa@jspence.com', '$2y$10$7mg6BRD9UXqQL8wxUiCkQe5IqceroHPGvq8wMgiiTCpFEOYsUdcNq', 2222, NULL, '2024-06-28 05:49:20', '2024-07-05 06:28:04', 'salesperson', 0),
(13, '404d51db-6533-4586-b8d5-17c27c2f0607', 'henry asamoah', 'henry@email.com', '$2y$10$.pYicI6NOTj8Rd8S878EB.Hn6uoxCQXkix7uJgXlvxx1eR8iV1dLq', 1234, NULL, '2024-07-01 14:21:26', '2024-09-10 15:12:58', 'salesperson', 0),
(14, '59e29767-cc32-4b2b-9abf-8422e2e45dcd', 'Adiza husein', 'adiza@email.com', '$2y$10$cC84GJNvi4Tq/6gm.r.ft.G9YEZ267sz3JQ/B/b.Nl5Cz6Fa64z9S', 1234, NULL, '2024-07-01 22:33:16', NULL, 'admin,salesperson,supervisor', 0),
(15, '986785d8-7b98-4747-a0b2-8b4f4b239e06', 'emmanuel atim', 'emma@jspence.com', '$2y$10$lwzmqYK9BHTWrHL0FNxoju1FCQQfOY78T8nb9kEeH0dTzvRCannvW', 1234, NULL, '2024-09-09 17:19:05', '2024-09-14 20:53:07', 'supervisor', 0);

-- --------------------------------------------------------

--
-- Table structure for table `jspence_daily`
--

CREATE TABLE `jspence_daily` (
  `id` bigint(20) NOT NULL,
  `daily_id` varchar(300) DEFAULT NULL,
  `daily_capital` double(10,2) NOT NULL,
  `daily_balance` double(10,2) NOT NULL DEFAULT 0.00,
  `daily_date` date DEFAULT NULL,
  `daily_by` varchar(300) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_expenditures`
--

CREATE TABLE `jspence_expenditures` (
  `id` bigint(20) NOT NULL,
  `expenditure_id` varchar(300) DEFAULT NULL,
  `expenditure_capital_id` varchar(300) DEFAULT NULL,
  `expenditure_what_for` varchar(800) DEFAULT NULL,
  `expenditure_amount` double(10,2) NOT NULL,
  `expenditure_by` varchar(300) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_logs`
--

CREATE TABLE `jspence_logs` (
  `id` bigint(20) NOT NULL,
  `log_id` varchar(300) NOT NULL,
  `log_message` text NOT NULL,
  `log_admin` varchar(300) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `log_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_sales`
--

CREATE TABLE `jspence_sales` (
  `id` bigint(20) NOT NULL,
  `sale_id` varchar(300) NOT NULL,
  `sale_gram` double(10,2) NOT NULL,
  `sale_volume` double(10,2) NOT NULL,
  `sale_density` double(10,2) NOT NULL,
  `sale_pounds` double(10,2) NOT NULL,
  `sale_carat` double(10,2) NOT NULL,
  `sale_price` double(10,2) NOT NULL,
  `sale_total_amount` double(10,2) NOT NULL,
  `sale_customer_name` varchar(200) DEFAULT NULL,
  `sale_customer_contact` varchar(100) DEFAULT NULL,
  `sale_comment` text NOT NULL,
  `sale_type` enum('in','out') DEFAULT NULL,
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
  ADD KEY `status` (`status`);

--
-- Indexes for table `jspence_expenditures`
--
ALTER TABLE `jspence_expenditures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenditure_id` (`expenditure_id`),
  ADD KEY `expenditure_capital_id` (`expenditure_capital_id`),
  ADD KEY `status` (`status`),
  ADD KEY `createdAt` (`createdAt`),
  ADD KEY `expenditure_by` (`expenditure_by`),
  ADD KEY `expenditure_amount` (`expenditure_amount`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `jspence_daily`
--
ALTER TABLE `jspence_daily`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_expenditures`
--
ALTER TABLE `jspence_expenditures`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_logs`
--
ALTER TABLE `jspence_logs`
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
