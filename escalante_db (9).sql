-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2024 at 03:27 PM
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
-- Database: `escalante_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `punong_barangay` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`id`, `name`, `punong_barangay`, `contact_number`, `created_at`, `updated_at`) VALUES
(1, 'ALIMANGO', 'HON. LEO ALEJANDRO U. YAP', '09171234567', NULL, NULL),
(2, 'BALINTAWAK', 'HON. MARLYN D. SALILI', '09171234568', NULL, NULL),
(3, 'BINAGUIOHAN', 'HON. JOCELYN N. BALICUATRO', '09171234569', NULL, NULL),
(4, 'BUENAVISTA', 'HON. FEDERICO M. TANGUAN JR.', '09171234569', NULL, NULL),
(5, 'CERVANTES', 'HON. NICOLAS V. BERNAJE JR.', '09171234569', NULL, NULL),
(6, 'DIAN-AY', 'HON. JUANITO I. GREGORIO JR.', '09171234569', NULL, NULL),
(7, 'HACIENDA FE', 'HON. AIDA P. FRANCISCO', '09171234569', NULL, NULL),
(8, 'JAPITAN', 'HON. CARLITO L. VILLARIN', '09171234569', NULL, NULL),
(9, 'JONOB-JONOB', 'HON. TACIANA Y. SARATOBIAS', '09171234569', NULL, NULL),
(10, 'LANGUB', 'HON. LADISLAO G. PONTEVEDRA JR.', '09171234569', NULL, NULL),
(11, 'LIBERTAD', 'HON. NEPTALI P. NARVASA', '09171234569', NULL, NULL),
(12, 'MABINI', 'HON. JOHN PAUL D. ESCALA', '09171234569', NULL, NULL),
(13, 'MAGSAYSAY', 'HON. ROBERTO G. GRIPO SR.', '09171234569', NULL, NULL),
(14, 'MALASIBOG', 'HON ROMULO P. AMACAN', '09171234569', NULL, NULL),
(15, 'OLD POBLACION', 'HON. JOAN M. GUINANAO', '09171234569', NULL, NULL),
(16, 'PAITAN', 'HON. RAMILO C. VIAJEDOR', '09171234569', NULL, NULL),
(17, 'PINAPUGASAN', 'HON. EDGAR D. RAPANA', '09171234569', NULL, NULL),
(18, 'RIZAL', 'HON. IVY A. CANTERE', '09171234569', NULL, NULL),
(19, 'TAMLANG', 'HON. ANALYN E. ALBERIO', '09171234569', NULL, NULL),
(20, 'UDTONGAN', 'HON. RASEL B. CASES', '09171234569', NULL, NULL),
(21, 'WASHINGTON', 'HON. RENE L. TIGUELO', '09171234569', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('dionisiominano@gmail.com|127.0.0.1', 'i:2;', 1727673007),
('dionisiominano@gmail.com|127.0.0.1:timer', 'i:1727673007;', 1727673007);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disasters`
--

