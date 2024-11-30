-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 04:49 PM
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
-- Database: `icoco_rms`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_start_date` date DEFAULT NULL,
  `booking_end_date` date DEFAULT NULL,
  `booking_duration` int(11) DEFAULT NULL,
  `booking_status_id` int(11) DEFAULT NULL,
  `booking_participants` int(11) DEFAULT NULL,
  `booking_original_price` decimal(10,2) DEFAULT NULL,
  `booking_grand_total` decimal(10,2) DEFAULT NULL,
  `booking_guest_id` int(11) DEFAULT NULL,
  `booking_venue_id` int(11) DEFAULT NULL,
  `booking_discount` varchar(255) DEFAULT NULL,
  `booking_payment_method` varchar(50) DEFAULT NULL,
  `booking_payment_reference` varchar(255) DEFAULT NULL,
  `booking_payment_status_id` int(11) DEFAULT NULL,
  `booking_cancellation_reason` text DEFAULT NULL,
  `booking_service_fee` int(11) NOT NULL,
  `booking_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_start_date`, `booking_end_date`, `booking_duration`, `booking_status_id`, `booking_participants`, `booking_original_price`, `booking_grand_total`, `booking_guest_id`, `booking_venue_id`, `booking_discount`, `booking_payment_method`, `booking_payment_reference`, `booking_payment_status_id`, `booking_cancellation_reason`, `booking_service_fee`, `booking_created_at`, `booking_updated_at`) VALUES
(15, '2024-11-30', '2024-12-05', 5, 1, 100, 26250.00, 21950.00, 2, 52, 'SAVE20', 'G-cash', 'sa6dsa4f6safsa', 1, '', 788, '2024-11-29 08:31:15', '2024-11-29 08:31:15'),
(16, '2024-11-30', '2024-12-05', 5, 1, 100, 26250.00, 21950.00, 2, 52, 'SAVE20', 'G-cash', 'asdsa654sa5fas', 1, '', 788, '2024-11-29 08:34:51', '2024-11-29 08:34:51'),
(17, '2024-11-30', '2024-12-05', 5, 1, 100, 26250.00, 19206.25, 2, 52, 'SAVE30', 'G-cash', 'a154as6fasfasf', 1, '', 788, '2024-11-29 08:37:53', '2024-11-29 08:37:53'),
(18, '2024-11-30', '2024-12-05', 5, 1, 100, 26250.00, 19206.25, 2, 52, 'SAVE30', 'G-cash', 'w1safas412', 1, '', 788, '2024-11-29 08:39:32', '2024-11-29 08:39:32'),
(23, '2024-11-30', '2024-12-24', 24, 1, 100, 126000.00, 126000.00, 2, 52, 'none', 'PayMaya', 'sadas5fsa6f56', 1, '', 788, '2024-11-29 09:03:06', '2024-11-29 09:03:06'),
(24, '2024-12-04', '2024-12-20', 16, 1, 100, 84000.00, 58800.00, 2, 52, 'SAVE30', 'G-cash', '484654', 1, '', 788, '2024-11-29 09:04:44', '2024-11-29 09:04:44'),
(25, '2024-11-30', '2024-12-24', 24, 1, 100, 126000.00, 88200.00, 2, 52, 'SAVE30', 'G-cash', 'aasdasf5as6fasf', 1, '', 788, '2024-11-29 09:31:57', '2024-11-29 09:31:57');

-- --------------------------------------------------------

--
-- Table structure for table `bookings_status_sub`
--

CREATE TABLE `bookings_status_sub` (
  `id` int(11) NOT NULL,
  `booking_status_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings_status_sub`
--

INSERT INTO `bookings_status_sub` (`id`, `booking_status_name`) VALUES
(1, 'Pending'),
(2, 'Confirmed'),
(3, 'Cancelled'),
(4, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `venueId` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `userId`, `venueId`, `created_at`, `updated_at`) VALUES
(85, 5, 52, '2024-11-25 01:14:06', '2024-11-25 01:14:06');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `discount_code` varchar(255) DEFAULT NULL,
  `discount_type` enum('flat','percentage') DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `expiration_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `venue_id`, `discount_code`, `discount_type`, `discount_value`, `expiration_date`) VALUES
