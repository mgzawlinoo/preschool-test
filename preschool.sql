-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 21, 2025 at 12:35 PM
-- Server version: 8.0.41-0ubuntu0.22.04.1
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `preschool`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admins`
--

CREATE TABLE `Admins` (
  `admin_id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Admins`
--

INSERT INTO `Admins` (`admin_id`, `user_id`, `name`, `photo`, `phone`, `address`) VALUES
(1, 1, 'admin', '1.png', '092345678', 'sample address');

-- --------------------------------------------------------

--
-- Table structure for table `Attendance`
--

CREATE TABLE `Attendance` (
  `attendance_id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Late') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Classes`
--

CREATE TABLE `Classes` (
  `class_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `class_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fees` int NOT NULL,
  `age_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_students` int DEFAULT NULL,
  `schedule` text COLLATE utf8mb4_unicode_ci,
  `start_date` date NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Classes`
--

INSERT INTO `Classes` (`class_id`, `teacher_id`, `class_name`, `fees`, `age_group`, `max_students`, `schedule`, `start_date`, `status`) VALUES
(1, 29, 'Grade 1', 2000000, '4-5 Years', 10, 'Mon-Fri, 9:00 AM - 12:00 PM', '2025-02-01', 'active'),
(2, 29, 'Grade 2', 3000000, '5-6 Years', 10, 'Mon-Fri, 9:00 AM - 12:00 PM', '2025-02-01', 'active'),
(3, 29, 'Grade 3', 4000000, '6-7 Years', 10, 'Mon-Fri, 9:00 AM - 12:00 PM', '2025-02-01', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE `Events` (
  `event_id` int NOT NULL,
  `event_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `location` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Gallery`
--

CREATE TABLE `Gallery` (
  `media_id` int NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `event_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Messages`
--

CREATE TABLE `Messages` (
  `message_id` int NOT NULL,
  `sender_id` int DEFAULT NULL,
  `receiver_id` int DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Parents`
--

CREATE TABLE `Parents` (
  `parent_id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Parents`
--

INSERT INTO `Parents` (`parent_id`, `user_id`, `name`, `photo`, `phone`, `address`) VALUES
(3, 7, 'Daw Aye Aye', '7.png', '+1 (912) 594-4514', 'Est a doloremque sin'),
(4, 9, 'U Ko Ko', '9.png', '23424242423', 'sample address');

-- --------------------------------------------------------

--
-- Table structure for table `Payments`
--

CREATE TABLE `Payments` (
  `payment_id` int NOT NULL,
  `student_id` int NOT NULL,
  `class_id` int NOT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `payment_method` enum('cash','kpay','bank transfer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('unpaid','paid','checking') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Payments`
--

INSERT INTO `Payments` (`payment_id`, `student_id`, `class_id`, `payment_date`, `amount`, `payment_method`, `payment_status`, `photo`, `description`) VALUES
(1, 16, 1, NULL, NULL, NULL, 'unpaid', NULL, NULL),
(2, 17, 1, NULL, NULL, NULL, 'unpaid', NULL, NULL),
(3, 18, 1, NULL, NULL, NULL, 'unpaid', NULL, NULL),
(4, 19, 2, NULL, NULL, NULL, 'unpaid', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Staff`
--

CREATE TABLE `Staff` (
  `staff_id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_role` enum('HR','Accountant','Driver','Cleaner') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hire_date` date NOT NULL,
  `salary` int NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Staff`
--

INSERT INTO `Staff` (`staff_id`, `user_id`, `name`, `photo`, `phone`, `staff_role`, `hire_date`, `salary`, `address`) VALUES
(13, 8, 'Kyaw Soe', '8.png', '+1 (335) 717-1602', 'HR', '2024-12-12', 500000, 'Rerum quae sed fugia');

-- --------------------------------------------------------

--
-- Table structure for table `Students`
--

CREATE TABLE `Students` (
  `student_id` int NOT NULL,
  `parent_id` int NOT NULL,
  `class_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'male',
  `enrollment_date` date NOT NULL,
  `status` enum('active','suspend','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Students`
--

INSERT INTO `Students` (`student_id`, `parent_id`, `class_id`, `name`, `photo`, `date_of_birth`, `gender`, `enrollment_date`, `status`) VALUES
(16, 3, 1, 'Soe Moe', 's_16.png', '2021-12-25', 'male', '2025-02-21', 'pending'),
(17, 3, 1, 'Thuzar', 's_17.png', '2022-02-10', 'female', '2025-02-21', 'pending'),
(18, 3, 1, 'Tun Tun', 's_18.png', '2022-01-04', 'male', '2025-02-21', 'pending'),
(19, 4, 2, 'Su Su', 's_19.png', '2021-02-21', 'female', '2025-02-21', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `Teachers`
--

CREATE TABLE `Teachers` (
  `teacher_id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qualification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hire_date` date NOT NULL,
  `salary` int DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Teachers`
--

INSERT INTO `Teachers` (`teacher_id`, `user_id`, `name`, `photo`, `position`, `phone`, `experience`, `qualification`, `hire_date`, `salary`, `address`) VALUES
(29, 6, 'Teacher Su Su', '6.png', 'Lead Teacher', '+1 (382) 486-2475', '10', 'Tempor nesciunt lab', '2024-09-04', 700000, 'Expedita molestiae o');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Teacher','Staff','Parent','Admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','suspend','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', '$2y$10$/Tcvx3OdY3RSq3yeE1VhFeWIblWEMCO5c1IjdLytE8bzKX7yJD/sW', 'Admin', 'active', '2025-02-20 15:48:32', '2025-02-20 15:48:32'),
(6, 'susu@gmail.com', '$2y$10$OcSVYckTJZd7gI0SQNenCu0PJJdaxTUBT5X6uAqokT9hyrY1iz/H2', 'Teacher', 'active', '2025-02-21 06:34:26', '2025-02-21 06:34:26'),
(7, 'dawayeaye@gmail.com', '$2y$10$IxWZjtbPA9G8NCdulMQh.u6LG6b3IpGvYCiPmNvE5yHqqL7BWpXj6', 'Parent', 'active', '2025-02-21 06:52:15', '2025-02-21 06:52:15'),
(8, 'kyawsoe@gmail.com', '$2y$10$WQFkUtBDDou69z8rouNQ8eDtNh6eVD3nTFTLvAXs8c2d7MBnLV92C', 'Staff', 'active', '2025-02-21 07:02:37', '2025-02-21 07:02:37'),
(9, 'ukoko@gmail.com', '$2y$10$inojzZDyIY7oBEhDrqIKpuhQWjqjk7zDvCYKcbguZyTTcPaF/vkz.', 'Parent', 'active', '2025-02-21 02:24:38', '2025-02-21 02:24:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admins`
--
ALTER TABLE `Admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `Attendance`
--
ALTER TABLE `Attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `Classes`
--
ALTER TABLE `Classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `Events`
--
ALTER TABLE `Events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `Gallery`
--
ALTER TABLE `Gallery`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `Messages`
--
ALTER TABLE `Messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `Parents`
--
ALTER TABLE `Parents`
  ADD PRIMARY KEY (`parent_id`),
  ADD UNIQUE KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `Payments`
--
ALTER TABLE `Payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `Staff`
--
ALTER TABLE `Staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `Students`
--
ALTER TABLE `Students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `Teachers`
--
ALTER TABLE `Teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admins`
--
ALTER TABLE `Admins`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Attendance`
--
ALTER TABLE `Attendance`
  MODIFY `attendance_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Classes`
--
ALTER TABLE `Classes`
  MODIFY `class_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Events`
--
ALTER TABLE `Events`
  MODIFY `event_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Gallery`
--
ALTER TABLE `Gallery`
  MODIFY `media_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Messages`
--
ALTER TABLE `Messages`
  MODIFY `message_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Parents`
--
ALTER TABLE `Parents`
  MODIFY `parent_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Payments`
--
ALTER TABLE `Payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Staff`
--
ALTER TABLE `Staff`
  MODIFY `staff_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Students`
--
ALTER TABLE `Students`
  MODIFY `student_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `Teachers`
--
ALTER TABLE `Teachers`
  MODIFY `teacher_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Attendance`
--
ALTER TABLE `Attendance`
  ADD CONSTRAINT `Attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `Students` (`student_id`),
  ADD CONSTRAINT `Attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `Classes` (`class_id`);

--
-- Constraints for table `Gallery`
--
ALTER TABLE `Gallery`
  ADD CONSTRAINT `Gallery_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `Events` (`event_id`);

--
-- Constraints for table `Parents`
--
ALTER TABLE `Parents`
  ADD CONSTRAINT `Parents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `Staff`
--
ALTER TABLE `Staff`
  ADD CONSTRAINT `Staff_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `Teachers`
--
ALTER TABLE `Teachers`
  ADD CONSTRAINT `Teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