CREATE TABLE `disasters` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `rescue_team` varchar(255) NOT NULL,
  `place_of_incident` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `type` varchar(55) NOT NULL,
  `affected_infrastructure` text DEFAULT NULL,
  `casualties` text DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `current_water_level` varchar(50) DEFAULT NULL,
  `water_level_trend` enum('Rising','Falling','Stable') DEFAULT NULL,
  `intensity_level` decimal(10,2) DEFAULT NULL,
  `aftershocks` int(11) DEFAULT NULL,
  `typhoon_signal` varchar(55) DEFAULT NULL,
  `eruption_type` varchar(255) DEFAULT NULL,
  `eruption_intensity` varchar(50) DEFAULT NULL,
  `involved_parties` text DEFAULT NULL,
  `triggering_event` varchar(255) DEFAULT NULL,
  `nature_of_encounter` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disasters`
--

INSERT INTO `disasters` (`id`, `date`, `rescue_team`, `place_of_incident`, `city`, `barangay`, `type`, `affected_infrastructure`, `casualties`, `is_approved`, `current_water_level`, `water_level_trend`, `intensity_level`, `aftershocks`, `typhoon_signal`, `eruption_type`, `eruption_intensity`, `involved_parties`, `triggering_event`, `nature_of_encounter`, `duration`, `created_at`, `updated_at`) VALUES
(2, '2024-12-10', 'Delta', 'prk. puto', 'ESCALANTE CITY', 'HACIENDA FE', 'Earthquake', 'School: Impassable, House: Damaged', 'Killed: 1, Injured: 1', 1, 'Knee Deep', 'Rising', 7.00, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-10 21:12:40', '2024-12-16 04:21:10'),
(3, '2024-12-10', 'Alpha', 'prk. puto', 'ESCALANTE CITY', 'Old Poblacion', 'Flood', 'Roads: Flooded', 'Killed: 1', 1, 'Knee Deep', 'Rising', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-10 22:26:24', '2024-12-10 22:26:24'),
(4, '2024-11-30', 'Bravo', 'prk. puto', 'ESCALANTE CITY', 'Old Poblacion', 'Flood', 'Roads: Flooded', 'Injured: 1', 1, 'Knee Deep', 'Rising', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-10 22:30:58', '2024-12-10 22:30:58'),
(5, '2024-11-20', 'Bravo', 'prk. puto', 'ESCALANTE CITY', 'Old Poblacion', 'Flood', 'Roads: Flooded', 'Injured: 1', 1, 'Roof Top', 'Rising', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-10 22:31:45', '2024-12-11 06:33:09'),
(6, '2024-12-04', 'Alpha', 'prk. puto', 'ESCALANTE CITY', 'Old Poblacion', 'Flood', 'Roads: Flooded', 'Missing: 1', 1, 'Knee Deep', 'Rising', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-10 22:55:50', '2024-12-10 22:55:50'),
(7, '2024-12-12', 'Charlie', 'prk. puto', 'ESCALANTE CITY', 'Old Poblacion', 'Volcanic Eruption', 'Roads: Damaged', 'Missing: 1', 1, NULL, NULL, NULL, NULL, '', 'Phreatic', 'Weak', NULL, NULL, NULL, NULL, '2024-12-10 23:00:02', '2024-12-10 23:00:02'),
(8, '2024-12-11', 'Delta', 'prk. puto', 'ESCALANTE CITY', 'Old Poblacion', 'Rebel Encounter', 'School: Damaged', 'Police injured: 1, Rebel killed: 1', 1, NULL, NULL, NULL, NULL, '', NULL, NULL, 'saf and maote', 'Checkpoint', 'Firefight', '30 mins', '2024-12-10 23:01:07', '2024-12-10 23:01:07'),
(10, '2024-12-10', 'Bravo', 'prk. puto', 'ESCALANTE CITY', 'Rizal', 'Flood', 'Roads: Flooded', 'Killed: 1', 1, 'Knee Deep', 'Falling', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-10 23:51:02', '2024-12-10 23:51:02'),
(11, '2024-12-11', 'Bravo', 'prk. puto', 'ESCALANTE CITY', 'Tamlang', 'Flood', 'Roads: Flooded', 'Injured: 1', 1, 'Waist Deep', 'Rising', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-10 23:51:41', '2024-12-10 23:51:41'),
(13, '2024-12-11', 'Bravo', 'prk. puto', 'ESCALANTE CITY', 'Tamlang', 'Flood', 'Roads: Flooded', 'Injured: 1', 1, 'Knee Deep', 'Rising', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-12 00:16:16', '2024-12-12 00:16:16'),
(15, '2024-12-11', 'Charlie', 'prk. puto', 'ESCALANTE CITY', 'Buenavista', 'Flood', 'Roads: Flooded', 'Missing: 1', 1, 'Knee Deep', 'Rising', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-12 00:23:46', '2024-12-12 00:23:46'),
(17, '2024-12-11', 'Charlie', 'prk. puto', 'ESCALANTE CITY', 'Dian-ay', 'Flood', 'Roads: Flooded', 'Missing: 1', 1, 'Knee Deep', 'Rising', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-12 00:46:10', '2024-12-12 00:46:10'),
(18, '2024-12-13', 'Alpha', 'prk. puto', 'ESCALANTE CITY', 'Pinapugasan', 'Flood', 'Roads: Flooded', 'Killed: 1', 1, 'Chest Deep', 'Rising', NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-12 12:52:40', '2024-12-12 12:52:40');

-- --------------------------------------------------------

--
-- Table structure for table `disaster_type`
--

CREATE TABLE `disaster_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disaster_type`
--

INSERT INTO `disaster_type` (`id`, `type_name`) VALUES
(3, 'Earthquake'),
(1, 'Flood'),
(5, 'Rebel Encounter'),
(2, 'Typhoon'),
(4, 'Volcanic Eruption');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_cases`
--

