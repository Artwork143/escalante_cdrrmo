-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 02:33 AM
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

INSERT INTO `medical_cases` (`id`, `date`, `rescue_team`, `place_of_incident`, `barangay`, `no_of_patients`, `chief_complaints`, `facility_name`, `created_at`, `updated_at`, `is_approved`) VALUES
(2, '2024-04-06', 'Charlie', 'prk. makiangayon', 'Alimango', 1, 'body weaknesses', 'VGDH', '2024-09-21 21:57:45', '2024-10-02 17:34:07', 1),
(8, '2024-04-06', 'Charlie', 'public plaza', 'Balintawak', 1, 'dizziness and fainting', 'VGDH', '2024-09-22 00:10:58', '2024-10-02 17:37:42', 1),
(15, '2024-04-16', 'Charlie', 'PBB', 'Balintawak', 1, 'difficulty of breathing', 'VGDH', '2024-09-22 00:53:22', '2024-10-02 17:39:20', 1),
(16, '2024-04-16', 'Bravo', 'so. benit-agan', 'Buenavista', 1, 'body weaknesses (history of stroke)', 'VGDH', '2024-09-22 00:54:44', '2024-10-02 17:41:45', 1),
(17, '2024-04-16', 'Charlie', 'back of old city hall', 'Balintawak', 1, 'dizziness and hypertension', 'VGDH', '2024-09-22 04:14:48', '2024-10-02 17:46:52', 1),
(19, '2024-04-23', 'Charlie', 'so. bangkiling', 'Old Poblacion', 1, 'body weaknesses', 'VGDH', '2024-09-24 00:49:36', '2024-10-02 17:50:29', 1),
(26, '2022-01-31', 'Alpha', 'prk. puto', 'Alimango', 1, 'qqqqqq', 'LODIFHI', '2024-11-17 19:13:14', '2024-11-17 19:13:14', 1),
(27, '2024-01-01', 'Bravo', '1', 'Balintawak', 1, 'aaa', 'LODIFHI', '2024-11-17 19:25:56', '2024-11-17 19:25:56', 1),
(28, '2022-01-01', 'Bravo', 'prk. puto', 'Binaguiohan', 1, 'qq', 'LODIFHI', '2024-11-17 19:59:28', '2024-11-17 19:59:28', 1),
(29, '2023-01-01', 'Alpha', 'prk. puto', 'Binaguiohan', 1, 'sss', 'LODIFHI', '2024-11-17 19:59:46', '2024-11-17 19:59:46', 1),
(30, '2023-01-31', 'Bravo', 'prk. puto', 'Binaguiohan', 1, 'lll', 'LODIFHI', '2024-11-17 20:00:15', '2024-11-17 20:00:15', 1),
(31, '2024-01-01', 'Bravo', 'prk. puto', 'Balintawak', 1, 'ggggg', 'LODIFHI', '2024-11-17 20:00:55', '2024-11-17 20:00:55', 1),
(32, '2024-01-01', 'Charlie', 'prk. puto', 'Balintawak', 1, 'iiiiiiiii', 'LODIFHI', '2024-11-17 20:01:13', '2024-11-17 20:01:13', 1),
(33, '2019-01-31', 'Alpha', 'prk. puto', 'Buenavista', 1, 'q', 'LODIFHI', '2024-11-18 00:27:00', '2024-11-18 00:27:00', 1);

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
('uw5xWh5TYdKaInSpggItXugsLh6OpAkIO3B17bPV', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWTI1WGI4YXdIUmtKZmlHOUFMV3pybnNBS0tqSmVZMTNTaHJYZ1hQSSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjcyOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZ2V0LWJhcmFuZ2F5LWFjY2lkZW50cy9EaWFuLWF5P21vbnRoPSZwYWdlPTEmeWVhcj0iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1732066404);

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
(1, 'user', 'user@gmail.com', 1, NULL, '$2y$12$UucFnnfZLNCoCxZ48NpHvOlDqxnLbmL5rSAVC5IOMitO1eQL01R8y', 'LAi8KGKGetzGDnIBQpOASQ8de8RXF46QUCNAG6MXVCGF2okovm63prYs1hWt', '2024-09-17 18:15:06', '2024-11-19 04:32:05'),
(2, 'admin', 'admin@gmail.com', 0, NULL, '$2y$12$IhO1dKAae5O4u.sQoVC0MeBcJmSpyaVcrI1a9tDpol/7vXcLqFM1i', NULL, '2024-09-17 19:27:14', '2024-09-17 19:27:14'),
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
(7, 3, 'Car', 'mustang', '2024-11-19 17:19:17', '2024-11-19 17:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `vehicular_accidents`
--

CREATE TABLE `vehicular_accidents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `rescue_team` varchar(255) NOT NULL,
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

INSERT INTO `vehicular_accidents` (`id`, `date`, `rescue_team`, `barangay`, `place_of_incident`, `no_of_patients`, `cause_of_incident`, `vehicles_involved`, `facility_name`, `created_at`, `updated_at`, `is_approved`) VALUES
(1, '2024-01-01', 'Bravo', 'Old Poblacion', 'prk. puto', 1, 'Over Speeding', 'Car', 'LODIFHI', '2024-11-19 06:46:13', '2024-11-19 07:38:16', 1),
(2, '2024-01-01', 'Alpha', 'Old Poblacion', '1', 1, 'Road Hazards', 'Motorcycle', 'LODIFHI', '2024-11-19 07:24:06', '2024-11-19 07:24:06', 1),
(3, '2024-01-01', 'Bravo', 'Dian-ay', 'prk. puto', 1, 'someone shoot me while i\'m driving', 'Car', 'LODIFHI', '2024-11-19 17:19:17', '2024-11-19 17:19:17', 1);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicle_details`
--
ALTER TABLE `vehicle_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vehicular_accidents`
--
ALTER TABLE `vehicular_accidents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
