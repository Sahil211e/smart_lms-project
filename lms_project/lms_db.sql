-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 07:11 AM
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
-- Database: `lms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `course_id`, `title`, `description`, `due_date`) VALUES
(1, 1, 'HTML Page Design', 'Create a responsive HTML page', '2026-04-20'),
(2, 2, 'PHP Form Handling', 'Build a PHP form with validation', NULL),
(3, 3, 'Network Topology Report', 'Prepare a short report on topology', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `total_classes` int(11) DEFAULT NULL,
  `attended_classes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_name`, `subject_name`, `total_classes`, `attended_classes`) VALUES
(1, 'Sahil', 'Web Development', 30, 27),
(2, 'Sahil', 'PHP & MySQL', 28, 24),
(3, 'Sahil', 'Computer Networks', 32, 26),
(4, 'Sahil', 'Database Management', 25, 22);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `description`) VALUES
(1, 'Web Development', 'Learn HTML, CSS and frontend basics'),
(2, 'PHP & MySQL', 'Learn backend development with database'),
(3, 'Computer Networks', 'Networking concepts and protocols'),
(4, 'C++', 'object-oriented concepts, and real-world problem solving.”'),
(5, 'Operating System', 'Operating system manages hardware resources and allows applications to run.');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `marks_obtained` int(11) DEFAULT NULL,
  `total_marks` int(11) DEFAULT NULL,
  `result_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_name`, `subject_name`, `marks_obtained`, `total_marks`, `result_date`) VALUES
(1, 'Sahil', 'Web Development', 85, 100, '2026-04-14 15:11:23'),
(2, 'Sahil', 'PHP & MySQL', 78, 100, '2026-04-14 15:11:23'),
(3, 'Sahil', 'Computer Networks', 72, 100, '2026-04-14 15:11:23'),
(4, 'Sahil', 'Database Management', 88, 100, '2026-04-14 15:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `student_name` varchar(100) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `marks` int(11) DEFAULT 0,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `assignment_id`, `student_name`, `file_name`, `submitted_at`, `marks`, `status`) VALUES
(1, 1, 'Sahil', 'demo.pdf', '2026-04-14 15:11:23', 100, 'Reviewed'),
(2, 2, 'Sahil', '1777699295_pexels-ibragraphics-12191023.jpg', '2026-05-02 05:21:35', 0, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_image`, `password`, `role`, `status`) VALUES
(1, 'Sahil', 'sahil@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'student', 'active'),
(2, 'Mohammad Sahil', 'sahil@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'student', 'active'),
(4, 'Admin', 'admin@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'admin', 'active'),
(5, 'Sufyaan', 'sufyaan@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'student', 'active'),
(6, 'Abduttawwab', 'abdut@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'student', 'inactive'),
(7, 'Rahul', 'rahul@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'student', 'active'),
(8, 'Vikas', 'vikas@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'student', 'active'),
(9, 'ajay', 'ajay@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'student', 'active'),
(10, 'shivansh', 'shivansh@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', 'student', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
