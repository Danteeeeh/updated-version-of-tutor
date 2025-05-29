-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql303.infinityfree.com
-- Generation Time: May 29, 2025 at 06:19 PM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_38927201_tutors_lounge`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('superadmin','admin','editor') NOT NULL DEFAULT 'editor',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `full_name`, `email`, `role`, `is_active`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$pWs8e.y8GGSEHiNIofFoXeoW4WaNXUSjZ0mpsSfBdrWt86Yn7gHQW', 'System Administrator', 'admin@example.com', 'superadmin', 1, '2025-05-29 07:28:05', '2025-05-04 23:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `schedule` varchar(100) DEFAULT NULL,
  `instructor` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT '/api/placeholder/400/300',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `title`, `description`, `category`, `age_group`, `schedule`, `instructor`, `image_url`, `created_at`) VALUES
(1, 'Math Group Class', 'A fun and interactive class combining early reading skills with foundational mathematics concepts for young learners.', 'Math', '4-6 y/o', '10:00-11:00 AM - 4:00-5:00 PM', 'Ms. Robbie', '/images/math-reading.jpg', '2025-05-04 02:10:00'),
(2, 'Reading', 'Develop early reading skills through engaging stories, phonics activities, and sight word practice.', 'reading', '4-6 y/o', '2:00 - 3:00 PM', 'Ms. Shiela', '/images/reading.jpg', '2025-05-04 02:10:00'),
(3, 'Filipino Reading', 'Introduction to Filipino language reading skills through stories, songs, and interactive activities.', 'filipino', '4-6 y/o', '3:00-4:00 pm', 'Ms. Shiela', '/images/filipino.jpg', '2025-05-04 02:10:00'),
(4, 'Playgroup Class', 'A social and educational playgroup that helps children develop social skills while having fun with peers.', 'playgroup', '4-6 y/o', '4:00 - 5:00 PM', 'Ms. Robbie', '/images/playgroup.jpg', '2025-05-04 02:10:00'),
(5, 'Filipino for Primary Level', 'Filipino language classes designed specifically for primary school students to develop reading and writing skills.', 'filipino', 'Grade 1-3', '2:00 - 3:00 PM', 'Ms. Robbie', '/images/filipino-primary.jpg', '2025-05-04 02:10:00'),
(6, 'Arts and Crafts', 'Explore creativity through various art forms, techniques, and materials in this hands-on arts and crafts class.', 'arts', 'Grades 7-9', '5:00 - 6:00 PM', 'Ms. Robbie', '/images/arts.jpg', '2025-05-04 02:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE `professors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT '/api/placeholder/400/300'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professors`
--

INSERT INTO `professors` (`id`, `name`, `bio`, `specialization`, `image_url`) VALUES
(1, 'Ms. Robbie', 'Experienced educator with 10+ years teaching young children', 'Early Childhood Education', '/api/placeholder/400/300'),
(2, 'Ms. Shiela', 'Specialized in language development and reading skills', 'Language Arts', '/api/placeholder/400/300');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `student_name` varchar(100) NOT NULL,
  `student_age` int(11) DEFAULT NULL,
  `parent_name` varchar(100) NOT NULL,
  `parent_email` varchar(100) NOT NULL,
  `parent_phone` varchar(20) DEFAULT NULL,
  `special_notes` text DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `professor_id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `week_number` tinyint(4) NOT NULL COMMENT 'Week number in month (1-4)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `class_id`, `professor_id`, `day_of_week`, `start_time`, `end_time`, `week_number`) VALUES
(1, 1, 1, 'Monday', '10:00:00', '11:00:00', 1),
(2, 1, 1, 'Wednesday', '10:00:00', '11:00:00', 1),
(3, 1, 1, 'Friday', '10:00:00', '11:00:00', 1),
(4, 1, 1, 'Monday', '16:00:00', '17:00:00', 1),
(5, 1, 1, 'Wednesday', '16:00:00', '17:00:00', 1),
(6, 1, 1, 'Friday', '16:00:00', '17:00:00', 1),
(7, 2, 2, 'Tuesday', '14:00:00', '15:00:00', 1),
(8, 2, 2, 'Thursday', '14:00:00', '15:00:00', 1),
(9, 3, 2, 'Monday', '15:00:00', '16:00:00', 1),
(10, 3, 2, 'Wednesday', '15:00:00', '16:00:00', 1),
(11, 3, 2, 'Friday', '15:00:00', '16:00:00', 1),
(12, 4, 1, 'Tuesday', '16:00:00', '17:00:00', 1),
(13, 4, 1, 'Thursday', '16:00:00', '17:00:00', 1),
(14, 5, 1, 'Monday', '14:00:00', '15:00:00', 1),
(15, 5, 1, 'Wednesday', '14:00:00', '15:00:00', 1),
(16, 5, 1, 'Friday', '14:00:00', '15:00:00', 1),
(17, 6, 1, 'Monday', '17:00:00', '18:00:00', 1),
(18, 6, 1, 'Wednesday', '17:00:00', '18:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon_class` varchar(50) NOT NULL DEFAULT 'fas fa-book',
  `age_range` varchar(50) NOT NULL,
  `format` varchar(50) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `has_class_link` tinyint(1) NOT NULL DEFAULT 1,
  `display_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `icon_class`, `age_range`, `format`, `duration`, `has_class_link`, `display_order`) VALUES
(1, 'Summer Math Intensive', 'Boost math skills with our focused summer program', 'fas fa-square-root-alt', 'Grades 1-8', 'Group Classes', '4-8 weeks', 1, 1),
(2, 'Reading Enrichment', 'Develop strong reading comprehension and fluency', 'fas fa-book-open', 'Ages 5-12', 'Group or Individual', 'Ongoing', 1, 2),
(4, 'College Prep', 'SAT/ACT prep and college application guidance', 'fas fa-university', 'Grades 9-12', 'Individual', 'Custom', 0, 3),
(5, 'Homework Help', 'Personalized assistance with school assignments', 'fas fa-question-circle', 'All ages', 'Individual', 'Ongoing', 0, 4),
(6, 'Creative Writing', 'Develop storytelling and writing skills', 'fas fa-pen-fancy', 'Ages 10-18', 'Group Classes', '6-12 weeks', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `professor_id` (`professor_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`);

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`professor_id`) REFERENCES `professors` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
