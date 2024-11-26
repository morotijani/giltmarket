-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 26, 2024 at 07:32 PM
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
  `admin_id` varchar(100) NOT NULL,
  `admin_fullname` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_phone` varchar(20) DEFAULT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_pin` int(11) DEFAULT 1234,
  `admin_profile` text DEFAULT NULL,
  `admin_joined_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_last_login` datetime DEFAULT NULL,
  `admin_permissions` varchar(255) NOT NULL,
  `admin_status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jspence_admin`
--

INSERT INTO `jspence_admin` (`id`, `admin_id`, `admin_fullname`, `admin_email`, `admin_phone`, `admin_password`, `admin_pin`, `admin_profile`, `admin_joined_date`, `admin_last_login`, `admin_permissions`, `admin_status`) VALUES
(1, 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'alhaji priest babson', 'admin@jspence.com', NULL, '$2y$10$dHoeddyBK4Z23jqePowDO.JPAeXDugtyZN6.Zwc8hy.033Z1/5vbq', 1234, 'assets/media/admin-profiles/971aaa4a3274e711f35f7201d56d19c9.png', '2020-02-21 21:01:31', '2024-11-25 01:22:52', 'admin,salesperson,supervisor', 0),
(11, '16acd24f-0ad7-42d9-a565-a8863f4a8fa2', 'tijani moro', 'tijani@jspence.com', NULL, '$2y$10$6VM4wWjd3Ts2snR4KDRS9On2bRxzXJ0V/TXplZHs0ZL93y.G/RqWu', 1234, NULL, '2024-06-28 05:48:12', '2024-09-15 22:49:51', 'salesperson', 1),
(12, 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', 'inuwa mohammed umar', 'inuwa@jspence.com', NULL, '$2y$10$7mg6BRD9UXqQL8wxUiCkQe5IqceroHPGvq8wMgiiTCpFEOYsUdcNq', 2222, NULL, '2024-06-28 05:49:20', '2024-11-26 15:53:49', 'salesperson', 0),
(13, '404d51db-6533-4586-b8d5-17c27c2f0607', 'henry asamoah', 'henry@email.com', NULL, '$2y$10$.pYicI6NOTj8Rd8S878EB.Hn6uoxCQXkix7uJgXlvxx1eR8iV1dLq', 1234, NULL, '2024-07-01 14:21:26', '2024-11-14 08:21:47', 'salesperson', 0),
(14, '59e29767-cc32-4b2b-9abf-8422e2e45dcd', 'Adiza husein', 'adiza@email.com', NULL, '$2y$10$cC84GJNvi4Tq/6gm.r.ft.G9YEZ267sz3JQ/B/b.Nl5Cz6Fa64z9S', 1234, NULL, '2024-07-01 22:33:16', NULL, 'admin,salesperson,supervisor', 0),
(15, '986785d8-7b98-4747-a0b2-8b4f4b239e06', 'emmanuel atim', 'emma@jspence.com', NULL, '$2y$10$lwzmqYK9BHTWrHL0FNxoju1FCQQfOY78T8nb9kEeH0dTzvRCannvW', 1234, NULL, '2024-09-09 17:19:05', '2024-11-26 15:53:15', 'supervisor', 0);

-- --------------------------------------------------------

--
-- Table structure for table `jspence_admin_login_details`
--

CREATE TABLE `jspence_admin_login_details` (
  `id` int(11) NOT NULL,
  `login_details_id` varchar(100) DEFAULT NULL,
  `login_details_admin_id` varchar(100) DEFAULT NULL,
  `admin_device` varchar(100) DEFAULT NULL,
  `admin_os` varchar(300) DEFAULT NULL,
  `admin_refferer` varchar(300) DEFAULT NULL,
  `admin_browser` varchar(300) DEFAULT NULL,
  `admin_ip` varchar(100) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `updateAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` tinytext NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_coffers`
--

CREATE TABLE `jspence_coffers` (
  `id` bigint(20) NOT NULL,
  `coffers_id` varchar(100) DEFAULT NULL,
  `coffers_amount` double(10,2) DEFAULT NULL,
  `coffers_status` enum('receive','send','reverse') DEFAULT NULL,
  `coffers_receive_through` enum('cash','trades','end_trade_balance') DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_customers`
--

CREATE TABLE `jspence_customers` (
  `id` bigint(20) NOT NULL,
  `customer_id` varchar(150) DEFAULT NULL,
  `customer_name` varchar(300) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `customer_unique_info` varchar(300) DEFAULT NULL,
  `customer_city` varchar(300) DEFAULT NULL,
  `customer_region` varchar(300) DEFAULT NULL,
  `customer_address` varchar(300) DEFAULT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jspence_daily`
--

CREATE TABLE `jspence_daily` (
  `id` bigint(20) NOT NULL,
  `daily_id` varchar(300) DEFAULT NULL,
  `daily_capital` double(10,2) DEFAULT 0.00,
  `daily_balance` double(10,2) DEFAULT 0.00,
  `daily_profit` double(10,2) DEFAULT 0.00,
  `daily_date` date DEFAULT current_timestamp(),
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
  `denomination_have_cash` enum('yes','no') DEFAULT NULL,
  `denomination_checker` enum('nothing','forgot','something-else') DEFAULT NULL,
  `denomination_data` text DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` tinyint(1) DEFAULT 0
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
  `push_amount` double(10,2) DEFAULT NULL,
  `push_type` enum('money','gold') DEFAULT NULL,
  `push_from` varchar(300) DEFAULT NULL,
  `push_to` varchar(300) DEFAULT NULL,
  `push_from_where` enum('daily','coffers','end-trade','physical-cash') DEFAULT NULL,
  `push_date` date DEFAULT current_timestamp(),
  `push_data` text DEFAULT NULL,
  `push_note` varchar(500) DEFAULT NULL,
  `push_reverse_reason` varchar(500) DEFAULT NULL,
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
  `sale_pushed` tinyint(1) DEFAULT 0,
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
-- Indexes for table `jspence_admin_login_details`
--
ALTER TABLE `jspence_admin_login_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jspence_coffers`
--
ALTER TABLE `jspence_coffers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coffer_id` (`coffers_id`),
  ADD KEY `createdAt` (`createdAt`),
  ADD KEY `coffers_status` (`coffers_status`),
  ADD KEY `coffers_receive_through` (`coffers_receive_through`);

--
-- Indexes for table `jspence_customers`
--
ALTER TABLE `jspence_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `customer_name` (`customer_name`),
  ADD KEY `customer_phone` (`customer_phone`),
  ADD KEY `customer_unique_info` (`customer_unique_info`);

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
  ADD KEY `capital_status` (`push_status`),
  ADD KEY `push_type` (`push_type`);

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
-- AUTO_INCREMENT for table `jspence_admin_login_details`
--
ALTER TABLE `jspence_admin_login_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_coffers`
--
ALTER TABLE `jspence_coffers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_customers`
--
ALTER TABLE `jspence_customers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