CREATE TABLE `medical_cases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `rescue_team` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `place_of_incident` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `no_of_patients` int(11) NOT NULL,
  `chief_complaints` text DEFAULT NULL,
  `facility_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_cases`
--

INSERT INTO `medical_cases` (`id`, `date`, `rescue_team`, `city`, `place_of_incident`, `barangay`, `no_of_patients`, `chief_complaints`, `facility_name`, `created_at`, `updated_at`, `is_approved`) VALUES
(2, '2024-04-06', 'Alpha', 'ESCALANTE CITY', 'prk. makiangayon', 'Alimango', 1, 'body weaknesses', 'VGDH', '2024-09-21 21:57:45', '2024-12-16 04:55:21', 1),
(8, '2024-04-06', 'Charlie', '', 'public plaza', 'Balintawak', 1, 'dizziness and fainting', 'VGDH', '2024-09-22 00:10:58', '2024-10-02 17:37:42', 1),
(15, '2024-04-16', 'Charlie', '', 'PBB', 'Balintawak', 1, 'difficulty of breathing', 'VGDH', '2024-09-22 00:53:22', '2024-10-02 17:39:20', 1),
(16, '2024-04-16', 'Bravo', '', 'so. benit-agan', 'Buenavista', 1, 'body weaknesses (history of stroke)', 'VGDH', '2024-09-22 00:54:44', '2024-10-02 17:41:45', 1),
(17, '2024-04-16', 'Charlie', '', 'back of old city hall', 'Balintawak', 1, 'dizziness and hypertension', 'VGDH', '2024-09-22 04:14:48', '2024-10-02 17:46:52', 1),
(19, '2024-04-23', 'Charlie', '', 'so. bangkiling', 'Old Poblacion', 1, 'body weaknesses', 'VGDH', '2024-09-24 00:49:36', '2024-10-02 17:50:29', 1),
(26, '2022-01-31', 'Alpha', '', 'prk. puto', 'Alimango', 1, 'qqqqqq', 'LODIFHI', '2024-11-17 19:13:14', '2024-11-17 19:13:14', 1),
(27, '2024-01-01', 'Bravo', '', '1', 'Balintawak', 1, 'aaa', 'LODIFHI', '2024-11-17 19:25:56', '2024-11-17 19:25:56', 1),
(28, '2022-01-01', 'Bravo', '', 'prk. puto', 'Binaguiohan', 1, 'qq', 'LODIFHI', '2024-11-17 19:59:28', '2024-11-17 19:59:28', 1),
(29, '2023-01-01', 'Alpha', '', 'prk. puto', 'Binaguiohan', 1, 'sss', 'LODIFHI', '2024-11-17 19:59:46', '2024-11-17 19:59:46', 1),
(30, '2023-01-31', 'Bravo', '', 'prk. puto', 'Binaguiohan', 1, 'lll', 'LODIFHI', '2024-11-17 20:00:15', '2024-11-17 20:00:15', 1),
(31, '2024-01-01', 'Bravo', '', 'prk. puto', 'Balintawak', 1, 'ggggg', 'LODIFHI', '2024-11-17 20:00:55', '2024-11-17 20:00:55', 1),
(32, '2024-01-01', 'Charlie', '', 'prk. puto', 'Balintawak', 1, 'iiiiiiiii', 'LODIFHI', '2024-11-17 20:01:13', '2024-11-17 20:01:13', 1),
(33, '2019-01-31', 'Alpha', '', 'prk. puto', 'Buenavista', 1, 'q', 'LODIFHI', '2024-11-18 00:27:00', '2024-11-18 00:27:00', 1),
(34, '2024-12-11', 'Alpha', 'ESCALANTE CITY', 'prk. puto', 'Tamlang', 1, 'sakit tyan', 'LODIFHI', '2024-12-10 21:06:32', '2024-12-10 21:06:32', 1),
(35, '2024-11-13', 'Bravo', 'ESCALANTE CITY', 'prk. puto', 'Balintawak', 1, 'ss', 'LODIFHI', '2024-12-10 22:48:14', '2024-12-10 22:48:14', 1),
(38, '2024-12-15', 'Alpha', 'ESCALANTE CITY', 'prk. puto', 'Rizal', 1, 'hhhhhhhhhh', 'LODIFHI', '2024-12-14 19:39:43', '2024-12-14 19:47:13', 1),
(40, '2024-12-15', 'Delta', 'ESCALANTE CITY', 'prk. puto', 'Magsaysay', 1, 'jjjjjjjjjjj', 'LODIFHI', '2024-12-14 19:44:39', '2024-12-14 19:44:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_09_21_073008_create_medical_cases_table', 2),
(8, '2024_09_24_063600_add_barangay_to_vehicular_accidents_table', 4),
(10, '2024_09_22_022631_add_is_approved_to_medical_cases_table', 5),
(11, '2024_10_01_084613_create_barangays_table', 6),
(12, '2024_09_21_073120_create_vehicular_accidents_table', 7),
(13, '2024_11_19_143841_create_vehicle_details_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rescue_teams`
--

