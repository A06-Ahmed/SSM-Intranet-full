-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 11:46 AM
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
-- Database: `smm_bacend`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `module`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-16 12:29:49'),
(2, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-16 13:49:10'),
(3, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-16 13:49:11'),
(4, 4, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-16 14:15:23'),
(5, 5, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-16 14:19:49'),
(6, 5, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-16 15:38:36'),
(7, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-16 15:39:25'),
(8, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-17 08:47:26'),
(9, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-17 08:52:45'),
(10, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-17 09:39:29'),
(11, 8, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-17 10:54:40'),
(12, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-17 10:55:25'),
(13, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-17 12:40:04'),
(14, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-18 09:11:28'),
(15, 1, 'login', 'auth', 'User login', '127.0.0.1', '2026-03-18 09:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `priority_status` varchar(20) NOT NULL DEFAULT 'Moyenne',
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `priority_status`, `author_id`, `is_published`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'mantenance', 'npm run devnpm run devnpm run devnpm run devnpm run devnpm run devnpm run dev', 'Moyenne', 1, 0, NULL, '2026-03-16 13:14:42', '2026-03-16 13:14:42', NULL),
(2, '3ajil', '3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil', 'Haute', 1, 1, '2026-03-17 09:41:59', '2026-03-17 09:41:59', '2026-03-17 09:41:59', NULL),
(3, 'mantenance', 'a mantenance is take due 9/11 a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11a mantenance is take due 9/11', 'Haute', 1, 0, NULL, '2026-03-17 13:42:43', '2026-03-17 13:42:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('smm-intranet-cache-94d92f976fd06fd3e8cf53ec4e03d646', 'i:2;', 1773825146),
('smm-intranet-cache-94d92f976fd06fd3e8cf53ec4e03d646:timer', 'i:1773825146;', 1773825146),
('smm-intranet-cache-D4AloHG9ReSIctJ7', 's:7:\"forever\";', 2089035535),
('smm-intranet-cache-g0pXAwSFPCplW83e', 's:7:\"forever\";', 2089100201),
('smm-intranet-cache-iwPa1SQw79KYNyLk', 's:7:\"forever\";', 2089104902),
('smm-intranet-cache-mE4vqa5XUgQd36Ot', 's:7:\"forever\";', 2089030513),
('smm-intranet-cache-mpQBD3gQkNUSv3cv', 's:7:\"forever\";', 2089030701),
('smm-intranet-cache-Tjgn47ITMqvu82JP', 's:7:\"forever\";', 2089097493);

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
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `type` enum('internal','external') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `manager_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Human Resources', 'People and culture', NULL, '2026-03-16 11:51:19', '2026-03-16 11:51:19', NULL),
(2, 'IT', 'Infrastructure and support', NULL, '2026-03-16 11:51:19', '2026-03-16 11:51:19', NULL),
(3, 'Operations', 'Core operations', NULL, '2026-03-16 11:51:19', '2026-03-16 11:51:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `matricule` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `office_location` varchar(255) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `status` enum('active','inactive','on_leave') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `matricule`, `position`, `phone`, `office_location`, `department_id`, `photo_url`, `hire_date`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'EMP-0001', 'Staff', '000-000-0000', 'HQ', 1, NULL, '2026-02-14', 'active', '2026-03-16 11:51:19', '2026-03-16 12:54:36', NULL),
(2, 3, 'EMP-0002', 'Staff', '000-000-0000', 'HQ', 1, NULL, '2026-02-04', 'active', '2026-03-16 11:51:19', '2026-03-16 12:54:36', NULL),
(3, 8, 'artisan@artisan.ma', 'artisan@artisan.ma', 'artisan@artisan.ma', 'artisan@artisan.ma', NULL, NULL, '2026-03-17', 'active', '2026-03-17 10:12:50', '2026-03-17 10:12:50', NULL);

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
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `description`, `image_url`, `uploaded_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'uw', NULL, '/storage/gallery/YcwcwmgWDg98IB97OO9ZDFDFkOavzoVcDmOeljE7.jpg', 1, '2026-03-16 13:38:54', '2026-03-18 10:35:45', '2026-03-18 10:35:45'),
(2, 'cd C:\\Users\\asus\\Desktop\\SMM\\smm_bacend php artisan migrate', 'cd C:\\Users\\asus\\Desktop\\SMM\\smm_bacend\r\nphp artisan migrate\r\ncd C:\\Users\\asus\\Desktop\\SMM\\smm_bacend\r\nphp artisan migrate', '/storage/gallery/Mjl1XuGWPMQW1T6HDpucCFHbHtYYlOdkN3AhJOYE.jpg', 1, '2026-03-16 13:56:48', '2026-03-18 10:35:44', '2026-03-18 10:35:44'),
(3, 'party', '3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil 3ajil3ajil3ajil3ajil3ajil', '/storage/gallery/ORlExs4o01fERVPORPmDWC10OrvB48npAr92og3U.jpg', 1, '2026-03-17 09:42:22', '2026-03-18 10:35:42', '2026-03-18 10:35:42'),
(4, 'php artisan migrate', 'php artisan migrate\r\nphp artisan migrate\r\nphp artisan migrate', '/storage/gallery/O1vwZUEMEG38977ZMpJWOnILWvl5Xc1EdtGKLHvl.jpg', 1, '2026-03-17 10:34:20', '2026-03-18 10:35:41', '2026-03-18 10:35:41'),
(5, 'wawa', 'wawa', 'http://localhost:8000/storage/gallery/KyqselOWnJCX9jeTmP8iH1hDO4BxuW2jN7FRaPPG.jpg', 1, '2026-03-17 10:55:58', '2026-03-18 10:35:40', '2026-03-18 10:35:40'),
(6, 'new take', NULL, 'http://localhost:8000/storage/gallery/I0eEXhwlW2VDcznmJR6TDNuB20wCL7VP66hPLB8l.png', 1, '2026-03-18 09:57:45', '2026-03-18 10:35:26', '2026-03-18 10:35:26'),
(7, 'sabsab', NULL, 'gallery/wFiWCU28QJy0EO8GyqdNuwkMFRg4OrcLRrYBRdrc.jpg', 1, '2026-03-18 10:34:39', '2026-03-18 10:35:25', '2026-03-18 10:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gallery_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `gallery_id`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 2, '/storage/gallery/Mjl1XuGWPMQW1T6HDpucCFHbHtYYlOdkN3AhJOYE.jpg', '2026-03-16 13:56:48', '2026-03-16 13:56:48'),
(2, 2, '/storage/gallery/H5g0KNQRYW4d3jRPvELZs8TetjHTId4W5SnJhsf2.png', '2026-03-16 13:56:48', '2026-03-16 13:56:48'),
(3, 2, '/storage/gallery/ISoSTDyDxqPl0s6wjwkAsDENlcUL0JZxh48VK2kW.png', '2026-03-16 13:56:48', '2026-03-16 13:56:48'),
(4, 2, '/storage/gallery/hdvIfDMDlThplUinz7xBlNb3BJTTdALReYLL4Ctg.png', '2026-03-16 13:56:48', '2026-03-16 13:56:48'),
(5, 3, '/storage/gallery/ORlExs4o01fERVPORPmDWC10OrvB48npAr92og3U.jpg', '2026-03-17 09:42:22', '2026-03-17 09:42:22'),
(6, 3, '/storage/gallery/HUoEX5NYjnNXtbWvmC5ARJLeivi9c4CIIvrxxaut.jpg', '2026-03-17 09:42:22', '2026-03-17 09:42:22'),
(7, 3, '/storage/gallery/K4ofTySf81QjMYjRHy5l7vBywjvUXkgfwy6rIZTc.jpg', '2026-03-17 09:42:22', '2026-03-17 09:42:22'),
(8, 4, '/storage/gallery/O1vwZUEMEG38977ZMpJWOnILWvl5Xc1EdtGKLHvl.jpg', '2026-03-17 10:34:20', '2026-03-17 10:34:20'),
(9, 4, '/storage/gallery/7VA28HTOXzllYrB4x6hi47O36uc47LIHNd3t7jnB.jpg', '2026-03-17 10:34:20', '2026-03-17 10:34:20'),
(10, 4, '/storage/gallery/IoePUVndeTYUgkGvd3UzaUMr4QRHc46GSwve84bt.jpg', '2026-03-17 10:34:20', '2026-03-17 10:34:20'),
(11, 5, 'http://localhost:8000/storage/gallery/KyqselOWnJCX9jeTmP8iH1hDO4BxuW2jN7FRaPPG.jpg', '2026-03-17 10:55:58', '2026-03-17 10:55:58'),
(12, 5, 'http://localhost:8000/storage/gallery/CCv7eQUAsz5xvsJ7pNCtbH8GTeKYQgQNN9hkzuj0.jpg', '2026-03-17 10:55:58', '2026-03-17 10:55:58'),
(13, 5, 'http://localhost:8000/storage/gallery/08nLGBvotDnaT6gma88oCahAvJNc2uICcHwNGwPV.jpg', '2026-03-17 10:55:58', '2026-03-17 10:55:58'),
(14, 6, 'http://localhost:8000/storage/gallery/I0eEXhwlW2VDcznmJR6TDNuB20wCL7VP66hPLB8l.png', '2026-03-18 09:57:45', '2026-03-18 09:57:45'),
(15, 6, 'http://localhost:8000/storage/gallery/4yRBbJlJevjaqJIQV55UJMaJUsjbRYvpHCHoDb2S.png', '2026-03-18 09:57:45', '2026-03-18 09:57:45'),
(16, 6, 'http://localhost:8000/storage/gallery/laBJBwBRCzImsL63x33QV8QRMBPYeMw1Wjz5tW1r.png', '2026-03-18 09:57:45', '2026-03-18 09:57:45'),
(17, 7, 'gallery/wFiWCU28QJy0EO8GyqdNuwkMFRg4OrcLRrYBRdrc.jpg', '2026-03-18 10:34:39', '2026-03-18 10:34:39'),
(18, 7, 'gallery/Zeyhf0A6RkEcXaJN2XcKUXNF376bkL0dECURxp02.jpg', '2026-03-18 10:34:39', '2026-03-18 10:34:39'),
(19, 7, 'gallery/KkyJAQp3njMSOTGha262JhIKIGZlLFQvYJpvVcb9.jpg', '2026-03-18 10:34:39', '2026-03-18 10:34:39');

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
(1, '0000_01_01_000000_create_departments_table', 1),
(2, '0000_01_02_000000_create_roles_permissions_tables', 1),
(3, '0001_01_01_000000_create_users_table', 1),
(4, '0001_01_01_000001_create_cache_table', 1),
(5, '0001_01_01_000002_create_jobs_table', 1),
(6, '0001_01_02_000000_create_employees_table', 1),
(7, '0001_01_03_000000_create_news_table', 1),
(8, '0001_01_04_000000_create_gallery_table', 1),
(9, '0001_01_05_000000_create_audit_logs_table', 1),
(10, '0001_01_06_000000_create_refresh_tokens_table', 1),
(11, '2026_03_16_120000_create_role_user_table', 2),
(12, '2026_03_16_120010_create_permission_role_table', 2),
(13, '2026_03_16_120020_update_users_remove_role_id', 2),
(14, '2026_03_16_120030_create_projects_table', 2),
(15, '2026_03_16_120040_create_tasks_table', 2),
(16, '2026_03_16_120050_create_applications_table', 2),
(17, '2026_03_16_120060_create_activity_logs_table', 2),
(18, '2026_03_16_130100_create_announcements_table', 3),
(19, '2026_03_16_141000_create_gallery_images_table', 4),
(20, '2026_03_16_145500_add_description_to_gallery_table', 4),
(21, '2026_03_16_153000_create_contacts_table', 5),
(22, '2026_03_16_160000_create_notifications_table', 6),
(23, '2026_03_17_090000_add_priority_status_to_announcements_table', 6),
(24, '2026_03_17_103000_make_employee_department_nullable', 7),
(25, '2026_03_17_103500_make_employee_position_nullable', 8);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image_url`, `author_id`, `is_published`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Welcome to SMM Intranet', 'Welcome to the SMM Intranet platform.', NULL, 1, 1, '2026-03-16 12:54:36', '2026-03-16 11:51:19', '2026-03-18 10:36:23', '2026-03-18 10:36:23'),
(2, 'here we go', 'here we go here we go here we go here we go here we go here we go here we go here we go', '/storage/news/G44mk3sCDJMjmfOhEJxSKh8y0HNtURgjthr21p8M.png', 1, 1, '2026-03-16 13:32:46', '2026-03-16 13:32:40', '2026-03-16 13:37:46', '2026-03-16 13:37:46'),
(3, 'now', 'now for à la use or news doesnt work it tells me unauthenticated \r\n\r\nwhen upploadding an image \r\nInternal Server Error\r\n\r\nIlluminate\\Database\\QueryException\r\nSQLSTATE[HY000]: General error: 1364 Field \'image_url\' doesn\'t have a default value (Connection: mysql, SQL: insert into `gallery` (`title`, `uploaded_by`, `updated_at`, `created_at`) values (wac, 1, 2026-03-16 13:34:45, 2026-03-16 13:34:45))for à la use or news doesnt work it tells me unauthenticated \r\n\r\nwhen upploadding an image \r\nInternal Server Error\r\n\r\nIlluminate\\Database\\QueryException\r\nSQLSTATE[HY000]: General error: 1364 Field \'image_url\' doesn\'t have a default value (Connection: mysql, SQL: insert into `gallery` (`title`, `uploaded_by`, `updated_at`, `created_at`) values (wac, 1, 2026-03-16 13:34:45, 2026-03-16 13:34:45))for à la use or news doesnt work it tells me unauthenticated \r\n\r\nwhen upploadding an image \r\nInternal Server Error\r\n\r\nIlluminate\\Database\\QueryException\r\nSQLSTATE[HY000]: General error: 1364 Field \'image_url\' doesn\'t have a default value (Connection: mysql, SQL: insert into `gallery` (`title`, `uploaded_by`, `updated_at`, `created_at`) values (wac, 1, 2026-03-16 13:34:45, 2026-03-16 13:34:45))', '/storage/news/gMYKbo0NbCeo6Slw88lVjkRZbLfFsVFBNJoAsJeE.png', 1, 1, '2026-03-16 13:38:14', '2026-03-16 13:38:14', '2026-03-18 10:36:22', '2026-03-18 10:36:22'),
(4, 'Fix the following issues in the Laravel backend related to News and Gallery image uploads.', 'Fix the following issues in the Laravel backend related to News and Gallery image uploads.\r\n\r\n1. News Image Upload\r\nCurrently when adding a news post:\r\n- Large images cannot be uploaded.\r\n- After publishing the news, the image does not appear on the website.\r\n\r\nFix the system so that:\r\n- The backend accepts larger image uploads (increase PHP/Laravel upload limits).\r\n- Images are stored correctly in Laravel storage (storage/app/public/news).\r\n- The image path is saved correctly in the database.\r\n- The image is properly displayed on the frontend after publishing the news.\r\n\r\n2. Gallery Multiple Image Upload\r\nWhen uploading multiple images to a gallery, the system throws this error:\r\n\r\nSQLSTATE[42S02]: Base table or view not found: 1146 Table \'gallery_images\' doesn\'t exist.\r\n\r\nFix this by:\r\n- Creating the missing `gallery_images` table through a Laravel migration.\r\n- The table should store:\r\n  - id\r\n  - gallery_id (foreign key to galleries table)\r\n  - image_url\r\n  - timestamps\r\n\r\n3. Gallery Structure\r\nEach gallery should act as an album:\r\n- A gallery entry (title, description, uploaded_by)\r\n- Multiple images linked to that gallery via the `gallery_images` table.\r\n\r\n4. Expected Behavior\r\n- Admin can upload multiple images under one gallery album.\r\n- Images are stored in Laravel storage (storage/app/public/gallery).\r\n- The image URLs are saved in the `gallery_images` table.\r\n- The frontend can fetch and display all images related to a gallery album.\r\n\r\nGoal:\r\nEnsure both the News and Gallery modules correctly upload, store, and display images without database or file upload errors.', 'http://localhost:8000/storage/news/i4PAQ9f0C9oCdjetSh2Tkf0zAB0VxW1eYUpGftZ8.png', 1, 1, '2026-03-16 13:46:51', '2026-03-16 13:46:51', '2026-03-18 10:36:21', '2026-03-18 10:36:21'),
(5, 'wow wowo', 'wow You are a senior Laravel engineer debugging a Windows development environment.\r\n\r\nProblem:\r\nImages are correctly uploaded to:\r\n\r\nstorage/app/public/gallery\r\n\r\nThe URL being accessed is:\r\n\r\nhttp://localhost:8000/storage/gallery/filename.jpg\r\n\r\nBut it returns 404 when running:\r\n\r\nphp artisan serve\r\n\r\nThe storage symlink was created using:\r\n\r\nphp artisan storage:link\r\n\r\nHowever, on Windows, the symlink does not properly resolve when using artisan serve.\r\n\r\nYour task:\r\n\r\n1) Remove dependency on symbolic links entirely.\r\n2) Implement a solution that serves images directly from storage without using public/storage symlink.\r\n3) Ensure it works reliably on Windows using php artisan serve.\r\n\r\n---\r\n\r\nRequirements:\r\n\r\n• Delete reliance on public/storage symlink\r\n• Add a new route that serves images using response()->file()\r\n• Make it secure (prevent directory traversal)\r\n• Update the API to return URLs pointing to the new route\r\n• Keep the solution clean and production-safe\r\n\r\n---\r\n\r\nExpected solution structure:\r\n\r\n1) Add route:\r\n\r\nRoute::get(\'/gallery-image/{filename}\', function ($filename) {\r\n    $path = storage_path(\'app/public/gallery/\' . $filename);\r\n\r\n    if (!file_exists($path)) {\r\n        abort(404);\r\n    }\r\n\r\n    return response()->file($path);\r\n});\r\n\r\n2) Modify upload logic so API returns:\r\n\r\nhttp://localhost:8000/gallery-image/filename.jpg\r\n\r\ninstead of:\r\n\r\n/storage/gallery/filename.jpg\r\n\r\n3) Extract filename safely using basename()\r\n\r\n4) Keep code clean and minimal.\r\n\r\nExplain briefly why Windows symlinks can fail with artisan serve.', 'http://localhost:8000/storage/news/Yf6SaZcIvxRXlB3lWTrdFw9wq6l8JqHSZsayG5Wj.jpg', 1, 1, '2026-03-18 10:37:19', '2026-03-18 10:36:48', '2026-03-18 10:37:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `body`, `type`, `related_id`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'Nouvelle annonce', '3ajil', 'announcement', 2, '2026-03-18 09:41:59', '2026-03-17 09:41:59', '2026-03-17 09:41:59'),
(2, 'Nouvelle galerie', 'party', 'gallery', 3, '2026-03-18 09:42:22', '2026-03-17 09:42:22', '2026-03-17 09:42:22'),
(3, 'Nouvelle galerie', 'php artisan migrate', 'gallery', 4, '2026-03-18 10:34:20', '2026-03-17 10:34:20', '2026-03-17 10:34:20'),
(4, 'Nouvelle galerie', 'wawa', 'gallery', 5, '2026-03-18 10:55:58', '2026-03-17 10:55:58', '2026-03-17 10:55:58'),
(5, 'Nouvelle annonce', 'mantenance', 'announcement', 3, '2026-03-18 13:42:43', '2026-03-17 13:42:43', '2026-03-17 13:42:43'),
(6, 'Nouvelle galerie', 'new take', 'gallery', 6, '2026-03-19 09:57:45', '2026-03-18 09:57:45', '2026-03-18 09:57:45'),
(7, 'Nouvelle galerie', 'sabsab', 'gallery', 7, '2026-03-19 10:34:39', '2026-03-18 10:34:39', '2026-03-18 10:34:39'),
(8, 'Nouvelle actualité', 'wow wowo', 'news', 5, '2026-03-19 10:36:48', '2026-03-18 10:36:48', '2026-03-18 10:36:48'),
(9, 'Actualité mise à jour', 'wow wowo', 'news', 5, '2026-03-19 10:37:19', '2026-03-18 10:37:19', '2026-03-18 10:37:19');

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `module`, `created_at`, `updated_at`) VALUES
(1, 'create_user', 'users', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(2, 'edit_user', 'users', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(3, 'change_user_role', 'users', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(4, 'create_department', 'departments', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(5, 'edit_department', 'departments', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(6, 'delete_department', 'departments', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(7, 'create_employee', 'employees', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(8, 'edit_employee', 'employees', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(9, 'delete_employee', 'employees', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(10, 'create_news', 'news', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(11, 'edit_news', 'news', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(12, 'delete_news', 'news', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(13, 'create_gallery', 'gallery', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(14, 'delete_gallery', 'gallery', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(15, 'users.create', 'users', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(16, 'users.read', 'users', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(17, 'users.update', 'users', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(18, 'users.delete', 'users', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(19, 'projects.create', 'projects', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(20, 'projects.read', 'projects', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(21, 'projects.update', 'projects', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(22, 'projects.delete', 'projects', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(23, 'tasks.create', 'tasks', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(24, 'tasks.assign', 'tasks', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(25, 'tasks.update', 'tasks', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(26, 'tasks.delete', 'tasks', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(27, 'roles.manage', 'roles', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(28, 'permissions.manage', 'permissions', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(29, 'reports.view', 'reports', '2026-03-16 12:27:41', '2026-03-16 12:27:41'),
(30, 'system.settings', 'system', '2026-03-16 12:27:41', '2026-03-16 12:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(15, 2),
(16, 1),
(16, 2),
(16, 3),
(17, 1),
(17, 2),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(20, 4),
(20, 5),
(20, 8),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(23, 4),
(24, 1),
(24, 2),
(24, 4),
(25, 1),
(25, 2),
(25, 4),
(25, 5),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(29, 3),
(29, 4),
(30, 1),
(30, 2);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `status`, `start_date`, `end_date`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mofo', 'sdv', 'active', NULL, NULL, 1, '2026-03-16 12:42:22', '2026-03-16 12:42:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `refresh_tokens`
--

CREATE TABLE `refresh_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `revoked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `refresh_tokens`
--

INSERT INTO `refresh_tokens` (`id`, `user_id`, `token_hash`, `expires_at`, `revoked_at`, `created_at`, `updated_at`) VALUES
(1, 1, '7865d09d4f205fc2242793ba36478e9d6870c1e8e91bc32af62b7947eb26ef90', '2026-03-30 12:29:49', NULL, '2026-03-16 12:29:49', '2026-03-16 12:29:49'),
(2, 1, '6fdae8cdcfa47912a1740c587897093ec14280ea8408ef31fdbd258ea70aa49b', '2026-03-30 13:49:10', NULL, '2026-03-16 13:49:10', '2026-03-16 13:49:10'),
(3, 1, '657399a5f12e0a2aa601862d76764f8de74b7744faf881570218e79e3c109358', '2026-03-16 14:15:13', '2026-03-16 14:15:13', '2026-03-16 13:49:11', '2026-03-16 14:15:13'),
(4, 4, '0b1fb81b192a52baa07f500e8420e5892cbcf39db7848ef264df490813b6a979', '2026-03-16 14:18:21', '2026-03-16 14:18:21', '2026-03-16 14:15:23', '2026-03-16 14:18:21'),
(5, 5, '4d954b9c90e7023405af547fc36cd7ae2b0bbd007ef362c583fb282e6009345a', '2026-03-30 14:19:49', NULL, '2026-03-16 14:19:49', '2026-03-16 14:19:49'),
(6, 5, '8c9be9859aa8a6a67096ed619d1337887335ce331c6163e705c65c595a314c13', '2026-03-16 15:38:55', '2026-03-16 15:38:55', '2026-03-16 15:38:36', '2026-03-16 15:38:55'),
(7, 1, '0b145d078a28b0f61f6f4bd8abdb21c27e8c7419daa903abcb3bfb2c01d34b25', '2026-03-30 15:39:25', NULL, '2026-03-16 15:39:25', '2026-03-16 15:39:25'),
(8, 1, '5169e7d4087d67abc0b4c99f528863a88d2e9dc3222162a17a52be615a12d57b', '2026-03-17 08:51:33', '2026-03-17 08:51:33', '2026-03-17 08:47:26', '2026-03-17 08:51:33'),
(9, 1, 'fcb83805771485e0edf811fc104f62322cdd916b8832cd0c9d19d5294af22e6e', '2026-03-17 09:36:41', '2026-03-17 09:36:41', '2026-03-17 08:52:45', '2026-03-17 09:36:41'),
(10, 1, '398972a901a7caf01a49833e4dd917236107179dee42ae52820c4ba3363b9d34', '2026-03-31 09:39:29', NULL, '2026-03-17 09:39:29', '2026-03-17 09:39:29'),
(11, 8, '5f40c4ef630cb146b908389d0c70a50b2734dd08250f77ecef34b6df1526333f', '2026-03-17 10:55:02', '2026-03-17 10:55:02', '2026-03-17 10:54:40', '2026-03-17 10:55:02'),
(12, 1, '98878c46483b54e9f8835dc2c9202209509fab7ce19a85df258bf44ea7ffe94c', '2026-03-31 10:55:25', NULL, '2026-03-17 10:55:25', '2026-03-17 10:55:25'),
(13, 1, '4948db1aa3a12d9eb20895dd204fbeaf953c91a3fc3ec6942cc67d9778d9c959', '2026-03-31 12:40:04', NULL, '2026-03-17 12:40:04', '2026-03-17 12:40:04'),
(14, 1, '1eb4cf27f6f0aea292a359f531956176aae15aadaecc13914d5e13af68a8f0a6', '2026-04-01 09:11:27', NULL, '2026-03-18 09:11:27', '2026-03-18 09:11:27'),
(15, 1, 'de547284014448b1f5fbf8661b296351172af0b1fba47b85fdbc71af09504968', '2026-04-01 09:11:30', NULL, '2026-03-18 09:11:30', '2026-03-18 09:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'SuperAdmin', 'System super administrator', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(2, 'Admin', 'System administrator', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(3, 'HR', 'Human resources', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(4, 'Manager', 'Department manager', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(5, 'Employee', 'Employee', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(6, 'ITSupport', 'IT support', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(7, 'ContentEditor', 'Content editor', '2026-03-16 11:51:18', '2026-03-16 11:51:18'),
(8, 'Guest', 'Guest user', '2026-03-16 12:27:41', '2026-03-16 12:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14),
(3, 4),
(3, 5),
(3, 7),
(3, 8),
(3, 9),
(4, 7),
(4, 8),
(6, 2),
(7, 10),
(7, 11),
(7, 12),
(7, 13),
(7, 14);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`) VALUES
(1, 1),
(3, 4),
(3, 10),
(4, 7),
(5, 2),
(5, 3),
(5, 5),
(5, 6),
(5, 8),
(5, 9);

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
('e6ff2nVTcB8C9vbdurPcrpNa22P8ccvcnBSoQDxh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2gzdFNBeklmYXk3djNRVGNjTnVMVDV4bDB2RmxlOUt5TGdGTUJWMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nYWxsZXJ5LWltYWdlL1ljd2N3bWdXRGc5OElCOTdPTzlaREZERmtPYXZ6b1ZjRG1PZWxqRTcuanBnIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773830092),
('MMUg6tcm95uTO0wCsvRI1rUZikzl86gucEQBMWwm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicmw0U1NzU1BGMEdvR1poYVltQVF1eHNTdEhpVjBRSG9ReVp4ZjBvcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nYWxsZXJ5LWltYWdlL01qbDFYdUdXUE1RVzFUNkhEcHVjQ0ZIYkh0WVlsT2RrTjNBaEpPWUUuanBnIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773830092),
('NEnipYNirqkNsB24bykHYmewNX6WAOCIRtkD212D', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN0VHMzZINlBFdnNsTWloanl6MWhKVmxRdlgyOEVqMjdQcU5iNlhyeSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjc3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvbmV3cy1pbWFnZS9ZZjZTYVpjSXZ4UlhsQjNsV1RyZEZ3OXdxNmw4SnFIU1pzYXlHNVdqLmpwZyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1773830702),
('NkaAijDeP74WTAJrocoXSV2H81PbYkowlReWQZcv', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieFRVOWNtMkRTTzFCM2xzU1lnVkNCaEc0ekszYU5SOW1jMVlmNVFuSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nYWxsZXJ5LWltYWdlL0t5cXNlbE9XbkpDWDlqZVRtUDhpSDFoRE80Qnh1VzJqTjdGUmFQUEcuanBnIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773830092),
('wW0CMSKJ6XRHCDhvza1NIGpMrKcnQC5IASatIpm8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGlaUDdqQ21leW9CZDRRS2R3aU8xQkczbGttRjU2ZDlERUdsbGJKYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1773825056),
('z2oMZpl07QDGj7pph2RAhgeLwcG16i7pUl8PxsPe', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMkMwY3pLVVVMT3B6T2ZQbVp2cXFUcWNSWlFaUEtTdDJQUGE2VzI0MSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nYWxsZXJ5LWltYWdlL09SbEV4czRvMDFmRVJWUE9SUG1EV0MxME9ydkI0OG5wQXI5Mm9nM1UuanBnIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773830092);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `department_id`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System', 'Admin', 'admin@smm.com', NULL, '$2y$12$IMdgs/TLj9EOqYAL9gblpe5/VR/pb8Yll5F4KPaZvpyYE1FiauHpi', NULL, 1, 'B9eZ1WnOJRjBgDO05SSIr00M6SVy2H1UDgHZKGl4pgjXQTTG5WcjnxbKvlne', '2026-03-16 11:51:19', '2026-03-16 12:54:35'),
(2, 'John', 'Doe', 'john.doe@smm.com', NULL, '$2y$12$5XD0pTbpde385Nh.E52M4uQDOwbqgf.uBKe0hLIOu1uI1W0z/MAga', NULL, 1, NULL, '2026-03-16 11:51:19', '2026-03-16 12:54:36'),
(3, 'Jane', 'Smith', 'jane.smith@smm.com', NULL, '$2y$12$/BjfZick31IxWHEK9m.FReG4PCAQKtH9ZVYAQDOd8MYBS3.Bg4v7O', NULL, 1, NULL, '2026-03-16 11:51:19', '2026-03-16 12:54:36'),
(4, 'ahmed', 'atoubi', 'ahmed@ssd', NULL, '$2y$12$WgVrnSR3cLzr3gMaxBj1We60.KHnXH.OyJzmbf/FVfLF7ROM6BN3y', NULL, 1, NULL, '2026-03-16 13:14:16', '2026-03-16 14:14:33'),
(5, 'ana', 'ana', 'ana@ssd.ma', NULL, '$2y$12$I5MatiFcMzNOmFpmXUwQwOPDpXuOM217aH0uJTXzKOoS.y5D.4onS', NULL, 1, NULL, '2026-03-16 14:17:28', '2026-03-16 14:19:43'),
(6, 'likan', 'likan', 'likan@sdd.com', NULL, '$2y$12$cggXoZo9WUj3dRGmf3o9YO7rNhwZ8LRFy69EbzpmVvCkDX.bAOW8y', 2, 1, NULL, '2026-03-17 09:41:30', '2026-03-17 09:41:30'),
(7, 'ljadidlikan@ssd.com', 'ljadidlikan@ssd.com', 'ljadidlikan@ssd.com', NULL, '$2y$12$NnNdMKsnJhPgVLMNFtc72uUHWW41AyI3kJQgd9VSt2Qbnqte4wPA2', NULL, 1, NULL, '2026-03-17 10:06:49', '2026-03-17 10:07:15'),
(8, 'artisan@artisan.ma', 'artisan@artisan.ma', 'artisan@artisan.ma', NULL, '$2y$12$7Z/Z/zAWFlRulbceaMhXVuc6.yJ1BHUg/l2w7tdbeRlq3UsQpYI3i', NULL, 1, NULL, '2026-03-17 10:12:17', '2026-03-17 10:12:50'),
(9, 'php', 'php', 'php@php.com', NULL, '$2y$12$jJowdzccIvXdmVvy.C2WpeLDep0grRPIyRLp0xo/CHTHnz0MwYv/m', 1, 1, NULL, '2026-03-17 10:35:49', '2026-03-17 10:35:49'),
(10, 'test', 'test', 'test@smm.com', NULL, '$2y$12$ZCi4YFLanwGjmbA2TsQjge0819edpX8hYMdS2FoC4zEdbImh5P0Ou', 2, 1, NULL, '2026-03-17 13:05:03', '2026-03-17 13:05:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_author_id_foreign` (`author_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applications_user_id_foreign` (`user_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`),
  ADD KEY `audit_logs_module_action_index` (`module`,`action`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departments_manager_id_index` (`manager_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_matricule_unique` (`matricule`),
  ADD KEY `employees_user_id_foreign` (`user_id`),
  ADD KEY `employees_department_id_status_index` (`department_id`,`status`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_images_gallery_id_foreign` (`gallery_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_author_id_foreign` (`author_id`),
  ADD KEY `news_is_published_published_at_index` (`is_published`,`published_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`),
  ADD KEY `permissions_module_index` (`module`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_created_by_foreign` (`created_by`);

--
-- Indexes for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `refresh_tokens_token_hash_unique` (`token_hash`),
  ADD KEY `refresh_tokens_user_id_expires_at_index` (`user_id`,`expires_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`role_id`,`user_id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_project_id_foreign` (`project_id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_department_id_foreign` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `gallery_images_gallery_id_foreign` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD CONSTRAINT `refresh_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
