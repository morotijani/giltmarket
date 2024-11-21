-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 21, 2024 at 10:27 AM
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
(1, 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'alhaji priest babson', 'admin@jspence.com', NULL, '$2y$10$dHoeddyBK4Z23jqePowDO.JPAeXDugtyZN6.Zwc8hy.033Z1/5vbq', 1234, 'assets/media/admin-profiles/971aaa4a3274e711f35f7201d56d19c9.png', '2020-02-21 21:01:31', '2024-11-21 09:19:25', 'admin,salesperson,supervisor', 0),
(11, '16acd24f-0ad7-42d9-a565-a8863f4a8fa2', 'tijani moro', 'tijani@jspence.com', NULL, '$2y$10$6VM4wWjd3Ts2snR4KDRS9On2bRxzXJ0V/TXplZHs0ZL93y.G/RqWu', 1234, NULL, '2024-06-28 05:48:12', '2024-09-15 22:49:51', 'salesperson', 1),
(12, 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', 'inuwa mohammed umar', 'inuwa@jspence.com', NULL, '$2y$10$7mg6BRD9UXqQL8wxUiCkQe5IqceroHPGvq8wMgiiTCpFEOYsUdcNq', 2222, NULL, '2024-06-28 05:49:20', '2024-11-21 09:19:35', 'salesperson', 0),
(13, '404d51db-6533-4586-b8d5-17c27c2f0607', 'henry asamoah', 'henry@email.com', NULL, '$2y$10$.pYicI6NOTj8Rd8S878EB.Hn6uoxCQXkix7uJgXlvxx1eR8iV1dLq', 1234, NULL, '2024-07-01 14:21:26', '2024-11-14 08:21:47', 'salesperson', 0),
(14, '59e29767-cc32-4b2b-9abf-8422e2e45dcd', 'Adiza husein', 'adiza@email.com', NULL, '$2y$10$cC84GJNvi4Tq/6gm.r.ft.G9YEZ267sz3JQ/B/b.Nl5Cz6Fa64z9S', 1234, NULL, '2024-07-01 22:33:16', NULL, 'admin,salesperson,supervisor', 0),
(15, '986785d8-7b98-4747-a0b2-8b4f4b239e06', 'emmanuel atim', 'emma@jspence.com', NULL, '$2y$10$lwzmqYK9BHTWrHL0FNxoju1FCQQfOY78T8nb9kEeH0dTzvRCannvW', 1234, NULL, '2024-09-09 17:19:05', '2024-11-21 09:19:15', 'supervisor', 0);

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

--
-- Dumping data for table `jspence_admin_login_details`
--

INSERT INTO `jspence_admin_login_details` (`id`, `login_details_id`, `login_details_admin_id`, `admin_device`, `admin_os`, `admin_refferer`, `admin_browser`, `admin_ip`, `createdAt`, `updateAt`, `status`) VALUES
(1, '31cea989-b0c2-45b3-a3b4-b4ab6d013ccd', '986785d8-7b98-4747-a0b2-8b4f4b239e06', 'Desktop', 'Macintosh; Intel Mac OS X 10.15; rv:133.0', 'https://sites.local/jspence/auth/login', 'Firefox', '127.0.0.1', '2024-11-21 09:19:15', NULL, '0'),
(2, '644d72d2-1c90-4f99-8b0b-87ae8356deeb', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', 'Desktop', 'Macintosh; Intel Mac OS X 10_15_7', 'https://sites.local/jspence/auth/login', 'Chrome', '127.0.0.1', '2024-11-21 09:19:25', '2024-11-21 09:19:29', '0'),
(3, '063e352e-78db-44d4-951f-8f3b54d05cee', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', 'Desktop', 'Macintosh; Intel Mac OS X 10_15_7', 'https://sites.local/jspence/auth/login', 'Chrome', '127.0.0.1', '2024-11-21 09:19:35', NULL, '0');

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

--
-- Dumping data for table `jspence_coffers`
--

INSERT INTO `jspence_coffers` (`id`, `coffers_id`, `coffers_amount`, `coffers_status`, `coffers_receive_through`, `createdAt`, `updatedAt`) VALUES
(1, '676a91ed-044f-4c13-a147-21b93c2787b3', 1000000.00, 'receive', 'cash', '2024-11-21 09:19:48', NULL),
(2, '4c7f53d3-d1a8-4b88-b798-57806aaf3c9c', 500000.00, 'send', NULL, '2024-11-21 09:20:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jspence_daily`
--

CREATE TABLE `jspence_daily` (
  `id` bigint(20) NOT NULL,
  `daily_id` varchar(300) DEFAULT NULL,
  `daily_capital` double(10,2) DEFAULT NULL,
  `daily_balance` double(10,2) DEFAULT NULL,
  `daily_date` date DEFAULT current_timestamp(),
  `daily_to` varchar(300) DEFAULT NULL,
  `daily_capital_status` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jspence_daily`
--

INSERT INTO `jspence_daily` (`id`, `daily_id`, `daily_capital`, `daily_balance`, `daily_date`, `daily_to`, `daily_capital_status`, `createdAt`, `updatedAt`, `status`) VALUES
(1, '37dbb77c-166d-42be-b45b-3dbd0a1fda99', 500000.00, 305175.00, '2024-11-21', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', 0, '2024-11-21 09:20:10', '2024-11-21 09:22:52', 0),
(2, '038bf407-3db7-46cd-a050-ac1b8549949d', 194825.00, NULL, '2024-11-21', '986785d8-7b98-4747-a0b2-8b4f4b239e06', 0, '2024-11-21 09:23:20', NULL, 0);

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

--
-- Dumping data for table `jspence_logs`
--

INSERT INTO `jspence_logs` (`id`, `log_id`, `log_message`, `log_admin`, `createdAt`, `updatedAt`, `log_status`) VALUES
(1, '6480c669-9759-49a1-bbe9-ede9b1e148d5', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:19:04', NULL, 0),
(2, '328f0cf3-d02d-47aa-9f7d-f4e26994eee2', 'logged out from system', '986785d8-7b98-4747-a0b2-8b4f4b239e06', '2024-11-21 09:19:08', NULL, 0),
(3, '5ee899ba-511d-4fcc-870d-36dc0620a7a1', 'logged into the system', '986785d8-7b98-4747-a0b2-8b4f4b239e06', '2024-11-21 09:19:15', NULL, 0),
(4, '8cb93017-cc27-4474-9fed-3775edebe86a', 'logged into the system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', '2024-11-21 09:19:25', NULL, 0),
(5, 'd0d3392e-a676-4013-9c9e-bf5bc9532722', 'logged out from system', 'c454b2bf-9b1e-409a-8b0d-d84ab82cf22d', '2024-11-21 09:19:29', NULL, 0),
(6, 'b0cd6b96-eb96-41fc-8593-272bd3456609', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:19:35', NULL, 0),
(7, '0d85e562-9bfe-4df9-ad19-cce9d21ae2b1', '₵1,000,000.00 from CASH has been add to coffers', '986785d8-7b98-4747-a0b2-8b4f4b239e06', '2024-11-21 09:19:48', NULL, 0),
(8, '1721c3ef-7aeb-4607-b275-707ed0da3069', 'push made on 2024-11-21, of an amount of ₵500,000.00 to a saleperson id: e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '986785d8-7b98-4747-a0b2-8b4f4b239e06', '2024-11-21 09:20:10', NULL, 0),
(9, 'db2d6f18-9116-436c-862f-b8e8db9e8e92', 'on this day 2024-11-21, capital entered of an amount of ₵0.00 to a saleperson id: e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '986785d8-7b98-4747-a0b2-8b4f4b239e06', '2024-11-21 09:20:10', NULL, 0),
(10, '90c95bdb-9cc8-4cdc-8219-54438742f2db', 'bought made, balance remaining is: ₵388,909.00 and 2024-11-21 capital was:  ₵500,000.00', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:21:55', NULL, 0),
(11, '9a89e938-c7da-4a79-8bb0-5050c8f799f2', 'added new sale with gram of 56.44 and volume of 2.1 and total amount of ₵111,091.00 and price of ₵10,910.00 on id 87e74079-40e2-4ecd-9156-ade1a2055a72', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:21:55', NULL, 0),
(12, '24f12552-3671-4db7-9cc9-3b761d9cd245', 'bought made, balance remaining is: ₵347,042.00 and 2024-11-21 capital was:  ₵500,000.00', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:22:28', NULL, 0),
(13, '923be806-277d-4aff-8d7c-adc50449bae0', 'added new sale with gram of 34 and volume of 2 and total amount of ₵41,867.00 and price of ₵10,900.00 on id 1dfd9952-4f60-4235-94a8-5801bfc03698', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:22:28', NULL, 0),
(14, 'ea2f78c8-e03d-4dcd-9f2c-2768b94824ea', 'bought made, balance remaining is: ₵305,175.00 and 2024-11-21 capital was:  ₵500,000.00', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:22:52', NULL, 0),
(15, '3d639e57-1a22-4bcc-9132-9384f6aaff2f', 'added new sale with gram of 34 and volume of 2 and total amount of ₵41,867.00 and price of ₵10,900.00 on id 8747ae7c-a637-4f4a-8b70-a3b917287cee', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:22:52', NULL, 0),
(16, 'cf13a523-87e2-40d4-b2d3-3e607b50212a', 'push made on 2024-11-21, of an amount of ₵194,825.00 to a saleperson id: 986785d8-7b98-4747-a0b2-8b4f4b239e06', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:23:20', NULL, 0),
(17, '83e777b5-a798-4210-b842-dc174f5470e5', 'on this day 2024-11-21, capital entered of an amount of ₵0.00 to a saleperson id: 986785d8-7b98-4747-a0b2-8b4f4b239e06', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21 09:23:20', NULL, 0);

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
  `push_date` date DEFAULT current_timestamp(),
  `push_from_where` enum('dialy','coffers','end-trade','physical-cash') DEFAULT NULL,
  `push_data` text DEFAULT NULL,
  `push_note` varchar(500) DEFAULT NULL,
  `push_reverse_reason` varchar(500) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `push_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jspence_pushes`
--

INSERT INTO `jspence_pushes` (`id`, `push_id`, `push_daily`, `push_amount`, `push_type`, `push_from`, `push_to`, `push_date`, `push_from_where`, `push_data`, `push_note`, `push_reverse_reason`, `createdAt`, `updatedAt`, `push_status`) VALUES
(1, '883b2aee-18d5-4912-81a8-c65a029ce376', '676a91ed-044f-4c13-a147-21b93c2787b3', 1000000.00, 'money', '986785d8-7b98-4747-a0b2-8b4f4b239e06', 'coffers', '2024-11-21', 'physical-cash', NULL, NULL, NULL, '2024-11-21 09:19:48', NULL, 0),
(2, '8ec16a3a-fbd9-4d56-8a0d-4de87348e931', '4c7f53d3-d1a8-4b88-b798-57806aaf3c9c', 500000.00, 'money', '986785d8-7b98-4747-a0b2-8b4f4b239e06', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-11-21', 'coffers', NULL, '', NULL, '2024-11-21 09:20:10', NULL, 0),
(3, 'f5593409-cf64-4636-8377-ca826d0f5613', '038bf407-3db7-46cd-a050-ac1b8549949d', 194825.00, 'gold', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '986785d8-7b98-4747-a0b2-8b4f4b239e06', '2024-11-21', 'dialy', '{\"gram\":124.43999999999999772626324556767940521240234375,\"volume\":6.0999999999999996447286321199499070644378662109375,\"density\":20.39999999999999857891452847979962825775146484375,\"pounds\":16.050000000000000710542735760100185871124267578125,\"carat\":25.6099999999999994315658113919198513031005859375}', '', NULL, '2024-11-21 09:23:20', NULL, 0);

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
-- Dumping data for table `jspence_sales`
--

INSERT INTO `jspence_sales` (`id`, `sale_id`, `sale_gram`, `sale_volume`, `sale_density`, `sale_pounds`, `sale_carat`, `sale_price`, `sale_total_amount`, `sale_customer_name`, `sale_customer_contact`, `sale_comment`, `sale_type`, `sale_by`, `sale_daily`, `sale_pushed`, `createdAt`, `updatedAt`, `sale_status`, `sale_delete_request_reason`, `sale_delete_request_status`) VALUES
(1, '87e74079-40e2-4ecd-9156-ade1a2055a72', 56.44, 2.10, 26.87, 7.28, 32.17, 10910.00, 111091.00, 'sdfds', 'sdfsd', '', 'out', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '37dbb77c-166d-42be-b45b-3dbd0a1fda99', 1, '2024-11-21 09:21:55', '2024-11-21 09:23:20', 0, NULL, 0),
(2, '1dfd9952-4f60-4235-94a8-5801bfc03698', 34.00, 2.00, 17.00, 4.38, 20.17, 10900.00, 41867.00, 'ewf', 'wef', '', 'out', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '37dbb77c-166d-42be-b45b-3dbd0a1fda99', 1, '2024-11-21 09:22:28', '2024-11-21 09:23:20', 0, NULL, 0),
(3, '8747ae7c-a637-4f4a-8b70-a3b917287cee', 34.00, 2.00, 17.00, 4.38, 20.17, 10900.00, 41867.00, 'wed', 'we', '', 'out', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '37dbb77c-166d-42be-b45b-3dbd0a1fda99', 1, '2024-11-21 09:22:52', '2024-11-21 09:23:20', 0, NULL, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jspence_coffers`
--
ALTER TABLE `jspence_coffers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jspence_daily`
--
ALTER TABLE `jspence_daily`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jspence_denomination`
--
ALTER TABLE `jspence_denomination`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jspence_logs`
--
ALTER TABLE `jspence_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jspence_pushes`
--
ALTER TABLE `jspence_pushes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jspence_sales`
--
ALTER TABLE `jspence_sales`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