CREATE TABLE `rescue_teams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rescue_teams`
--

INSERT INTO `rescue_teams` (`id`, `team_name`, `created_at`, `updated_at`) VALUES
(2, 'Alpha', '2024-12-14 18:38:47', '2024-12-14 19:46:46'),
(3, 'Bravo', '2024-12-14 18:38:54', '2024-12-14 18:38:54'),
(4, 'Charlie', '2024-12-14 18:39:00', '2024-12-14 18:39:00'),
(5, 'Delta', '2024-12-14 18:39:05', '2024-12-14 18:39:05');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('srCLU5SQEAZR2nAh9Uci1E3XiymvhugdWImxzXFk', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib1pIQkxiM3Vidk9pY3psV2VGTDJjOFN3aEp2Y2FEZE10Q28yTUxpSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXNjdWVfdGVhbSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1734358958);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'user', 'user@gmail.com', 1, NULL, '$2y$12$UucFnnfZLNCoCxZ48NpHvOlDqxnLbmL5rSAVC5IOMitO1eQL01R8y', 'RpSxGxaIAk7QSVSdmr6epMeEpuiSytpNEvSaVniSeCIRYC7T3sa3KNjYW0pd', '2024-09-17 18:15:06', '2024-11-19 04:32:05'),
(2, 'admin', 'admin@gmail.com', 0, NULL, '$2y$12$IhO1dKAae5O4u.sQoVC0MeBcJmSpyaVcrI1a9tDpol/7vXcLqFM1i', 'ezRyPki85RsWcYvR2lZJZeNfRHo1OfHXL9em4rRxpLff1SS48o1SikkgvpJs', '2024-09-17 19:27:14', '2024-09-17 19:27:14'),
(3, 'ben', 'ben@gmail.com', 1, NULL, '$2y$12$VxTidW8ftxPQrHD/DmHpOuzGvvsjzFraJcl1BislFluyH/W/2dP/W', NULL, '2024-09-18 04:05:35', '2024-10-02 05:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_details`
--

CREATE TABLE `vehicle_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicular_accident_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_type` varchar(255) NOT NULL,
  `vehicle_detail` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_details`
--

INSERT INTO `vehicle_details` (`id`, `vehicular_accident_id`, `vehicle_type`, `vehicle_detail`, `created_at`, `updated_at`) VALUES
(2, 2, 'Motorcycle', 'aerox', '2024-11-19 07:24:06', '2024-11-19 07:24:06'),
(6, 1, 'Car', 'mustang', '2024-11-19 17:01:01', '2024-11-19 17:01:01'),
(7, 3, 'Car', 'mustang', '2024-11-19 17:19:17', '2024-11-19 17:19:17'),
(8, 4, 'Bike', 'trisikad', '2024-12-10 21:10:15', '2024-12-10 21:10:15'),
(9, 5, 'Motorcycle', 'aerox', '2024-12-10 22:51:24', '2024-12-10 22:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `vehicular_accidents`
--

CREATE TABLE `vehicular_accidents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `rescue_team` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `place_of_incident` varchar(255) NOT NULL,
  `no_of_patients` int(11) NOT NULL,
  `cause_of_incident` varchar(255) DEFAULT NULL,
  `vehicles_involved` varchar(255) DEFAULT NULL,
  `facility_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_approved` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicular_accidents`
--

INSERT INTO `vehicular_accidents` (`id`, `date`, `rescue_team`, `city`, `barangay`, `place_of_incident`, `no_of_patients`, `cause_of_incident`, `vehicles_involved`, `facility_name`, `created_at`, `updated_at`, `is_approved`) VALUES
(1, '2024-01-01', 'Bravo', '0', 'Old Poblacion', 'prk. puto', 1, 'Over Speeding', 'Car', 'LODIFHI', '2024-11-19 06:46:13', '2024-11-19 07:38:16', 1),
(2, '2024-01-01', 'Alpha', '0', 'Old Poblacion', '1', 1, 'Road Hazards', 'Motorcycle', 'LODIFHI', '2024-11-19 07:24:06', '2024-11-19 07:24:06', 1),
(3, '2024-01-01', 'Bravo', '0', 'Dian-ay', 'prk. puto', 1, 'someone shoot me while i\'m driving', 'Car', 'LODIFHI', '2024-11-19 17:19:17', '2024-11-19 17:19:17', 1),
(4, '2024-12-12', 'Delta', 'ESCALANTE CITY', 'Buenavista', 'prk. puto', 1, 'naka bunggo ido buang', 'Bike', 'LODIFHI', '2024-12-10 21:10:15', '2024-12-10 21:10:15', 1),
(5, '2024-11-12', 'Bravo', 'ESCALANTE CITY', 'Buenavista', 'prk. puto', 1, 'Drunk Driving', 'Motorcycle', 'LODIFHI', '2024-12-10 22:51:24', '2024-12-10 22:51:24', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `disasters`
--
ALTER TABLE `disasters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disaster_type`
--
ALTER TABLE `disaster_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medical_cases`
--
ALTER TABLE `medical_cases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `rescue_teams`
--
ALTER TABLE `rescue_teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_name` (`team_name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicle_details`
--
ALTER TABLE `vehicle_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_details_vehicular_accident_id_foreign` (`vehicular_accident_id`);

--
-- Indexes for table `vehicular_accidents`
--
ALTER TABLE `vehicular_accidents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `disasters`
--
ALTER TABLE `disasters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `disaster_type`
--
ALTER TABLE `disaster_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_cases`
--
ALTER TABLE `medical_cases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `rescue_teams`
--
ALTER TABLE `rescue_teams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicle_details`
--
ALTER TABLE `vehicle_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `vehicular_accidents`
--
ALTER TABLE `vehicular_accidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vehicle_details`
--
ALTER TABLE `vehicle_details`
  ADD CONSTRAINT `vehicle_details_vehicular_accident_id_foreign` FOREIGN KEY (`vehicular_accident_id`) REFERENCES `vehicular_accidents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
