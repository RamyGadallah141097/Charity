-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2022 at 11:35 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zakat`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `image`, `created_at`, `updated_at`) VALUES
(1, 'هشام محمد', 'admin@admin.com', '$2y$10$r4APdO9rlyZV7TnTiATH/uca1gJMmIRkmegvafKL8FfvS51ppwCGq', NULL, '2022-05-01 23:11:24', '2022-05-01 23:11:24'),
(2, 'احمد', 'ahmedtarekya100@gmail.com', '$2y$10$PDbYIxHdfLyvU.tlz5NCfuoEEKK.79QvUcwourzfTvas8kVFoRkzq', 'assets/uploads/admins/45241653381381.png', '2022-05-24 08:36:21', '2022-05-24 08:36:21'),
(3, 'mm', 'ahmedtarekya200@gmail.com', '$2y$10$dh9cMwA./6gIgOJeaJ2Ch.kWudIVDoOb7GkLjGOY2/U.DXOkW45.C', NULL, '2022-05-24 15:17:40', '2022-05-24 15:17:40');

-- --------------------------------------------------------

--
-- Table structure for table `childrens`
--

CREATE TABLE `childrens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lessons_cost` double DEFAULT 0,
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_cost` double DEFAULT 0,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `childrens`
--

INSERT INTO `childrens` (`id`, `user_id`, `name`, `school`, `lessons_cost`, `academic_year`, `monthly_cost`, `notes`, `created_at`, `updated_at`) VALUES
(16, 13, 'Linus Orr', 'Molestias eiusmod es', 19, '1976', 5, 'Occaecat labore dolo', '2022-05-24 14:21:05', '2022-05-24 14:21:05'),
(17, 14, 'Joel Justice', 'Neque ratione est ma', 18, '1975', 12, 'Ut perferendis ad ea', '2022-05-24 14:22:53', '2022-05-24 14:22:53'),
(18, 15, 'James Manning', 'Quo cumque cum elige', 63, '1989', 1, 'Est commodi illum o', '2022-05-24 14:23:59', '2022-05-24 14:23:59'),
(20, 19, 'Sopoline Hancock', 'Numquam qui sed sed', 85, '1995', 10, 'Quo consectetur quib', '2022-05-24 15:19:32', '2022-05-24 15:19:32'),
(21, 22, 'Dexter Mosley', 'Ut atque est id temp', 35, '1976', 1, 'Tempora quae minim s', '2022-05-25 14:30:37', '2022-05-25 14:30:37'),
(22, 24, 'Sebastian Hart', 'Exercitationem neces', 0, '1990', 7, 'Perferendis sed eius', '2022-05-25 14:30:37', '2022-05-25 14:30:37'),
(23, 20, 'Malachi Parks', 'Eos sit quo asperio', 0, '2018', 8, 'Proident qui dicta', '2022-05-25 14:30:37', '2022-05-25 14:30:37'),
(24, 21, 'Timon Hood', 'Est aperiam beatae', 4, '1989', 3, 'Et enim quaerat in q', '2022-05-25 14:31:33', '2022-05-25 14:31:33'),
(25, 22, 'Graham Ortega', 'Quaerat ab laborum q', 61, '1995', 7, 'Non reiciendis quia', '2022-05-26 08:07:37', '2022-05-26 08:07:37'),
(26, 23, 'Teagan Mccarthy', 'Ipsum sed anim aliq', 100, '1991', 8, 'Quia distinctio Vol', '2022-05-26 08:08:23', '2022-05-26 08:08:23'),
(27, 22, 'Scarlet Carlson', 'Nostrum deserunt qui', 64, '2012', 4, 'Ad alias laborum id', '2022-05-26 08:09:47', '2022-05-26 08:09:47'),
(28, 25, 'Channing Suarez', 'Voluptate in magna n', 99, '1979', 10, 'Nulla quis occaecat', '2022-05-26 08:10:22', '2022-05-26 08:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `name`, `phone`, `price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'محمود كامل علي ياسر', '0105487879', 700, 'تبرع الاستاذ محمود لاول مرة', '2022-05-25 09:06:37', '2022-05-25 09:06:37'),
(3, 'سعد عماد', '0104585878', 1500, NULL, '2022-05-25 09:40:53', '2022-05-25 09:53:05'),
(5, 'محمود الكومي', '010114878', 750, 'يتبرع كل اسبوع', '2022-05-25 09:40:53', '2022-05-25 09:53:05'),
(6, 'مصطفي باسم', '0111454788', 900, NULL, '2022-05-25 09:40:53', '2022-05-25 09:53:05'),
(7, 'احمد طارق', '01011485994', 1000, NULL, '2022-05-26 09:19:21', '2022-05-26 09:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_03_20_123415_create_admins_table', 1),
(6, '2022_03_20_134518_create_settings_table', 1),
(10, '2014_10_12_000000_create_users_table', 2),
(11, '2022_05_24_111749_create_childrens_table', 3),
(12, '2022_05_24_113536_create_patients_table', 4),
(13, '2022_05_25_100212_create_donors_table', 5),
(14, '2022_05_25_115249_create_subventions_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 means women and 1 means man',
  `treatment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment_pay_by` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_insurance` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 means women and 1 means man',
  `doctor_name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `name`, `type`, `treatment`, `treatment_pay_by`, `is_insurance`, `doctor_name`, `created_at`, `updated_at`) VALUES
(6, 13, 'Fritz Macias', 1, 'Excepturi itaque rer', 'Expedita aliqua Ten', 1, 'Xaviera Stafford', '2022-05-24 14:21:05', '2022-05-24 14:21:05'),
(7, 14, 'Stephen Nunez', 1, 'Mollitia soluta sequ', 'Quas tenetur nostrum', 0, 'Medge Booker', '2022-05-24 14:22:53', '2022-05-24 14:22:53'),
(8, 15, 'Molly Navarro', 1, 'Quas aliquip ratione', 'Voluptatem Aut est', 1, 'Rhea Little', '2022-05-24 14:23:59', '2022-05-24 14:23:59'),
(9, 19, 'Hilel Sweeney', 0, 'Est natus non sed na', 'Iste dolor voluptas', 0, 'Blair Marsh', '2022-05-24 15:19:32', '2022-05-24 15:19:32'),
(10, 20, 'Dahlia Dudley', 0, 'Dolor quis dolorum e', 'Error ut similique s', 1, 'Margaret Valdez', '2022-05-25 14:30:37', '2022-05-25 14:30:37'),
(11, 21, 'Colin Serrano', 1, 'Ea deserunt atque ea', 'Debitis architecto i', 0, 'Garrett Benjamin', '2022-05-25 14:31:33', '2022-05-25 14:31:33'),
(12, 22, 'Fuller Howard', 1, 'Ab ipsum doloribus', 'Quis anim tempora au', 1, 'Keane Sanchez', '2022-05-26 08:07:37', '2022-05-26 08:07:37'),
(13, 23, 'Maxine Delaney', 1, 'Dolore dolor volupta', 'Non cillum ad dolore', 0, 'Vielka Contreras', '2022-05-26 08:08:23', '2022-05-26 08:08:23'),
(14, 24, 'Oprah Stuart', 1, 'Enim doloribus moles', 'Culpa aut sunt nulla', 0, 'Uma Mcdonald', '2022-05-26 08:09:47', '2022-05-26 08:09:47'),
(15, 25, 'Orson Christian', 1, 'Et laboris quaerat a', 'Magna sed esse eos', 0, 'Eagan Mullen', '2022-05-26 08:10:22', '2022-05-26 08:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vat_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `title`, `logo`, `vat_number`, `address`, `sub_address`, `branch`, `section`, `created_at`, `updated_at`) VALUES
(1, 'لجنة الزكاة بكفر طنبدي', 'assets/uploads/91491653550238.png', '3/4859', 'كفر طنبدي - شارع البحر بعد صيدلية ناصف اسفل الاستاذ علي داود المحامي', 'شبين الكوم - المنوفية', 'فرع شبين الكوم', 'قطاع التكافل / الادراة العامة للزكاة', '2022-05-24 08:28:10', '2022-05-26 07:31:56');

-- --------------------------------------------------------

--
-- Table structure for table `subventions`
--

CREATE TABLE `subventions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `price` double NOT NULL,
  `type` enum('monthly','once') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'once',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subventions`
--

INSERT INTO `subventions` (`id`, `user_id`, `price`, `type`, `created_at`, `updated_at`) VALUES
(3, 19, 700, 'once', '2022-05-25 11:43:32', '2022-05-25 11:51:22'),
(4, 14, 600, 'monthly', '2022-05-25 11:43:58', '2022-05-25 11:51:30'),
(10, 24, 3500, 'monthly', '2022-05-26 08:11:05', '2022-05-26 08:11:43'),
(11, 23, 900, 'monthly', '2022-05-26 08:11:35', '2022-05-26 08:11:35'),
(12, 20, 300, 'monthly', '2022-05-26 08:11:53', '2022-05-26 08:11:53'),
(13, 21, 700, 'monthly', '2022-05-26 08:12:03', '2022-05-26 08:12:03'),
(14, 22, 1000, 'monthly', '2022-05-26 08:12:16', '2022-05-26 08:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `husband_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wife_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `husband_birthday` date NOT NULL,
  `wife_birthday` date DEFAULT NULL,
  `status` enum('new','preparing','accepted','refused') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `social_status` enum('single','married','divorced','widow') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'married',
  `nearest_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` double DEFAULT 0,
  `pension` double DEFAULT 0,
  `insurance` double DEFAULT 0,
  `dignity` double DEFAULT 0,
  `trade` double DEFAULT 0,
  `pillows` double DEFAULT 0,
  `other` double DEFAULT 0,
  `gross_income` double DEFAULT 0,
  `rent` double DEFAULT 0,
  `gas` double DEFAULT 0,
  `debt` double DEFAULT 0,
  `water` double DEFAULT 0,
  `treatment` double DEFAULT 0,
  `electricity` double DEFAULT 0,
  `association` double DEFAULT 0,
  `food` double DEFAULT 0,
  `study` double DEFAULT 0,
  `total_expenses` double DEFAULT 0,
  `has_property` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 means he hasn''t any property',
  `has_savings_book` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0 means he hasn''t a saving book',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `husband_name`, `wife_name`, `husband_birthday`, `wife_birthday`, `status`, `social_status`, `nearest_phone`, `work_type`, `address`, `salary`, `pension`, `insurance`, `dignity`, `trade`, `pillows`, `other`, `gross_income`, `rent`, `gas`, `debt`, `water`, `treatment`, `electricity`, `association`, `food`, `study`, `total_expenses`, `has_property`, `has_savings_book`, `created_at`, `updated_at`) VALUES
(13, 'منصور خالد', 'Marny Gentry', '2007-03-10', '2002-04-22', 'refused', 'widow', '+1 (663) 518-5316', 'Et praesentium non d', 'Quos dicta in id ad', 76, 40, 79, 83, 11, 43, 82, 414, 91, 10, 37, 50, 0, 52, 17, 1, 35, 293, '1', '0', '2022-05-24 14:21:05', '2022-05-25 08:40:53'),
(14, 'شوقي محمد', 'John Cline', '2017-12-17', '2011-05-22', 'accepted', 'divorced', '+1 (932) 856-3638', 'Sequi dolorem et qui', 'Esse minim ut volup', 97, 31, 20, 39, 53, 94, 28, 362, 4, 99, 5, 73, 0, 34, 10, 29, 51, 305, '1', '0', '2022-05-24 14:22:53', '2022-05-24 14:22:53'),
(15, 'أحمد محمد', 'Nigel Welch', '1992-07-18', '1974-01-18', 'preparing', 'married', '+1 (919) 169-4109', 'Quia dolorem deserun', 'Lorem omnis rerum qu', 11, 33, 77, 39, 55, 7, 20, 242, 31, 14, 35, 44, 0, 31, 69, 40, 2, 266, '0', '1', '2022-05-24 14:23:59', '2022-05-25 08:40:49'),
(18, 'ناصر عصام', 'Reagan Mayo', '2015-08-11', '2008-12-03', 'accepted', 'single', '+1 (126) 176-9599', 'Duis qui quia asperi', 'Voluptatem voluptate', 83, 22, 53, 30, 65, 52, 3, 308, 2, 63, 92, 90, NULL, 42, 8, 94, 69, 460, '0', '0', '2022-05-24 14:28:00', '2022-05-26 08:07:52'),
(19, 'مهند مندور', 'Suki Guzman', '1973-04-01', '2005-09-17', 'accepted', 'divorced', '+1 (909) 517-4704', 'Tempora reprehenderi', 'Ullamco laborum exer', 14, 73, 65, 57, 13, 95, 81, 398, 25, 91, 15, 10, 0, 85, 6, 81, 53, 366, '0', '0', '2022-05-24 15:19:32', '2022-05-25 11:23:19'),
(20, 'علي جلال', 'Dieter Boyer', '2008-10-14', '2007-03-05', 'accepted', 'married', '+1 (289) 559-7725', 'Esse vero in sint s', 'Culpa quia fuga Ut', 64, 93, 3, 96, 6, 45, 75, 382, 35, 49, 96, 100, 0, 78, 27, 99, 25, 509, '0', '1', '2022-05-25 14:30:37', '2022-05-25 14:34:12'),
(21, 'جمال سعد', 'Althea Marsh', '1997-05-21', '1991-06-10', 'accepted', 'widow', '+1 (875) 592-9214', 'Quae praesentium odi', 'Consequatur pariatur', 24, 98, 33, 41, 33, 10, 7, 246, 62, 72, 62, 2, 0, 8, 32, 58, 2, 298, '1', '0', '2022-05-25 14:31:33', '2022-05-25 14:34:08'),
(22, 'كامل علي', 'فايزة محمد', '2020-10-15', '2022-02-05', 'accepted', 'married', '+1 (504) 909-3021', 'Et pariatur Nihil i', 'Eum harum praesentiu', 29, 27, 100, 21, 3, 46, 53, 279, 20, 66, 18, 31, 0, 48, 61, 6, 3, 253, '0', '1', '2022-05-26 08:07:37', '2022-05-26 08:07:47'),
(23, 'محمد فوزي', 'امل ماهر', '2004-05-28', '1988-10-23', 'accepted', 'widow', '+1 (421) 753-2032', 'Consectetur aute qu', 'Laudantium dolor qu', 13, 14, 43, 77, 12, 64, 67, 290, 63, 5, 15, 3, 0, 29, 8, 69, 38, 230, '1', '0', '2022-05-26 08:08:23', '2022-05-26 08:09:11'),
(24, 'محمود جمال', 'اماني محمد', '2008-12-20', '2016-07-18', 'accepted', 'married', '+1 (877) 886-4651', 'Totam sed labore quo', 'Sunt nulla quas ips', 64, 51, 11, 71, 6, 17, 81, 301, 1, 35, 27, 80, 0, 81, 15, 41, 54, 334, '0', '1', '2022-05-26 08:09:47', '2022-05-26 08:10:32'),
(25, 'ابراهيم محمد', 'مي علي', '1982-10-07', '1991-10-26', 'new', 'widow', '+1 (581) 794-4359', 'Soluta temporibus el', 'Amet consectetur sa', 87, 60, 38, 5, 30, 38, 85, 343, 16, 67, 11, 71, 0, 30, 3, 28, 94, 320, '0', '0', '2022-05-26 08:10:22', '2022-05-26 08:10:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `childrens`
--
ALTER TABLE `childrens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `childrens_user_id_foreign` (`user_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patients_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subventions`
--
ALTER TABLE `subventions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subventions_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `childrens`
--
ALTER TABLE `childrens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subventions`
--
ALTER TABLE `subventions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `childrens`
--
ALTER TABLE `childrens`
  ADD CONSTRAINT `childrens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subventions`
--
ALTER TABLE `subventions`
  ADD CONSTRAINT `subventions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