(1, NULL, 'SAVE10', 'percentage', 10.00, '2024-12-25 20:40:37'),
(2, NULL, 'SAVE20', 'percentage', 20.00, '2024-12-25 20:40:37'),
(3, NULL, 'SAVE30', 'percentage', 30.00, '2024-12-25 20:40:37'),
(4, NULL, 'none', 'percentage', 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `host_application`
--

CREATE TABLE `host_application` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `birthdate` varchar(50) NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `host_application`
--

INSERT INTO `host_application` (`id`, `userId`, `fullname`, `address`, `birthdate`, `status_id`) VALUES
(5, 2, 'Ansoc, Joevin C.', 'Veterans Avenue, Zone III, Santa Catalina, Zamboanga City, Zamboanga Peninsula, 7000, Philippines', '2003-09-28', 2),
(6, 3, 'Alejandro, Jorica Sher L.', 'Veterans Avenue, Zone III, Santa Catalina, Zamboanga City, Zamboanga Peninsula, 7000, Philippines', '2004-06-17', 2);

-- --------------------------------------------------------

--
-- Table structure for table `host_application_status_sub`
--

CREATE TABLE `host_application_status_sub` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `host_application_status_sub`
--

INSERT INTO `host_application_status_sub` (`id`, `name`) VALUES
(1, 'Pending'),
(2, 'Approved'),
(3, 'Declined');

-- --------------------------------------------------------

--
-- Table structure for table `host_id_images`
--

CREATE TABLE `host_id_images` (
  `id` int(11) NOT NULL,
  `idOne_type` varchar(100) NOT NULL,
  `idOne_image_url` varchar(255) NOT NULL,
  `idTwo_type` varchar(100) NOT NULL,
  `idTwo_image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `host_id_images`
--

INSERT INTO `host_id_images` (`id`, `idOne_type`, `idOne_image_url`, `idTwo_type`, `idTwo_image_url`, `created_at`) VALUES
(5, 'Philippine Passport', '/host_id_image/67321f17c3abe.jpg', 'Driver&#039;s License', '/host_id_image/67321f17c3eb9.jpg', '2024-11-11 15:13:27'),
(6, 'Philippine Passport', '/host_id_image/673847894a977.jpg', 'National ID', '/host_id_image/673847894b255.jpg', '2024-11-16 07:19:37');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method_sub`
--

CREATE TABLE `payment_method_sub` (
  `id` int(11) NOT NULL,
  `payment_method_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_method_sub`
--

INSERT INTO `payment_method_sub` (`id`, `payment_method_name`) VALUES
(1, 'G-cash'),
(2, 'PayMaya');

-- --------------------------------------------------------

--
-- Table structure for table `payment_status_sub`
--

CREATE TABLE `payment_status_sub` (
  `id` int(11) NOT NULL,
  `payment_status_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_status_sub`
--

INSERT INTO `payment_status_sub` (`id`, `payment_status_name`) VALUES
(1, 'Pending'),
(2, 'Paid'),
(3, 'Failed');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `review` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sex_sub`
--

CREATE TABLE `sex_sub` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sex_sub`
--

INSERT INTO `sex_sub` (`id`, `name`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `sex_id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `middlename`, `sex_id`, `user_type_id`, `birthdate`, `contact_number`, `address`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Joevin', 'Ansoc', 'C', 1, 3, '2003-09-28', '09053258512', 'Veterans Avenue, Zone III, Santa Catalina, Zamboanga City, Zamboanga Peninsula, 7000, Philippines', 'joevinansoc870@gmail.com', '$2y$10$oZOd4iRtyixA/HUBET5Wmuj.6I6jvJ911JtmUBFAUjlOIb.ckS4Km', '2024-11-06 18:18:45', '2024-11-11 00:48:03'),
(2, 'Joevin', 'Ansoc', 'C', 1, 1, '2003-09-28', '09053258512', 'Veterans Avenue, Zone III, Santa Catalina, Zamboanga City, Zamboanga Peninsula, 7000, Philippines', 'joevinansoc871@gmail.com', '$2y$10$l365/PHYVfu0zelT5t9YNO6R4ihGg6oOGNMKE2OS8hGTPRBV3za2e', '2024-11-07 03:19:46', '2024-11-23 15:07:35'),
(3, 'Jorica Sher', 'Alejandro', 'L', 2, 1, '2004-06-17', '09053258512', 'Veterans Avenue, Zone III, Santa Catalina, Zamboanga City, Zamboanga Peninsula, 7000, Philippines', 'joricasheralejandro@yahoo.com', '$2y$10$09zM7efNo0nYI7TTKCdc3u/ByH7HmMKrBZq8bif1QfODcL9VJ4Isa', '2024-11-09 14:50:31', '2024-11-18 07:38:43'),
(4, 'Joreen Jeay', 'Alejandro', 'L', 2, 2, '2004-06-17', '09954687923', 'Veterans Avenue, Zone III, Santa Catalina, Zamboanga City, Zamboanga Peninsula, 7000, Philippines', 'joreenjeayalejandro@yahoo.com', '$2y$10$.7iXs5OGrAZ3jHdADTyd2.omDREXvaRgwtCv3hcs/O/DO/YhYMUsu', '2024-11-09 14:53:25', '2024-11-11 00:47:43'),
(5, 'Ray Vincent', 'Concepcion', 'D.S', 1, 2, '2003-04-04', '09950331778', 'Veterans Avenue, Zone III, Santa Catalina, Zamboanga City, Zamboanga Peninsula, 7000, Philippines', 'rayvincent@gmail.com', '$2y$10$QdJ9AkFqFm.gEEP3Vs/d9.oNVc0HbNw.Iw.z0qGJMp5IQvuj1KS2a', '2024-11-11 00:46:32', '2024-11-11 00:46:32'),
(6, 'Irene', 'Ansoc', 'Concepcion', 2, 3, '1984-12-06', '09253258512', 'Pasonanca Road, Luyahan Urban Poor Subdivision, Pasonanca, Zamboanga City, Zamboanga Peninsula, 7000, Philippines', 'ireneansoc@gmail.com', '$2y$10$vPPD8NCxrjfSk3flTVjP9ebbYzKprO361ajYn6852tbGncj9.CICu', '2024-11-16 14:26:53', '2024-11-16 14:26:53');

-- --------------------------------------------------------

--
-- Table structure for table `user_types_sub`
--

CREATE TABLE `user_types_sub` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_types_sub`
--

INSERT INTO `user_types_sub` (`id`, `name`) VALUES
(1, 'Host'),
(2, 'Guest'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`amenities`)),
  `rules` longtext NOT NULL,
  `entrance` int(11) NOT NULL,
  `cleaning` int(11) NOT NULL,
  `venue_tag` int(11) NOT NULL,
  `time_inout` text NOT NULL,
  `host_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `availability_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `name`, `description`, `location`, `price`, `capacity`, `amenities`, `rules`, `entrance`, `cleaning`, `venue_tag`, `time_inout`, `host_id`, `status_id`, `availability_id`, `created_at`, `updated_at`) VALUES
(52, 'Marcian Hotel', 'A versatile and elegantly designed space featuring modern amenities and customizable layouts to suit various needs. With its convenient location, ample parking, and dedicated staff, it ensures a seamless and memorable experience for all occasions.', 'Marcian Garden Hotel, Governor Camins Avenue, Canelar, Baliwasan, Zamboanga City, Zamboanga Peninsula, 7000, Pilipinas', 5250.00, 100, '[\"Wifi\",\"TV\",\"Pool\",\"Hot tub\",\"Smoke alarm\",\"First aid kit\"]', '[\"No smoking\",\"No outside drinks\"]', 0, 400, 1, '{\"check_in\":\"14:00\",\"check_out\":\"12:00\"}', 2, 2, 1, '2024-11-24 14:01:42', '2024-11-24 14:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `venue_availability_sub`
--

CREATE TABLE `venue_availability_sub` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue_availability_sub`
--

INSERT INTO `venue_availability_sub` (`id`, `name`) VALUES
(1, 'Available'),
(2, 'Not Available');

-- --------------------------------------------------------

--
-- Table structure for table `venue_images`
--

CREATE TABLE `venue_images` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue_images`
--

INSERT INTO `venue_images` (`id`, `venue_id`, `image_url`, `created_at`) VALUES
(89, 52, '/venue_image_uploads/674331c60de90.jpg', '2024-11-24 14:01:42'),
(90, 52, '/venue_image_uploads/674331c60e257.jpg', '2024-11-24 14:01:42'),
(91, 52, '/venue_image_uploads/674331c60e4ff.jpg', '2024-11-24 14:01:42'),
(92, 52, '/venue_image_uploads/674331c60e84f.jpg', '2024-11-24 14:01:42'),
(93, 52, '/venue_image_uploads/674331c60eb72.jpg', '2024-11-24 14:01:42'),
(94, 52, '/venue_image_uploads/674331c612c42.jpg', '2024-11-24 14:01:42');

-- --------------------------------------------------------

--
-- Table structure for table `venue_status_sub`
--

CREATE TABLE `venue_status_sub` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue_status_sub`
--

INSERT INTO `venue_status_sub` (`id`, `name`) VALUES
(1, 'Pending'),
(2, 'Approved'),
(3, 'Declined');

-- --------------------------------------------------------

--
-- Table structure for table `venue_tag_sub`
--

CREATE TABLE `venue_tag_sub` (
  `id` int(11) NOT NULL,
  `tag_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue_tag_sub`
--

INSERT INTO `venue_tag_sub` (`id`, `tag_name`) VALUES
(1, 'Corporate Events'),
(2, 'Reception Hall'),
(3, 'Intimate Gatherings'),
(4, 'Outdoor');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_guest_id` (`booking_guest_id`),
  ADD KEY `booking_venue_id` (`booking_venue_id`),
  ADD KEY `booking_status_id` (`booking_status_id`),
  ADD KEY `booking_payment_status_id` (`booking_payment_status_id`),
  ADD KEY `booking_payment_method` (`booking_payment_method`),
  ADD KEY `booking_discount` (`booking_discount`);

--
-- Indexes for table `bookings_status_sub`
--
ALTER TABLE `bookings_status_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `venueId` (`venueId`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discount_code` (`discount_code`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `host_application`
--
ALTER TABLE `host_application`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userId` (`userId`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `host_application_status_sub`
--
ALTER TABLE `host_application_status_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `host_id_images`
--
ALTER TABLE `host_id_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_method_sub`
--
ALTER TABLE `payment_method_sub`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_payment_method_name` (`payment_method_name`);

--
-- Indexes for table `payment_status_sub`
--
ALTER TABLE `payment_status_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_user_id` (`user_id`),
  ADD KEY `fk_reviews_venue_id` (`venue_id`);

--
-- Indexes for table `sex_sub`
--
ALTER TABLE `sex_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_sex_id` (`sex_id`),
  ADD KEY `fk_user_type_id` (`user_type_id`);

--
-- Indexes for table `user_types_sub`
--
ALTER TABLE `user_types_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_availability_id` (`availability_id`),
  ADD KEY `fk_status_id` (`status_id`),
  ADD KEY `fk_host_id` (`host_id`),
  ADD KEY `fk_venue_tag_id` (`venue_tag`);

--
-- Indexes for table `venue_availability_sub`
--
ALTER TABLE `venue_availability_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venue_images`
--
ALTER TABLE `venue_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_venue_id` (`venue_id`);

--
-- Indexes for table `venue_status_sub`
--
ALTER TABLE `venue_status_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venue_tag_sub`
--
ALTER TABLE `venue_tag_sub`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `host_application`
--
ALTER TABLE `host_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `host_application_status_sub`
--
ALTER TABLE `host_application_status_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_method_sub`
--
ALTER TABLE `payment_method_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_status_sub`
--
ALTER TABLE `payment_status_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sex_sub`
--
ALTER TABLE `sex_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_types_sub`
--
ALTER TABLE `user_types_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `venue_availability_sub`
--
ALTER TABLE `venue_availability_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `venue_images`
--
ALTER TABLE `venue_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `venue_status_sub`
--
ALTER TABLE `venue_status_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `venue_tag_sub`
--
ALTER TABLE `venue_tag_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`booking_guest_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`booking_venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`booking_status_id`) REFERENCES `bookings_status_sub` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_6` FOREIGN KEY (`booking_payment_status_id`) REFERENCES `payment_status_sub` (`id`),
  ADD CONSTRAINT `bookings_ibfk_8` FOREIGN KEY (`booking_payment_method`) REFERENCES `payment_method_sub` (`payment_method_name`),
  ADD CONSTRAINT `bookings_ibfk_9` FOREIGN KEY (`booking_discount`) REFERENCES `discounts` (`discount_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`venueId`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `host_application`
--
ALTER TABLE `host_application`
  ADD CONSTRAINT `host_application_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `host_application_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `host_application_status_sub` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `host_id_images`
--
ALTER TABLE `host_id_images`
  ADD CONSTRAINT `host_id_images_ibfk_1` FOREIGN KEY (`id`) REFERENCES `host_application` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_venue_id` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_sex_id` FOREIGN KEY (`sex_id`) REFERENCES `sex_sub` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_type_id` FOREIGN KEY (`user_type_id`) REFERENCES `user_types_sub` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `venues`
--
ALTER TABLE `venues`
  ADD CONSTRAINT `fk_availability_id` FOREIGN KEY (`availability_id`) REFERENCES `venue_availability_sub` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_host_id` FOREIGN KEY (`host_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_status_id` FOREIGN KEY (`status_id`) REFERENCES `venue_status_sub` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_venue_tag_id` FOREIGN KEY (`venue_tag`) REFERENCES `venue_tag_sub` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `venue_images`
--
ALTER TABLE `venue_images`
  ADD CONSTRAINT `fk_venue_id` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
