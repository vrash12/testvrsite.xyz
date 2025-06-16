-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 04:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `patientcare`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `name`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin@patientcare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', NULL, '2025-05-09 13:13:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admission_details`
--

CREATE TABLE `admission_details` (
  `admission_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `admission_date` date NOT NULL,
  `admission_type` varchar(50) NOT NULL,
  `admission_source` varchar(50) NOT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `doctor_id` int(10) UNSIGNED DEFAULT NULL,
  `room_number` varchar(20) NOT NULL,
  `bed_number` varchar(20) NOT NULL,
  `admission_notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admission_details`
--

INSERT INTO `admission_details` (`admission_id`, `patient_id`, `admission_date`, `admission_type`, `admission_source`, `department_id`, `doctor_id`, `room_number`, `bed_number`, `admission_notes`, `created_at`, `updated_at`) VALUES
(3, 9, '2025-06-10', 'QWE', 'QWEQWE', 10, 10, 'QWEQWE', 'QWE', 'QWE', '2025-06-10 01:19:00', '2025-06-10 01:19:00'),
(4, 12, '2025-06-03', 'qwe', 'eqwe', 13, 13, 'qwe', 'fsdf', 'qwe', '2025-06-10 01:35:47', '2025-06-10 01:35:47'),
(5, 15, '2025-06-06', 'asdasd', 'eqweqwe', 14, 14, 'qweqwe', 'qwewqe', NULL, '2025-06-15 11:53:22', '2025-06-15 11:53:22'),
(6, 16, '2025-06-06', 'asdasd', 'eqweqwe', 14, 14, 'qweqwe', 'qwewqe', NULL, '2025-06-15 11:56:23', '2025-06-15 11:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `bed_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `bed_number` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing_information`
--

CREATE TABLE `billing_information` (
  `billing_info_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `insurance_provider_id` int(11) DEFAULT NULL,
  `policy_number` varchar(100) DEFAULT NULL,
  `group_number` varchar(100) DEFAULT NULL,
  `billing_contact_name` varchar(100) DEFAULT NULL,
  `billing_contact_phone` varchar(20) DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `billing_city` varchar(100) DEFAULT NULL,
  `billing_state` varchar(100) DEFAULT NULL,
  `billing_zip` varchar(20) DEFAULT NULL,
  `billing_notes` text DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billing_information`
--

INSERT INTO `billing_information` (`billing_info_id`, `patient_id`, `payment_method_id`, `insurance_provider_id`, `policy_number`, `group_number`, `billing_contact_name`, `billing_contact_phone`, `billing_address`, `billing_city`, `billing_state`, `billing_zip`, `billing_notes`, `payment_status`, `created_at`, `updated_at`) VALUES
(2, 9, 1, 27, '2323', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-06-10 01:19:00', '2025-06-10 01:19:00'),
(3, 12, 1, 28, '23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-06-10 01:35:47', '2025-06-10 01:35:47'),
(4, 15, 1, 29, 'sad', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-06-15 11:53:22', '2025-06-15 11:53:22'),
(5, 16, 1, 29, 'sad', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2025-06-15 11:56:23', '2025-06-15 11:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `billing_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `billing_date` date NOT NULL,
  `payment_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`billing_id`, `patient_id`, `billing_date`, `payment_status`) VALUES
(1, 9, '2025-06-10', 'partial'),
(2, 12, '2025-06-10', 'partial'),
(3, 15, '2025-06-15', 'partial'),
(4, 16, '2025-06-15', 'partial');

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `billing_item_id` int(11) NOT NULL,
  `billing_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `billing_date` date NOT NULL,
  `prescription_item_id` int(11) DEFAULT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`billing_item_id`, `billing_id`, `amount`, `billing_date`, `prescription_item_id`, `assignment_id`, `discount_amount`) VALUES
(1, 1, 23.00, '2025-06-10', NULL, NULL, 0.00),
(2, 2, 2333.00, '2025-06-10', NULL, NULL, 0.00),
(3, 3, 2323.00, '2025-06-15', NULL, NULL, 0.00),
(4, 4, 2323.00, '2025-06-15', NULL, NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `description`) VALUES
(1, 'Internal Medicine', 'Department for internal medicine and related conditions'),
(2, 'Surgery', 'Department for surgical procedures'),
(3, 'Pediatrics', 'Department for child healthcare'),
(4, 'Obstetrics', 'Department for pregnancy and childbirth care'),
(5, 'Cardiology', 'Department for heart-related conditions'),
(10, 'QWEQW', NULL),
(13, 'qwe', NULL),
(14, 'qweqwe', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `disputes`
--

CREATE TABLE `disputes` (
  `dispute_id` int(11) NOT NULL,
  `billing_item_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `approved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `doctor_name` varchar(100) NOT NULL,
  `doctor_specialization` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `doctor_name`, `doctor_specialization`, `created_at`, `updated_at`) VALUES
(1, 'Dr. Smith', 'Internal Medicine', '2025-05-12 06:40:15', '2025-05-12 06:40:15'),
(2, 'Dr. Jones', 'Surgery', '2025-05-12 06:40:15', '2025-05-12 06:40:15'),
(3, 'Dr. Wilson', 'Pediatrics', '2025-05-12 06:40:15', '2025-05-12 06:40:15'),
(4, 'Dr. Brown', 'Obstetrics', '2025-05-12 06:40:15', '2025-05-12 06:40:15'),
(5, 'Dr. Davis', 'Cardiology', '2025-05-12 06:40:15', '2025-05-12 06:40:15'),
(10, 'EQWEQWEWQ', NULL, '2025-06-10 01:19:00', '2025-06-10 01:19:00'),
(13, 'qwe', NULL, '2025-06-10 01:35:47', '2025-06-10 01:35:47'),
(14, 'qweqwe', NULL, '2025-06-15 11:53:22', '2025-06-15 11:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_services`
--

CREATE TABLE `hospital_services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_services`
--

INSERT INTO `hospital_services` (`service_id`, `service_name`, `department_id`, `price`, `description`) VALUES
(1, 'gh', 1, 32.00, 'qeqw');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_providers`
--

CREATE TABLE `insurance_providers` (
  `insurance_provider_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insurance_providers`
--

INSERT INTO `insurance_providers` (`insurance_provider_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Blue Cross', '2025-05-12 06:28:33', '2025-06-10 01:17:14'),
(2, 'Medicare', '2025-05-12 06:28:33', '2025-06-10 01:17:14'),
(3, 'Medicaid', '2025-05-12 06:28:33', '2025-06-10 01:17:14'),
(4, 'Aetna', '2025-05-12 06:28:33', '2025-06-10 01:17:14'),
(5, 'Cigna', '2025-05-12 06:28:33', '2025-06-10 01:17:14'),
(6, 'Blue Cross', '2025-05-12 06:37:55', '2025-06-10 01:17:14'),
(7, 'Medicare', '2025-05-12 06:37:55', '2025-06-10 01:17:14'),
(8, 'Medicaid', '2025-05-12 06:37:55', '2025-06-10 01:17:14'),
(9, 'Aetna', '2025-05-12 06:37:55', '2025-06-10 01:17:14'),
(10, 'Cigna', '2025-05-12 06:37:55', '2025-06-10 01:17:14'),
(11, 'Blue Cross', '2025-05-12 06:38:19', '2025-06-10 01:17:14'),
(12, 'Medicare', '2025-05-12 06:38:19', '2025-06-10 01:17:14'),
(13, 'Medicaid', '2025-05-12 06:38:19', '2025-06-10 01:17:14'),
(14, 'Aetna', '2025-05-12 06:38:19', '2025-06-10 01:17:14'),
(15, 'Cigna', '2025-05-12 06:38:19', '2025-06-10 01:17:14'),
(16, 'Blue Cross', '2025-05-12 06:38:44', '2025-06-10 01:17:14'),
(17, 'Medicare', '2025-05-12 06:38:44', '2025-06-10 01:17:14'),
(18, 'Medicaid', '2025-05-12 06:38:44', '2025-06-10 01:17:14'),
(19, 'Aetna', '2025-05-12 06:38:44', '2025-06-10 01:17:14'),
(20, 'Cigna', '2025-05-12 06:38:44', '2025-06-10 01:17:14'),
(21, 'Blue Cross', '2025-05-12 06:40:15', '2025-06-10 01:17:14'),
(22, 'Medicare', '2025-05-12 06:40:15', '2025-06-10 01:17:14'),
(23, 'Medicaid', '2025-05-12 06:40:15', '2025-06-10 01:17:14'),
(24, 'Aetna', '2025-05-12 06:40:15', '2025-06-10 01:17:14'),
(25, 'Cigna', '2025-05-12 06:40:15', '2025-06-10 01:17:14'),
(27, 'DSD', '2025-06-10 01:19:00', '2025-06-09 17:19:00'),
(28, 'eqwe', '2025-06-10 01:35:47', '2025-06-09 17:35:47'),
(29, 'asdasd', '2025-06-15 11:53:22', '2025-06-15 03:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `medical_details`
--

CREATE TABLE `medical_details` (
  `medical_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `primary_reason` text NOT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `blood_pressure` varchar(20) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `heart_rate` int(11) DEFAULT NULL,
  `medical_history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`medical_history`)),
  `allergies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`allergies`)),
  `other_medical_history` text DEFAULT NULL,
  `other_allergies` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_details`
--

INSERT INTO `medical_details` (`medical_id`, `patient_id`, `primary_reason`, `temperature`, `blood_pressure`, `weight`, `height`, `heart_rate`, `medical_history`, `allergies`, `other_medical_history`, `other_allergies`, `created_at`, `updated_at`) VALUES
(5, 9, 'QWEQWE', 2.0, NULL, 23.00, 23.00, NULL, '\"{\\\"hypertension\\\":false,\\\"heart_disease\\\":false,\\\"copd\\\":false,\\\"diabetes\\\":false,\\\"asthma\\\":false,\\\"kidney_disease\\\":false,\\\"others\\\":null}\"', '\"{\\\"penicillin\\\":false,\\\"nsaids\\\":false,\\\"contrast_dye\\\":false,\\\"sulfa\\\":false,\\\"latex\\\":false,\\\"none\\\":false,\\\"others\\\":null}\"', NULL, NULL, '2025-06-10 01:19:00', '2025-06-10 01:19:00'),
(8, 12, 'eqwe', NULL, NULL, NULL, NULL, NULL, '\"{\\\"hypertension\\\":false,\\\"heart_disease\\\":false,\\\"copd\\\":false,\\\"diabetes\\\":false,\\\"asthma\\\":false,\\\"kidney_disease\\\":false,\\\"others\\\":null}\"', '\"{\\\"penicillin\\\":false,\\\"nsaids\\\":false,\\\"contrast_dye\\\":false,\\\"sulfa\\\":false,\\\"latex\\\":false,\\\"none\\\":false,\\\"others\\\":null}\"', NULL, NULL, '2025-06-10 01:35:47', '2025-06-10 01:35:47'),
(9, 15, 'eqwe', 23.0, NULL, 123.00, 123.00, NULL, '\"{\\\"hypertension\\\":false,\\\"heart_disease\\\":false,\\\"copd\\\":false,\\\"diabetes\\\":true,\\\"asthma\\\":true,\\\"kidney_disease\\\":false,\\\"others\\\":null}\"', '\"{\\\"penicillin\\\":false,\\\"nsaids\\\":false,\\\"contrast_dye\\\":false,\\\"sulfa\\\":false,\\\"latex\\\":false,\\\"none\\\":false,\\\"others\\\":null}\"', NULL, NULL, '2025-06-15 11:53:22', '2025-06-15 11:53:22'),
(10, 16, 'eqwe', 23.0, NULL, 123.00, 123.00, NULL, '\"{\\\"hypertension\\\":false,\\\"heart_disease\\\":false,\\\"copd\\\":false,\\\"diabetes\\\":true,\\\"asthma\\\":true,\\\"kidney_disease\\\":false,\\\"others\\\":null}\"', '\"{\\\"penicillin\\\":false,\\\"nsaids\\\":false,\\\"contrast_dye\\\":false,\\\"sulfa\\\":false,\\\"latex\\\":false,\\\"none\\\":false,\\\"others\\\":null}\"', NULL, NULL, '2025-06-15 11:56:23', '2025-06-15 11:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_stock_movements`
--

CREATE TABLE `medicine_stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` int(11) NOT NULL,
  `delta` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_stock_movements`
--

INSERT INTO `medicine_stock_movements` (`id`, `service_id`, `delta`, `type`, `notes`, `created_at`) VALUES
(1, 1, 2, 'initial', 'Initial stock on creation', '2025-06-15 20:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `patient_first_name` varchar(100) NOT NULL,
  `patient_last_name` varchar(100) NOT NULL,
  `patient_birthday` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `civil_status` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `patient_first_name`, `patient_last_name`, `patient_birthday`, `email`, `phone_number`, `profile_picture`, `status`, `created_at`, `updated_at`, `civil_status`, `address`, `birthday`, `password`) VALUES
(2, 'John', 'Doe', '1990-01-01', 'john.doe@example.com', '09123456789', NULL, 'active', NULL, NULL, NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(9, 'SAM', 'SAM', '2025-06-15', 'ss.001@patientcare.com', '123123', NULL, 'active', '2025-06-09 17:19:00', '2025-06-09 17:19:00', 'QWEWQE', 'SAM', NULL, '$2y$10$197vRLMvMo0aC/2ljXHXjelIlAIJiUoLQXFOL2JDt7dAyULN5f6fi'),
(12, 'SAM', 'SAM', '2025-06-15', 'ss.002@patientcare.com', '123123', NULL, 'active', '2025-06-09 17:35:47', '2025-06-09 17:35:47', 'QWEWQE', 'dasd', NULL, '$2y$10$frPWwhZU/s58BIZVX/g8zuS7UcHcf8G6zjwhxdTUd5vUhsewvUOvG'),
(15, 'van', 'qweqwe', '2025-06-18', 'vq.001@patientcare.com', NULL, NULL, 'active', '2025-06-15 03:53:22', '2025-06-15 03:53:22', NULL, NULL, NULL, '$2y$10$wpJ3eF92aumDe.Rhil1tBeIHIyg4CWluWsCCR27vTW7jt0Zuz3yZW'),
(16, 'van', 'qweqwe', '2025-06-18', 'vq.002@patientcare.com', NULL, NULL, 'active', '2025-06-15 03:56:23', '2025-06-15 03:56:23', NULL, NULL, NULL, '$2y$10$S./eB9fvXvX2uRv4URyHb.9e.kJP7EDsQLVjJLLQdikeLwxG.6M8O');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `payment_method_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`payment_method_id`, `name`, `created_at`) VALUES
(1, 'Cash', '2025-05-12 06:28:33'),
(2, 'Insurance', '2025-05-12 06:28:33'),
(3, 'Credit Card', '2025-05-12 06:28:33'),
(4, 'Debit Card', '2025-05-12 06:28:33'),
(5, 'Bank Transfer', '2025-05-12 06:28:33'),
(6, 'Cash', '2025-05-12 06:37:44'),
(7, 'Insurance', '2025-05-12 06:37:44'),
(8, 'Credit Card', '2025-05-12 06:37:44'),
(9, 'Debit Card', '2025-05-12 06:37:44'),
(10, 'Bank Transfer', '2025-05-12 06:37:44'),
(11, 'Cash', '2025-05-12 06:37:55'),
(12, 'Insurance', '2025-05-12 06:37:55'),
(13, 'Credit Card', '2025-05-12 06:37:55'),
(14, 'Debit Card', '2025-05-12 06:37:55'),
(15, 'Bank Transfer', '2025-05-12 06:37:55'),
(16, 'Cash', '2025-05-12 06:38:19'),
(17, 'Insurance', '2025-05-12 06:38:19'),
(18, 'Credit Card', '2025-05-12 06:38:19'),
(19, 'Debit Card', '2025-05-12 06:38:19'),
(20, 'Bank Transfer', '2025-05-12 06:38:19'),
(21, 'Cash', '2025-05-12 06:38:44'),
(22, 'Insurance', '2025-05-12 06:38:44'),
(23, 'Credit Card', '2025-05-12 06:38:44'),
(24, 'Debit Card', '2025-05-12 06:38:44'),
(25, 'Bank Transfer', '2025-05-12 06:38:44'),
(26, 'Cash', '2025-05-12 06:40:15'),
(27, 'Insurance', '2025-05-12 06:40:15'),
(28, 'Credit Card', '2025-05-12 06:40:15'),
(29, 'Debit Card', '2025-05-12 06:40:15'),
(30, 'Bank Transfer', '2025-05-12 06:40:15');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_charges`
--

CREATE TABLE `pharmacy_charges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` int(11) NOT NULL,
  `prescribing_doctor` varchar(255) NOT NULL,
  `rx_number` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_charge_items`
--

CREATE TABLE `pharmacy_charge_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `charge_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `prescription_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prescription_items`
--

CREATE TABLE `prescription_items` (
  `prescription_item_id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity_given` int(11) NOT NULL,
  `quantity_asked` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_assignments`
--

CREATE TABLE `service_assignments` (
  `assignment_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `service_status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin_user', 'admin@patientcare.local', '$2y$10$cyyAUGK6EmXuY50SusjWmeU0i6kR333Jz4mQO0kATk4WtZafrilIe', 'admin'),
(2, 'patient_user', 'patient1@patientcare.local', '$2y$10$xVU8Du8Ndbb64Xfg0VBYeetMB/XL3i5bmiOCdBygJfXdjFY81Nz16', 'patient'),
(3, 'doctor_user', 'doctor1@patientcare.local', '$2y$10$YLyfJ7nH5vbmMluduR8dquIhNTer6rkR6z2ajts7VUFONRgdAO4fa', 'doctor'),
(4, 'admission_user', 'admit1@patientcare.local', '$2y$10$ZKINb3HL8RW4oMMjJlHal.dl1uU0o0LBjRRPS9hCvPa.cU6DvwcoG', 'admission'),
(5, 'billing_user', 'billing1@patientcare.local', '$2y$10$lJxG8rPZmx/PQ6A7ZNX20eO1sLWcfSc8R7S2QYwJhFDTG0zMmJo1.', 'billing'),
(6, 'hospital_user', 'hospital1@patientcare.local', '$2y$10$jhpFPtZ220HTsGhcXelaVOMEJOVSzf.jMFkyfY1eHnSTHSt7AhRW6', 'hospital_services'),
(7, 'pharmacy_user', 'pharmacy1@patientcare.local', '$2y$10$6JwpEFomEFfGGZORdUnqp.RahfWwbXg4/SWd4FR/b5SiQPJj1ex92', 'pharmacy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admission_details`
--
ALTER TABLE `admission_details`
  ADD PRIMARY KEY (`admission_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`bed_id`),
  ADD KEY `beds_room_id_foreign` (`room_id`);

--
-- Indexes for table `billing_information`
--
ALTER TABLE `billing_information`
  ADD PRIMARY KEY (`billing_info_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `insurance_provider_id` (`insurance_provider_id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`billing_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`billing_item_id`),
  ADD KEY `billing_id` (`billing_id`),
  ADD KEY `prescription_item_id` (`prescription_item_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `disputes`
--
ALTER TABLE `disputes`
  ADD PRIMARY KEY (`dispute_id`),
  ADD KEY `billing_item_id` (`billing_item_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `hospital_services`
--
ALTER TABLE `hospital_services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `insurance_providers`
--
ALTER TABLE `insurance_providers`
  ADD PRIMARY KEY (`insurance_provider_id`);

--
-- Indexes for table `medical_details`
--
ALTER TABLE `medical_details`
  ADD PRIMARY KEY (`medical_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `medicine_stock_movements`
--
ALTER TABLE `medicine_stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_msm_service` (`service_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `patients_email_unique` (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`payment_method_id`);

--
-- Indexes for table `pharmacy_charges`
--
ALTER TABLE `pharmacy_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pharmacy_charges_patient` (`patient_id`);

--
-- Indexes for table `pharmacy_charge_items`
--
ALTER TABLE `pharmacy_charge_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pharmacy_charge_items_charge` (`charge_id`),
  ADD KEY `fk_pharmacy_charge_items_service` (`service_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD PRIMARY KEY (`prescription_item_id`),
  ADD KEY `prescription_id` (`prescription_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `rooms_department_id_foreign` (`department_id`);

--
-- Indexes for table `service_assignments`
--
ALTER TABLE `service_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admission_details`
--
ALTER TABLE `admission_details`
  MODIFY `admission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `bed_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billing_information`
--
ALTER TABLE `billing_information`
  MODIFY `billing_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `billing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `billing_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `disputes`
--
ALTER TABLE `disputes`
  MODIFY `dispute_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hospital_services`
--
ALTER TABLE `hospital_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `insurance_providers`
--
ALTER TABLE `insurance_providers`
  MODIFY `insurance_provider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `medical_details`
--
ALTER TABLE `medical_details`
  MODIFY `medical_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `medicine_stock_movements`
--
ALTER TABLE `medicine_stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pharmacy_charges`
--
ALTER TABLE `pharmacy_charges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pharmacy_charge_items`
--
ALTER TABLE `pharmacy_charge_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescription_items`
--
ALTER TABLE `prescription_items`
  MODIFY `prescription_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_assignments`
--
ALTER TABLE `service_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admission_details`
--
ALTER TABLE `admission_details`
  ADD CONSTRAINT `admission_details_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `beds_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `billing_information`
--
ALTER TABLE `billing_information`
  ADD CONSTRAINT `billing_information_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `billing_information_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`payment_method_id`),
  ADD CONSTRAINT `billing_information_ibfk_3` FOREIGN KEY (`insurance_provider_id`) REFERENCES `insurance_providers` (`insurance_provider_id`);

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD CONSTRAINT `bill_items_ibfk_1` FOREIGN KEY (`billing_id`) REFERENCES `bills` (`billing_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_items_ibfk_2` FOREIGN KEY (`prescription_item_id`) REFERENCES `prescription_items` (`prescription_item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bill_items_ibfk_3` FOREIGN KEY (`assignment_id`) REFERENCES `service_assignments` (`assignment_id`) ON DELETE CASCADE;

--
-- Constraints for table `disputes`
--
ALTER TABLE `disputes`
  ADD CONSTRAINT `disputes_ibfk_1` FOREIGN KEY (`billing_item_id`) REFERENCES `bill_items` (`billing_item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disputes_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `hospital_services`
--
ALTER TABLE `hospital_services`
  ADD CONSTRAINT `hospital_services_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_details`
--
ALTER TABLE `medical_details`
  ADD CONSTRAINT `medical_details_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `medicine_stock_movements`
--
ALTER TABLE `medicine_stock_movements`
  ADD CONSTRAINT `fk_msm_service` FOREIGN KEY (`service_id`) REFERENCES `hospital_services` (`service_id`) ON DELETE CASCADE;

--
-- Constraints for table `pharmacy_charges`
--
ALTER TABLE `pharmacy_charges`
  ADD CONSTRAINT `fk_pharmacy_charges_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `pharmacy_charge_items`
--
ALTER TABLE `pharmacy_charge_items`
  ADD CONSTRAINT `fk_pharmacy_charge_items_charge` FOREIGN KEY (`charge_id`) REFERENCES `pharmacy_charges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pharmacy_charge_items_service` FOREIGN KEY (`service_id`) REFERENCES `hospital_services` (`service_id`) ON DELETE NO ACTION;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_items`
--
ALTER TABLE `prescription_items`
  ADD CONSTRAINT `prescription_items_ibfk_1` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`prescription_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_items_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `hospital_services` (`service_id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE;

--
-- Constraints for table `service_assignments`
--
ALTER TABLE `service_assignments`
  ADD CONSTRAINT `service_assignments_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `hospital_services` (`service_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_assignments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_assignments_ibfk_3` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
