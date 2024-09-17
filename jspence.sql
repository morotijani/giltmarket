-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 15, 2024 at 12:26 PM
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
(1, '234234234', 'jspence', 'admin@jspence.com', '$2y$10$dHoeddyBK4Z23jqePowDO.JPAeXDugtyZN6.Zwc8hy.033Z1/5vbq', 1234, 'dist/media/admin-profiles/961e39eea7a28892e44874f9d34eaa28.jpg', '2020-02-21 21:01:31', '2024-09-04 15:32:31', 'admin,salesperson', 0);

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

--
-- Dumping data for table `jspence_logs`
--

INSERT INTO `jspence_logs` (`id`, `log_id`, `log_message`, `log_admin`, `createdAt`, `updatedAt`, `log_status`) VALUES
(44, 'a6d65ece-f840-44aa-b4a3-3be208eee3f5', 'logged into the system', '234234234', '2024-07-07 20:59:44', NULL, 0),
(45, '42a5c868-27ed-40ef-a7c6-8ca33ed7698e', 'updated profile details', '234234234', '2024-07-07 21:00:34', NULL, 0),
(46, 'c21e160a-a110-4b43-93b9-8a3ca3be13b2', 'logged into the system', '234234234', '2024-07-08 21:50:31', NULL, 0),
(47, '885abfc4-4f6d-4250-94c6-b56764731bfb', 'logged out from system', '234234234', '2024-07-08 21:50:43', NULL, 0),
(48, '722be2f6-5cac-4947-837d-73d9ecef5e3d', 'logged into the system', '234234234', '2024-07-23 14:16:07', NULL, 0),
(49, '14065afd-da65-4ecb-a3f3-9f8a5cb11532', 'logged out from system', '234234234', '2024-08-13 11:05:29', NULL, 0),
(50, 'f33abe56-9cb1-46f0-9bb3-a82e1f7c1010', 'logged into the system', '234234234', '2024-09-04 12:44:03', NULL, 0),
(51, '8d7ad2de-c0a1-4499-9ca1-d2d765eb856d', 'logged out from system', '234234234', '2024-09-04 12:45:12', NULL, 0),
(52, '428c21aa-0d12-488b-8790-7b08a3d3c083', 'logged into the system', '234234234', '2024-09-04 12:45:40', NULL, 0),
(53, '0fa17dd8-6bf4-4a04-836c-86035f79cd74', 'viewed all new delete request', '234234234', '2024-09-04 13:22:06', NULL, 0),
(54, '5255a365-3e77-4187-adc0-318311039c0d', 'logged out from system', '234234234', '2024-09-04 14:50:31', NULL, 0),
(55, '799e4dd0-8d60-4686-b199-3bb04b1e7f0d', 'logged into the system', '234234234', '2024-09-04 14:52:03', NULL, 0),
(56, 'a6915ff3-79ba-4fde-b3c8-9537613c4c28', 'logged out from system', '234234234', '2024-09-04 14:56:15', NULL, 0),
(57, 'e2ed8946-e121-4217-b0ba-e5355d83e13a', 'logged into the system', '234234234', '2024-09-04 15:32:31', NULL, 0),
(58, '61049019-8897-4439-ba93-b8b79a35382f', 'added new sale with gram of 53.91 and volume of 2.86 and total amount of ₵67,976.00 and price of ₵9,630.00 on id e4ff0632-5b2d-4800-9015-7be650004e22', '234234234', '2024-09-04 15:36:43', NULL, 0);

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
  `sale_by` varchar(300) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `sale_status` tinyint(4) NOT NULL DEFAULT 0,
  `sale_delete_request_reason` varchar(700) DEFAULT NULL,
  `sale_delete_request_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jspence_sales`
--

INSERT INTO `jspence_sales` (`id`, `sale_id`, `sale_gram`, `sale_volume`, `sale_density`, `sale_pounds`, `sale_carat`, `sale_price`, `sale_total_amount`, `sale_customer_name`, `sale_customer_contact`, `sale_comment`, `sale_by`, `createdAt`, `updatedAt`, `sale_status`, `sale_delete_request_reason`, `sale_delete_request_status`) VALUES
(6, 'e4ff0632-5b2d-4800-9015-7be650004e22', 53.91, 2.86, 18.84, 6.95, 23.36, 9630.00, 67976.00, 'Abu', '098765432345', '', '234234234', '2024-09-04 15:36:43', NULL, 0, NULL, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jspence_logs`
--
ALTER TABLE `jspence_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `jspence_sales`
--
ALTER TABLE `jspence_sales`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
