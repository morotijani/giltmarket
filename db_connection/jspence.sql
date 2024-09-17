-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2024 at 11:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
(1, '234234234', 'alhaji priest babson', 'admin@jspence.com', '$2y$10$dHoeddyBK4Z23jqePowDO.JPAeXDugtyZN6.Zwc8hy.033Z1/5vbq', 1234, 'dist/media/admin-profiles/961e39eea7a28892e44874f9d34eaa28.jpg', '2020-02-21 21:01:31', '2024-07-05 09:38:30', 'admin,salesperson', 0),
(11, '16acd24f-0ad7-42d9-a565-a8863f4a8fa2', 'tijani moro', 'tijani@jspence.com', '$2y$10$6VM4wWjd3Ts2snR4KDRS9On2bRxzXJ0V/TXplZHs0ZL93y.G/RqWu', 1234, NULL, '2024-06-28 05:48:12', '2024-06-28 15:35:04', 'salesperson', 1),
(12, 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', 'inuwa mohammed umar', 'inuwa@jspence.com', '$2y$10$7mg6BRD9UXqQL8wxUiCkQe5IqceroHPGvq8wMgiiTCpFEOYsUdcNq', 2222, NULL, '2024-06-28 05:49:20', '2024-07-05 06:28:04', 'salesperson', 0),
(13, '404d51db-6533-4586-b8d5-17c27c2f0607', 'henry asamoah', 'henry@email.com', '$2y$10$.pYicI6NOTj8Rd8S878EB.Hn6uoxCQXkix7uJgXlvxx1eR8iV1dLq', 1234, NULL, '2024-07-01 14:21:26', '2024-07-04 11:07:23', 'salesperson', 0),
(14, '59e29767-cc32-4b2b-9abf-8422e2e45dcd', 'Adiza husein', 'adiza@email.com', '$2y$10$cC84GJNvi4Tq/6gm.r.ft.G9YEZ267sz3JQ/B/b.Nl5Cz6Fa64z9S', 1234, NULL, '2024-07-01 22:33:16', NULL, 'admin,salesperson', 0);

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
(1, '3113addb-2062-4ffe-a67d-f1ddb2bd9069', 'logged out from system', '234234234', '2024-06-30 11:20:22', NULL, 0),
(2, '04ae0617-f7e5-455f-b290-27611bb5658b', 'logged into the system', '234234234', '2024-06-30 11:20:34', NULL, 0),
(3, '035817b7-78a8-4ee5-b203-b0ced1561bd4', 'added new sale with gram of 15.37 and volume of 0.87 and total amount of ₵3,878.62 and price of ₵8,250.00 on id 13fafb53-a7b3-49ca-bb15-fdcd19a2cabf', '234234234', '2024-06-30 11:35:49', NULL, 0),
(4, '0b3ed1ac-3f0a-4c24-b7e3-29753e44801d', 'logged out from system', '234234234', '2024-06-30 11:46:36', NULL, 0),
(5, 'd078630f-b849-4b4a-a2c5-8dc40ba4f0b8', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 11:46:43', NULL, 0),
(6, 'a1df82c2-b185-49a2-b37b-ada621927133', 'added new sale with gram of 23.45 and volume of 1.1 and total amount of ₵3,171.44 and price of ₵8,250.00 on id b772ff1d-401e-418d-a2cf-1c98f7e372dd', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 11:58:24', NULL, 0),
(7, '2faa8df5-9ddd-4f78-8d17-404c31feb917', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 12:59:21', NULL, 0),
(8, '39fef907-d996-4b83-b405-12d85768d39c', 'logged into the system', '234234234', '2024-06-30 12:59:31', NULL, 0),
(9, 'd16484bb-2f78-4383-a8b2-3d7547b3a4df', 'logged out from system', '234234234', '2024-06-30 13:16:10', NULL, 0),
(10, '6f06bb8d-9057-43c4-8a6e-afb798936779', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 13:16:20', NULL, 0),
(11, '1f5201f5-8f7c-4bc8-a0eb-70abed7c0c8a', 'added new sale with gram of 23 and volume of 1 and total amount of ₵1,467.05 and price of ₵3,493.00 on id 8f148e3d-d1c0-4b4c-8273-9d539c58db8f', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 13:26:52', NULL, 0),
(12, '273094fe-571c-4023-ae7f-cd9bf6eda4eb', 'added new sale with gram of 15.37 and volume of 0.87 and total amount of ₵15,191.55 and price of ₵8,250.00 on id 92e0691c-81c3-475f-8dd8-b5a1ab1b0d68', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 15:51:00', NULL, 0),
(13, '39b9b759-635d-4a3f-a810-59fc09f83b9f', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 16:02:39', NULL, 0),
(14, '80dabb5c-f432-4c67-a8ae-e9562297b910', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 16:03:36', NULL, 0),
(15, '7083ecef-0196-4465-9599-9483212fe790', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 16:15:17', NULL, 0),
(16, '169f82a1-f1a3-4d25-955e-b7fcc28af060', 'logged into the system', '234234234', '2024-06-30 16:15:23', NULL, 0),
(17, 'e39b8a5b-e7c3-424c-96d1-d14da1d3a571', 'logged out from system', '234234234', '2024-06-30 16:35:45', NULL, 0),
(18, '72320320-23a3-42c7-a2e3-d52275f831e7', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 16:35:52', NULL, 0),
(19, '0e4ab9f4-0c35-47c0-991a-70744854750a', 'added new sale with gram of 123 and volume of 0.98 and total amount of ₵151,381.94 and price of ₵4,532.00 on id c6418219-b87d-491e-b2e5-ecc47371aad0', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 16:37:50', NULL, 0),
(20, '298efc7b-8b4e-4392-ab91-304848c16aee', 'added new sale with gram of 123 and volume of 2 and total amount of ₵37,302.46 and price of ₵1,234.00 on id 0084fe0d-e9f4-4ad4-9dcc-dfac138f3a69', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 17:00:49', NULL, 0),
(21, '3a1e0b85-2bfb-4c0a-8ef2-25c1b14ee546', 'changed PIN', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 17:49:05', NULL, 0),
(22, 'e638cc66-58a9-498d-acc5-4e883fd896a7', 'changed PIN', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 17:49:41', NULL, 0),
(23, 'c684eae5-22b2-40b5-b8db-90d9e85294fb', 'added new sale with gram of 123 and volume of 2 and total amount of ₵70,191.51 and price of ₵2,322.00 on id f8447ded-6b34-40cf-bd3e-1dbc5da48454', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 17:50:39', NULL, 0),
(24, 'af18d5db-ae34-4eb8-8c22-07c97c6b2a77', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-01 11:52:11', NULL, 0),
(25, '613245f2-6ef2-4dfd-89e0-ef1f5156c5f9', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-01 11:53:58', NULL, 0),
(26, 'c1151232-0dfa-435a-9939-6f301173c78e', 'added new sale with gram of 15.37 and volume of 0.87 and total amount of ₵15,191.55 and price of ₵8,250.00 on id 2647d3f4-be16-47a0-b24b-c7cce8988cd3', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-01 12:23:28', NULL, 0),
(27, '6e105892-1abb-4244-96bd-c050cc2f152c', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-01 14:05:33', NULL, 0),
(28, '6ad75911-f640-423a-9fd9-0275f9d13880', 'logged into the system', '234234234', '2024-07-01 14:06:03', NULL, 0),
(29, 'ede7a311-0a7e-44af-b6fe-9739c91540da', 'changed password', '234234234', '2024-07-01 14:21:26', NULL, 0),
(30, '37e73d4e-da7c-4344-ad15-a4807dfc41a6', 'logged into the system', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-01 14:23:06', NULL, 0),
(31, '187c3648-dab5-4525-9c50-255304ce8820', 'added new sale with gram of 45 and volume of 0.98 and total amount of ₵35,496.20 and price of ₵3,450.00 on id 4f666b08-1ac1-447a-90b9-8c5bbeb1cfd9', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-01 14:28:11', NULL, 0),
(32, 'c44f972c-9506-4684-bb24-4a3f5329f0d7', 'logged out from system', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-01 14:36:36', NULL, 0),
(33, '238c4a79-94cc-408e-b53f-6246f5b90535', 'exported xlsx trades data', '234234234', '2024-07-01 22:29:57', NULL, 0),
(34, '50c49e6d-600d-49c4-8a7d-be8b92555134', 'exported xls trades data', '234234234', '2024-07-01 22:30:14', NULL, 0),
(35, '6b2c01e9-684d-4bb4-adbd-99eb8a3f569a', 'exported csv trades data', '234234234', '2024-07-01 22:30:25', NULL, 0),
(36, 'a386f808-783a-4e1f-bed8-2a25bbbc5c2d', 'added new admin as a admin,salesperson', '234234234', '2024-07-01 22:33:16', NULL, 0),
(37, 'aeb80274-cd7e-4df9-8d8e-855d46ef0dd3', 'exported pdf trades data', '234234234', '2024-07-01 22:53:32', NULL, 0),
(38, '19b91a8d-a502-41b9-88ed-ba917f4a0287', 'exported pdf trades data', '234234234', '2024-07-01 22:53:56', NULL, 0),
(39, '893e570a-6465-480f-889b-ee09a18f9abf', 'exported pdf trades data', '234234234', '2024-07-01 22:57:11', NULL, 0),
(40, '493bf057-2646-490a-8c2b-a5d6bd72d916', 'exported pdf trades data', '234234234', '2024-07-01 22:58:11', NULL, 0),
(41, 'f40062ed-1c6c-4dc9-aa9e-5d1bc7880d3e', 'exported pdf trades data', '234234234', '2024-07-01 22:58:27', NULL, 0),
(42, 'f62b30ce-b769-4963-a5a1-0333c02a35d0', 'exported pdf trades data', '234234234', '2024-07-01 23:03:21', NULL, 0),
(43, '7f2d9e80-3478-49eb-8ba3-1271452f4c3b', 'exported pdf trades data', '234234234', '2024-07-01 23:03:49', NULL, 0),
(44, '34e08248-39fc-4a9e-bc30-61a64b4d0db0', 'exported pdf trades data', '234234234', '2024-07-02 00:00:39', NULL, 0),
(45, '06f02231-73fa-4e84-b8f7-f8f87d0ec707', 'exported pdf trades data', '234234234', '2024-07-02 00:00:41', NULL, 0),
(46, '76857d38-b56d-4464-9ae3-746c60673bcb', 'exported pdf trades data', '234234234', '2024-07-02 00:16:25', NULL, 0),
(47, '2722d99d-dcbc-4288-91cf-bddaa8dd982c', 'deleted profile picture', '234234234', '2024-07-02 00:58:32', NULL, 0),
(48, '0d46804b-0778-4ab5-af8d-dba143c77a16', 'logged out from system', '234234234', '2024-07-02 18:01:41', NULL, 0),
(49, '7a85d875-e25c-4dec-ad20-fca9f856726d', 'logged into the system', '234234234', '2024-07-02 18:04:20', NULL, 0),
(50, '0782410a-2b15-4fa4-8bf9-50996372e809', 'updated profile picture', '234234234', '2024-07-02 18:09:25', NULL, 0),
(51, '1409aa6b-09b5-474f-8c0e-faf8732f1ff8', 'logged out from system', '234234234', '2024-07-02 18:18:12', NULL, 0),
(52, '1080904c-c7f1-4f26-a01d-0879c49faf56', 'logged into the system', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-02 18:19:14', NULL, 0),
(53, '231e2d79-ba8e-48da-8d6a-1f5e01f1ebe1', 'logged out from system', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-02 18:49:39', NULL, 0),
(54, '227c5f8d-2acf-434d-9e9c-a42f1de9b3d5', 'logged into the system', '234234234', '2024-07-02 18:49:56', NULL, 0),
(55, 'cdcb8d74-88dd-4bf3-8790-009e91ac4714', 'added new sale with gram of 78 and volume of 1 and total amount of ₵175,679.87 and price of ₵8,787.00 on id f73617f0-c810-4b2b-a4c2-f973e02c04cb', '234234234', '2024-07-02 19:09:30', NULL, 0),
(56, '8a8b0752-b6d8-47c7-be46-94a1063d8057', 'logged into the system', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-03 12:19:23', NULL, 0),
(57, '3bf66aec-9654-4db5-b8a5-e4f517042afc', 'added new sale with gram of 12 and volume of 0.34 and total amount of ₵5,748.95 and price of ₵2,300.00 on id 72de0129-4f1b-4832-9145-8bc849d6b300', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-03 12:21:22', NULL, 0),
(58, '11768c62-fcae-4be7-abf3-4386a14cc384', 'added new sale with gram of 123 and volume of 1 and total amount of ₵152,235.92 and price of ₵4,567.00 on id a017da3b-debe-4724-bcda-99484919a2d2', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-03 12:22:15', NULL, 0),
(59, 'bf6f79fb-d821-44e6-be53-78f67cf42366', 'delete request for trade id: \'a017da3b-debe-4724-bcda-99484919a2d2\'', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-03 17:39:37', NULL, 0),
(60, 'b07d9ce1-d27a-4430-89e1-24afa838db9b', 'viewed all new delete request', '234234234', '2024-07-03 17:45:00', NULL, 0),
(61, '8c58aae4-87f5-4dd2-9eff-1edbbc576d21', 'viewed all new delete request', '234234234', '2024-07-03 17:45:07', NULL, 0),
(62, '6d792a3a-7354-424b-a03f-5c03259f8956', 'viewed all new delete request', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 09:11:41', NULL, 0),
(63, 'f5790b52-b017-4f96-aaa0-2558a249ff9a', 'viewed all new delete request', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 09:16:42', NULL, 0),
(64, 'b2e8fe42-cab4-4a53-87c1-f04a042a5816', 'viewed all new delete request', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 09:17:03', NULL, 0),
(65, 'b3a70481-3ae8-4745-a85c-f8b4e4abd32f', 'viewed all new delete request', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 09:22:41', NULL, 0),
(66, '20bd46ab-e689-4ee1-b9d0-e42a7ba641c5', 'viewed all new delete request', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 09:22:52', NULL, 0),
(67, '21dbe8e7-742f-446f-853a-929f7950dd47', 'viewed all new delete request', '234234234', '2024-07-04 09:23:07', NULL, 0),
(68, '86667517-ae7a-441c-a953-b52684578c16', 'viewed all new delete request', '234234234', '2024-07-04 09:23:34', NULL, 0),
(69, '914f2caf-4973-49b0-b2a1-a5f5a9cbc0a6', 'viewed all new delete request', '234234234', '2024-07-04 09:23:58', NULL, 0),
(70, '607b9edc-2058-4e44-8280-b9c55c83c168', 'viewed all new delete request', '234234234', '2024-07-04 09:24:11', NULL, 0),
(71, '046d2042-cc80-4348-b51d-b3e1fe95f59a', 'viewed all new delete request', '234234234', '2024-07-04 09:26:49', NULL, 0),
(72, '74b6ce53-f256-4513-ae02-8044688b2d63', 'viewed all new delete request', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 09:26:54', NULL, 0),
(73, '62c7125d-2eba-4740-9812-d58d93737dcf', 'viewed all new delete request', '234234234', '2024-07-04 09:27:27', NULL, 0),
(74, 'b0df04e0-70c1-4ffd-b9b8-1bb3cccd42da', 'viewed all new delete request', '234234234', '2024-07-04 09:28:53', NULL, 0),
(75, '7f301dfe-1729-42c4-be1d-9afdd046eb54', 'viewed all new delete request', '234234234', '2024-07-04 09:29:02', NULL, 0),
(76, '37a3638c-69f1-467f-9093-697f8514b5b5', 'viewed all new delete request', '234234234', '2024-07-04 09:29:11', NULL, 0),
(77, 'cefba928-f730-4293-ba48-f0bc2c82e58e', 'viewed all new delete request', '234234234', '2024-07-04 09:40:33', NULL, 0),
(78, 'e32eec01-6510-4197-91a8-55d35a8d6c67', 'viewed all new delete request', '234234234', '2024-07-04 09:40:33', NULL, 0),
(79, 'eee0d5d1-4c93-4075-910d-f5f51483604a', 'viewed all new delete request', '234234234', '2024-07-04 09:40:41', NULL, 0),
(80, 'c66910e7-5f96-41d2-a627-0f634c8fbaa2', 'viewed all new delete request', '234234234', '2024-07-04 09:40:44', NULL, 0),
(81, 'eaf33743-f64d-407b-8d2f-eed441753cd6', 'viewed all new delete request', '234234234', '2024-07-04 09:40:45', NULL, 0),
(82, '7e872cd9-67a2-4dd6-bc6e-916b69292a68', 'viewed all new delete request', '234234234', '2024-07-04 09:41:55', NULL, 0),
(83, '9bdd7f5a-1b14-4b3b-ad32-74eada56947a', 'viewed all new delete request', '234234234', '2024-07-04 09:42:04', NULL, 0),
(84, '5cfef9e8-11a9-40e6-b883-5cad0bdcf4ed', 'viewed all new delete request', '234234234', '2024-07-04 09:42:04', NULL, 0),
(85, 'e8f46f95-5f6d-4cfd-8b89-d581af99d919', 'delete request for trade id: \'a017da3b-debe-4724-bcda-99484919a2d2\'', '234234234', '2024-07-04 09:43:01', NULL, 0),
(86, '6a544281-4202-4d70-aca6-f192a8ca4576', 'viewed all new delete request', '234234234', '2024-07-04 09:43:05', NULL, 0),
(87, '7befc021-cfe2-49e5-b70a-5b191c69e8d7', 'viewed all new delete request', '234234234', '2024-07-04 09:43:09', NULL, 0),
(88, '2fe65919-9791-45fc-87a6-f8b65bd8f88c', 'viewed all new delete request', '234234234', '2024-07-04 09:43:09', NULL, 0),
(89, '38d14550-49fa-4993-b968-30d7a5e92629', 'viewed all new delete request', '234234234', '2024-07-04 09:44:33', NULL, 0),
(90, '8d8c7089-d304-4990-b603-98d41e41638d', 'delete request for trade id: \'a017da3b-debe-4724-bcda-99484919a2d2\'', '234234234', '2024-07-04 09:44:50', NULL, 0),
(91, '07d1eb63-cff2-4f1f-aea3-92ac47c1898a', 'viewed all new delete request', '234234234', '2024-07-04 09:44:52', NULL, 0),
(92, '0c263f41-08ef-4411-8260-186307e52988', 'delete request for trade id: \'a017da3b-debe-4724-bcda-99484919a2d2\'', '234234234', '2024-07-04 09:45:56', NULL, 0),
(93, '42532f28-87e7-4a88-8546-5944c881a7fc', 'viewed all new delete request', '234234234', '2024-07-04 09:46:00', NULL, 0),
(94, 'c515619c-228d-410e-b057-09ce661fff60', 'viewed all new delete request', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 09:50:46', NULL, 0),
(95, 'ff126f6e-e020-4d9c-a265-a28658be8dc3', 'delete request for trade id: \'72de0129-4f1b-4832-9145-8bc849d6b300\'', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 09:51:11', NULL, 0),
(96, '4c85b642-d7a1-4c5c-baa5-e7febed7b075', 'viewed all new delete request', '234234234', '2024-07-04 09:53:39', NULL, 0),
(97, '1e949a00-d760-44b6-8e66-188569ee4640', 'viewed all new delete request', '234234234', '2024-07-04 09:54:16', NULL, 0),
(98, 'b70387b9-39e2-43a5-b3ec-ec12b020f091', 'deleted sale from sale requests', '234234234', '2024-07-04 09:54:16', NULL, 0),
(99, '14d33f6b-b474-4d1e-8ff8-3658e6499c58', 'viewed all new delete request', '234234234', '2024-07-04 09:54:16', NULL, 0),
(100, 'e9c6890c-5afd-498d-b815-3babe14591f5', 'viewed all new delete request', '234234234', '2024-07-04 09:55:27', NULL, 0),
(101, '3f6a63c2-e1aa-42de-bde4-b1daa3195955', 'viewed all new delete request', '234234234', '2024-07-04 09:56:06', NULL, 0),
(102, '2baadeb1-7c0b-466e-a107-28b2be0c7fc0', 'viewed all new delete request', '234234234', '2024-07-04 09:56:48', NULL, 0),
(103, 'd4011521-8fd0-42b7-b878-ce86f83cc13e', 'viewed all new delete request', '234234234', '2024-07-04 09:57:11', NULL, 0),
(104, '354bb47f-e4b6-4b0b-a006-28d5e69e96fe', 'viewed all new delete request', '234234234', '2024-07-04 09:57:48', NULL, 0),
(105, 'e1c189ce-5a38-44bf-8e60-df0fc7a6bd0e', 'viewed all new delete request', '234234234', '2024-07-04 09:59:07', NULL, 0),
(106, '74ef29b0-21e2-47a9-a591-a9f67d7a5477', 'viewed all new delete request', '234234234', '2024-07-04 09:59:56', NULL, 0),
(107, '1e2657f7-90e4-4e28-9b6f-0ae0bbc3926c', 'viewed all new delete request', '234234234', '2024-07-04 10:06:28', NULL, 0),
(108, '3ac76e0c-5224-4803-b91b-09fd13ebddce', 'delete request for trade id: \'a017da3b-debe-4724-bcda-99484919a2d2\'', '234234234', '2024-07-04 10:39:38', NULL, 0),
(109, '73803ba3-1470-4314-be4e-6a6cfa591821', 'delete request for trade id: \'4f666b08-1ac1-447a-90b9-8c5bbeb1cfd9\'', '234234234', '2024-07-04 10:39:46', NULL, 0),
(110, '68bf56eb-d952-4af8-85f6-228664bac127', 'delete request for trade id: \'c6418219-b87d-491e-b2e5-ecc47371aad0\'', '234234234', '2024-07-04 10:39:51', NULL, 0),
(111, '1d880543-5c3a-4ef6-b91e-4c3e38075026', 'delete request for trade id: \'2647d3f4-be16-47a0-b24b-c7cce8988cd3\'', '234234234', '2024-07-04 10:39:55', NULL, 0),
(112, 'baa75f41-dde1-4015-ab57-429cb34f5c56', 'delete request for trade id: \'f8447ded-6b34-40cf-bd3e-1dbc5da48454\'', '234234234', '2024-07-04 10:39:59', NULL, 0),
(113, 'e468af84-8e1b-45cb-b492-cd28e2e8587d', 'logged out from system', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 10:59:12', NULL, 0),
(114, 'ef308d87-6d41-4f85-aba1-17161a880ef2', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 10:59:29', NULL, 0),
(115, 'bb480bf5-4722-47fa-bee4-277fec6a6663', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 11:07:14', NULL, 0),
(116, '12075823-83cf-4032-a26a-de07fe6b50e7', 'logged into the system', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 11:07:23', NULL, 0),
(117, '5b721163-ab15-4b09-a0b1-271bb5c8e3b1', 'added new sale with gram of 565 and volume of 6 and total amount of ₵11,270,724.32 and price of ₵75,765.00 on id 11c644f6-5dab-4501-99f6-e205da2a75ce', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 14:32:34', NULL, 0),
(118, 'e4ef3142-c28d-4079-82f9-aa03e6b96997', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 14:39:22', NULL, 0),
(119, '8d72dfc2-663d-40c9-8da0-a8b22f8225f1', 'added new sale with gram of 14.92 and volume of 0.79 and total amount of ₵17,009.03 and price of ₵8,700.00 on id 54c4b503-f584-4686-8fa7-a9f45bf04422', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 14:41:19', NULL, 0),
(120, '4d51e2b5-e8ab-4700-b5e3-41db06bd7197', 'delete request for trade id: \'54c4b503-f584-4686-8fa7-a9f45bf04422\'', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 14:43:12', NULL, 0),
(121, '798e81a9-cf7d-4a26-8758-04a5a5e37091', 'viewed all new delete request', '234234234', '2024-07-04 14:44:54', NULL, 0),
(122, '227b8fe9-9ea5-469e-bb60-55ff289a3514', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 14:45:40', NULL, 0),
(123, 'f4506420-88fe-455d-a083-9f0d2c183413', 'logged out from system', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 14:45:46', NULL, 0),
(124, '35eb81c2-71b9-404b-a677-e22bc29a0f5d', 'deleted profile picture', '234234234', '2024-07-04 21:41:49', NULL, 0),
(125, 'a8383164-a3cb-427b-8ae8-cfd022abb6cf', 'updated profile picture', '234234234', '2024-07-04 21:42:02', NULL, 0),
(126, 'bed9d58c-9304-4b90-b7b7-660524f21194', 'viewed all new delete request', '234234234', '2024-07-04 21:43:40', NULL, 0),
(127, '171012f6-6a05-4f2b-81d5-cf06cb5bbddb', 'viewed all new delete request', '234234234', '2024-07-04 21:43:40', NULL, 0),
(128, '1f6f8a42-7922-4c2d-a62b-d3edaa98b320', 'viewed all new delete request', '234234234', '2024-07-04 21:44:43', NULL, 0),
(129, '7b9cdcb2-2243-463c-81b9-9eb7a2f3f315', 'viewed all new delete request', '234234234', '2024-07-04 21:44:53', NULL, 0),
(130, '17260da2-3daa-4b91-961a-f73405579d6d', 'deleted sale from sale requests', '234234234', '2024-07-04 21:44:53', NULL, 0),
(131, 'e5d3c757-d628-45cc-9083-8a8e54b1c41e', 'viewed all new delete request', '234234234', '2024-07-04 21:44:53', NULL, 0),
(132, 'f65a4e81-24c6-4495-ba6e-affd2503eaff', 'viewed all new delete request', '234234234', '2024-07-04 21:45:59', NULL, 0),
(133, '1b34e8ec-b7bf-4c0e-9b72-cb120077f04b', 'exported PDF trades data', '234234234', '2024-07-04 21:48:24', NULL, 0),
(134, 'f5b2f829-0a77-47f4-b820-c80381cf1824', 'exported XLSX trades data', '234234234', '2024-07-04 21:51:20', NULL, 0),
(135, 'e8ec081e-23a5-4b05-bb0b-64739b3caf13', 'logged out from system', '234234234', '2024-07-04 21:53:19', NULL, 0),
(136, 'b7a0b978-6de5-49d3-b847-cd6fdf80c9b4', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 21:54:20', NULL, 0),
(137, 'e8092145-6e6e-4f0b-a9c3-a72a19ae00a4', 'delete request for trade id: \'0084fe0d-e9f4-4ad4-9dcc-dfac138f3a69\'', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 21:55:40', NULL, 0),
(138, '01ed2b02-ba63-4771-8d7f-dde4e3675c36', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 21:56:35', NULL, 0),
(139, 'c748edb1-75b1-48f7-92ed-14cc2ad6a25a', 'logged into the system', '234234234', '2024-07-04 21:56:47', NULL, 0),
(140, 'be119df5-9a9f-469a-8253-be5c1f003d14', 'viewed all new delete request', '234234234', '2024-07-04 21:57:01', NULL, 0),
(141, '44ad228f-93e1-4815-9b37-fa22e3db6de3', 'viewed all new delete request', '234234234', '2024-07-04 21:57:52', NULL, 0),
(142, 'aea32763-3a99-4232-b2e9-c1dd20dc0580', 'viewed all new delete request', '234234234', '2024-07-04 21:58:10', NULL, 0),
(143, '640e14c4-4cc3-40be-bf76-90403fda4865', 'deleted sale from sale requests', '234234234', '2024-07-04 21:58:10', NULL, 0),
(144, '222fff39-4128-4444-ba98-bf7e11b27591', 'viewed all new delete request', '234234234', '2024-07-04 21:58:10', NULL, 0),
(145, '5a2b205a-c115-4c94-af36-f3048f7aaa62', 'viewed all new delete request', '234234234', '2024-07-04 21:58:23', NULL, 0),
(146, 'e8145e1d-9354-4508-8ebd-cce19c6c1c9d', 'logged out from system', '234234234', '2024-07-05 06:27:52', NULL, 0),
(147, '957e1c37-df93-4283-919a-9cc2167a15b5', 'logged into the system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-05 06:28:04', NULL, 0),
(148, 'c1dd53f3-ea2c-46a5-907a-681d6cf8535a', 'logged out from system', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-05 09:38:21', NULL, 0),
(149, 'c6904532-c290-4aad-a739-b14e04f72f83', 'logged into the system', '234234234', '2024-07-05 09:38:30', NULL, 0),
(150, '1cb826c2-9215-421e-86e8-1c6101e69f44', 'viewed all new delete request', '234234234', '2024-07-05 09:38:39', NULL, 0);

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
(1, '13fafb53-a7b3-49ca-bb15-fdcd19a2cabf', 15.37, 0.87, 17.67, 1.98, 21.41, 8250.00, 3878.62, 'inuwa umar', '0244323212', 'make it swift for me', '234234234', '2024-06-30 11:35:49', '2024-07-04 09:45:10', 0, NULL, 0),
(2, 'b772ff1d-401e-418d-a2cf-1c98f7e372dd', 23.45, 1.10, 21.32, 3.03, 26.79, 8250.00, 3171.44, 'haruna osei', 'haruna@email.com', 'we good.', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 11:58:24', '2024-07-04 09:45:12', 0, NULL, 0),
(3, '8f148e3d-d1c0-4b4c-8273-9d539c58db8f', 23.00, 1.00, 23.00, 2.97, 28.69, 3493.00, 1467.05, 'inusa afiu', 'email@email.com', '', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 13:26:52', '2024-07-04 09:45:14', 0, NULL, 0),
(4, '92e0691c-81c3-475f-8dd8-b5a1ab1b0d68', 15.37, 0.87, 17.66, 1.98, 21.39, 8250.00, 15191.55, 'mama dora', 'mama@email.com', '', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 15:51:00', '2024-07-04 09:45:18', 0, NULL, 0),
(5, 'c6418219-b87d-491e-b2e5-ecc47371aad0', 123.00, 0.98, 125.50, 15.87, 48.41, 4532.00, 151381.94, 'kadr', '04455', '', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 16:37:50', '2024-07-04 14:44:54', 1, NULL, 2),
(6, '0084fe0d-e9f4-4ad4-9dcc-dfac138f3a69', 123.00, 2.00, 61.49, 15.87, 43.81, 1234.00, 37302.46, 'hassan', '022222', 'dc', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 17:00:49', '2024-07-04 21:58:10', 2, NULL, 0),
(7, 'f8447ded-6b34-40cf-bd3e-1dbc5da48454', 123.00, 2.00, 61.49, 15.87, 43.81, 2322.00, 70191.51, 'kadr', '0222222', '', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-06-30 17:50:39', '2024-07-04 14:44:54', 1, NULL, 2),
(8, '2647d3f4-be16-47a0-b24b-c7cce8988cd3', 15.37, 0.87, 17.66, 1.98, 21.39, 8250.00, 15191.55, 'Opoku emmanuel', '0222222222', '', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-01 12:23:28', '2024-07-04 14:44:54', 1, NULL, 2),
(9, '4f666b08-1ac1-447a-90b9-8c5bbeb1cfd9', 45.00, 0.98, 45.91, 5.81, 40.73, 3450.00, 35496.19, 'grace osei', '02444444', 'am all good', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-01 14:28:11', '2024-07-04 14:44:54', 1, NULL, 2),
(10, 'f73617f0-c810-4b2b-a4c2-f973e02c04cb', 78.00, 1.00, 77.99, 10.06, 45.71, 8787.00, 175679.87, 'kjnk', 'jnjknk', 'kj', '234234234', '2024-07-02 19:09:30', '2024-07-04 09:45:31', 0, NULL, 0),
(11, '72de0129-4f1b-4832-9145-8bc849d6b300', 12.00, 0.34, 35.28, 1.55, 37.09, 2300.00, 5748.95, 'Afa', 'email.com', '', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-03 12:21:22', '2024-07-04 09:54:16', 2, NULL, 0),
(12, 'a017da3b-debe-4724-bcda-99484919a2d2', 123.00, 1.00, 122.99, 15.87, 48.31, 4567.00, 152235.92, 'baba ali', '023212342', '', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-03 12:22:15', '2024-07-04 14:44:54', 1, NULL, 2),
(13, '11c644f6-5dab-4501-99f6-e205da2a75ce', 565.00, 6.00, 94.16, 72.89, 46.94, 75765.00, 11270724.32, 'issac', '023333', '', '404d51db-6533-4586-b8d5-17c27c2f0607', '2024-07-04 14:32:34', NULL, 0, NULL, 0),
(14, '54c4b503-f584-4686-8fa7-a9f45bf04422', 14.92, 0.79, 18.88, 1.92, 23.42, 8700.00, 17009.03, 'isiu', 'email@email.com', '', 'e01de4bc-10e7-47cc-b2df-c2e1bdd8997f', '2024-07-04 14:41:19', '2024-07-04 21:44:53', 2, NULL, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `jspence_logs`
--
ALTER TABLE `jspence_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `jspence_sales`
--
ALTER TABLE `jspence_sales`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
