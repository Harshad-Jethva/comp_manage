-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2025 at 04:40 PM
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
-- Database: `complain`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(4) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pass` varchar(30) NOT NULL,
  `role` varchar(30) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `pass`, `role`, `status`) VALUES
(1, 'dev@gmail.com', '111', 'adm', '1');

-- --------------------------------------------------------

--
-- Table structure for table `complain`
--

CREATE TABLE `complain` (
  `id` int(4) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(3) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `age_group` varchar(50) NOT NULL,
  `complaint_sector` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `status` varchar(50) NOT NULL,
  `priority` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `assigned_to` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complain`
--

INSERT INTO `complain` (`id`, `name`, `email`, `phone`, `age_group`, `complaint_sector`, `location`, `title`, `description`, `status`, `priority`, `created_at`, `updated_at`, `assigned_to`) VALUES
(1, 'ehbdje', 'gof', '55665768', '51-65', 'police', 'jebjbfj', 'nbjkbjkbk', 'hvvyugu', 'week', 'medium', '2025-09-30 12:08:36', '2025-09-30 12:08:36', NULL),
(3, 'test', 'tem', '12345656', '18-25', 'municipal', 'kjvnkjf', 'vtrgtrk', 'ebfjkrebgjk', 'week', 'low', '2025-10-01 14:34:07', '2025-10-01 14:34:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaint_status_summary`
--

CREATE TABLE `complaint_status_summary` (
  `id` int(4) NOT NULL,
  `compid` int(4) NOT NULL,
  `status` enum('Pending','in_progress','resolved','closed') NOT NULL DEFAULT 'Pending',
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint_status_summary`
--

INSERT INTO `complaint_status_summary` (`id`, `compid`, `status`, `update_at`) VALUES
(1, 1, 'Pending', '2025-09-30 12:08:36'),
(3, 3, 'Pending', '2025-10-01 14:34:07');

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `id` int(4) NOT NULL,
  `c_id` int(4) NOT NULL,
  `path` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`id`, `c_id`, `path`) VALUES
(1, 1, 'uploads/68dbc84431d98.png'),
(2, 1, 'uploads/68dbc844330ed.png'),
(3, 2, 'uploads/68dbc9024dcc0.png'),
(4, 2, 'uploads/68dbc9024f10c.png'),
(5, 3, 'uploads/68dd3bdff237b.png'),
(6, 3, 'uploads/68dd3bdff366d.png');

-- --------------------------------------------------------

--
-- Table structure for table `sectors`
--

CREATE TABLE `sectors` (
  `id` int(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sectors`
--

INSERT INTO `sectors` (`id`, `name`, `description`, `status`) VALUES
(1, 'Municipal', 'Municipal-related complaints', 'active'),
(2, 'Police', 'Police-related complaints', 'active'),
(3, 'Healthcare', 'Healthcare-related complaints', 'active'),
(4, 'Education', 'Education-related complaints', 'active'),
(5, 'Transport', 'Transport-related complaints', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(4) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pass` varchar(30) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `u_status` enum('Active','Inactive','Suspended') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `pass`, `phone`, `u_status`) VALUES
(1, 'test', 'tempmail@gmail.com', '123', '12345656', 'Active'),
(2, 'rhyrey', 'gyertyr@jb.fbfb', '123', 'ggryr', 'Active'),
(10, 'dipak ', 'bca@123gmail.com', '987', '9658745698', 'Active'),
(11, 'dipak ', 'bca@1234gmail.com', '123', '9658745698', 'Inactive');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complain`
--
ALTER TABLE `complain`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_complain_assigned_to` (`assigned_to`);

--
-- Indexes for table `complaint_status_summary`
--
ALTER TABLE `complaint_status_summary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sectors`
--
ALTER TABLE `sectors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complain`
--
ALTER TABLE `complain`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `complaint_status_summary`
--
ALTER TABLE `complaint_status_summary`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sectors`
--
ALTER TABLE `sectors`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complain`
--
ALTER TABLE `complain`
  ADD CONSTRAINT `fk_complain_assigned_to` FOREIGN KEY (`assigned_to`) REFERENCES `admin` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
