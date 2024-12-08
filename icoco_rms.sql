-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 01:25 PM
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
  `booking_start_date` date NOT NULL,
  `booking_end_date` date NOT NULL,
  `booking_duration` int(11) NOT NULL,
  `booking_status_id` int(11) DEFAULT NULL,
  `booking_participants` int(11) NOT NULL,
  `booking_original_price` decimal(10,2) NOT NULL,
  `booking_grand_total` decimal(10,2) NOT NULL,
  `booking_guest_id` int(11) DEFAULT NULL,
  `booking_venue_id` int(11) DEFAULT NULL,
  `booking_discount` varchar(255) DEFAULT NULL,
  `booking_payment_method` varchar(50) DEFAULT NULL,
  `booking_payment_reference` varchar(255) NOT NULL,
  `booking_payment_status_id` int(11) DEFAULT NULL,
  `booking_cancellation_reason` text NOT NULL,
  `booking_service_fee` decimal(10,2) NOT NULL,
  `booking_created_at` datetime NOT NULL,
  `booking_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_start_date`, `booking_end_date`, `booking_duration`, `booking_status_id`, `booking_participants`, `booking_original_price`, `booking_grand_total`, `booking_guest_id`, `booking_venue_id`, `booking_discount`, `booking_payment_method`, `booking_payment_reference`, `booking_payment_status_id`, `booking_cancellation_reason`, `booking_service_fee`, `booking_created_at`, `booking_updated_at`) VALUES
(34, '2024-12-05', '2024-12-07', 2, 4, 30, 9000.00, 14175.00, 10, 70, 'none', 'G-cash', '123123123123', 2, '', 675.00, '0000-00-00 00:00:00', '2024-12-08 11:42:09'),
(35, '2024-12-25', '2024-12-31', 6, 2, 10, 27000.00, 29175.00, 10, 70, 'none', 'G-cash', '474fdss45dfsd7gad', 2, '', 675.00, '0000-00-00 00:00:00', '2024-12-08 12:20:10');

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
(135, 10, 70, '2024-12-07 01:34:22', '2024-12-07 01:34:22');

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

-- --------------------------------------------------------

--
-- Table structure for table `mandatory_discount`
--

CREATE TABLE `mandatory_discount` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `discount_type` enum('PWD','Senior Citizen') NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `discount_id` varchar(50) NOT NULL,
  `card_image` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `discount_value` decimal(5,2) NOT NULL DEFAULT 20.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `venue_id`, `rating`, `review`, `created_at`, `updated_at`) VALUES
(9, 10, 70, 5, 'Great Experience', '2024-12-08 11:42:35', '2024-12-08 11:42:35');

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
  `profile_pic` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `middlename`, `sex_id`, `user_type_id`, `birthdate`, `contact_number`, `address`, `profile_pic`, `bio`, `email`, `password`, `created_at`, `updated_at`) VALUES
(9, 'Joevin', 'Ansoc', 'C', 1, 3, '2003-09-28', '09950331778', 'Forbes Hill, Pasonanca, Zamboanga City, Zamboanga Peninsula, 7000, Pilipinas', NULL, NULL, 'joevinansoc870@gmail.com', '$2y$10$1EK9IlnEj8A.l3hL25hiDulJ5VwdEOvjN/ymV2JEWv3o3CA9D5yM.', '2024-12-06 12:34:32', '2024-12-06 13:01:12'),
(10, 'Joevin', 'Ansoc', 'C', 1, 1, '2003-09-28', '09950331778', '2nd Street, Riverdale Village, Santa Maria, Zamboanga City, Zamboanga Peninsula, 7000, Pilipinas', NULL, NULL, 'joevinansoc871@gmail.com', '$2y$10$6P6/WglbIY5WTOuJZD6.BOSpUUrD.j4lttTbr9VoAUjIPF2/iU.UK', '2024-12-06 13:02:06', '2024-12-06 14:12:04');

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
  `rules` longtext DEFAULT NULL,
  `entrance` int(11) NOT NULL,
  `cleaning` int(11) NOT NULL,
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

INSERT INTO `venues` (`id`, `name`, `description`, `location`, `price`, `capacity`, `amenities`, `rules`, `entrance`, `cleaning`, `time_inout`, `host_id`, `status_id`, `availability_id`, `created_at`, `updated_at`) VALUES
(70, 'Grandia Resort', 'A versatile and elegantly designed space featuring modern amenities and customizable layouts to suit various needs. With its convenient location, ample parking, and dedicated staff, it ensures a seamless and memorable experience for all occasions.', 'San Jose Road, San Jose Cawa-cawa, Baliwasan, Zamboanga City, Zamboanga Peninsula, 7000, Pilipinas', 4500.00, 30, '[\"Wifi\",\"TV\"]', '[\"No smoking\",\"No parties or events\",\"No Drugs\"]', 150, 0, '{\"check_in\":\"07:00\",\"check_out\":\"17:00\"}', 10, 2, 1, '2024-12-06 14:26:32', '2024-12-06 14:27:00');

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
(106, 70, '/venue_image_uploads/675309980d67a.jpg', '2024-12-06 14:26:32'),
(107, 70, '/venue_image_uploads/675309980d9ee.jpg', '2024-12-06 14:26:32'),
(108, 70, '/venue_image_uploads/675309980ddba.jpg', '2024-12-06 14:26:32'),
(109, 70, '/venue_image_uploads/675309980e161.jpg', '2024-12-06 14:26:32'),
(110, 70, '/venue_image_uploads/675309980e55e.jpg', '2024-12-06 14:26:32'),
(111, 70, '/venue_image_uploads/675309980e88b.jpg', '2024-12-06 14:26:32'),
(112, 70, '/venue_image_uploads/675309980ee4d.jpg', '2024-12-06 14:26:32');

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
-- Indexes for table `mandatory_discount`
--
ALTER TABLE `mandatory_discount`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`userId`);

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
  ADD KEY `fk_host_id` (`host_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `host_application`
--
ALTER TABLE `host_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `host_application_status_sub`
--
ALTER TABLE `host_application_status_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mandatory_discount`
--
ALTER TABLE `mandatory_discount`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sex_sub`
--
ALTER TABLE `sex_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_types_sub`
--
ALTER TABLE `user_types_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `venue_availability_sub`
--
ALTER TABLE `venue_availability_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `venue_images`
--
ALTER TABLE `venue_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

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
-- Constraints for table `mandatory_discount`
--
ALTER TABLE `mandatory_discount`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_status_id` FOREIGN KEY (`status_id`) REFERENCES `venue_status_sub` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `venue_images`
--
ALTER TABLE `venue_images`
  ADD CONSTRAINT `fk_venue_id` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
