-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2025 at 03:51 PM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complain`
--

INSERT INTO `complain` (`id`, `name`, `email`, `phone`, `age_group`, `complaint_sector`, `location`, `title`, `description`, `status`, `priority`, `created_at`) VALUES
(1, 'test', 'tem', '9874563210', 'cecf', 'ff', 'fgtr', 'hty', 'gtrhtr', '', '', '2025-09-10 12:53:32'),
(2, 'fbtr', 'tem', '+919876543', '26-35', 'municipal', 'surat ballia', 'temple2', 'jbnvkjrv', '', '', '2025-09-10 12:53:32'),
(3, 'fbtr', 'tem', '+919876543', '26-35', 'college', 'd3f3fr', 'f4f42', 'rgfgg43f2', '', '', '2025-09-10 12:53:32'),
(4, 'fbtr', 'tem', '+919876543', '26-35', 'college', 'd3f3fr', 'f4f42', 'rgfgg43f2', '', '', '2025-09-10 12:53:32'),
(5, 'fbtr', 'tem', '+919876543', '26-35', 'college', 'd3f3fr', 'f4f42', 'rgfgg43f2', '', '', '2025-09-10 12:53:32'),
(6, 'fbtr', 'tem', '+919876543', '26-35', 'college', 'd3f3fr', 'f4f42', 'rgfgg43f2', '', '', '2025-09-10 12:53:32'),
(7, 'fbtr', 'tem', '+919876543', '18-25', 'municipal', 'd3f3fr', 'f4f42', 'rgfgg43f2', '', '', '2025-09-10 12:53:32'),
(8, 'hrhe', 'tem', '+919876543', '18-25', 'municipal', 'surat ballia', 'temple2', 'rgfgg43f2', '', '', '2025-09-10 12:53:32'),
(9, 'hrhe', 'tem', '+919876543', '', 'college', 'd3f3fr', 'f4f42', 'rgfgg43f2', 'immediate', 'low', '2025-09-10 12:53:32'),
(10, 'hrhe', 'tem', '+919876543', '', 'college', 'd3f3fr', 'f4f42', 'rgfgg43f2', 'immediate', 'low', '2025-09-10 12:54:27'),
(11, 'hrhe', 'tem', '+919876543', '26-35', 'police', 'd3f3fr', 'f4f42', 'rgfgg43f2', 'immediate', 'low', '2025-09-10 13:01:02'),
(12, 'hrhe', 'tem', '+919876543', '18-25', 'college', 'd3f3fr', 'f4f42', 'rgfgg43f2', 'week', 'medium', '2025-09-10 13:12:11'),
(13, 'hrhe', 'tem', '+919876543', '18-25', 'police', 'd3f3fr', 'jbjhgjh', 'rgfgg43f2', 'immediate', 'medium', '2025-09-10 13:44:18');

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
(1, 7, 'uploads/Screenshot 2025-01-15 '),
(2, 8, 'uploads/Screenshot 2025-01-15 '),
(3, 8, 'uploads/Screenshot 2025-01-15 '),
(4, 8, 'uploads/Screenshot 2025-01-15 '),
(5, 9, 'uploads/Screenshot 2025-01-30 '),
(6, 9, 'uploads/Screenshot 2025-01-30 '),
(7, 10, 'uploads/Screenshot 2025-01-30 '),
(8, 10, 'uploads/Screenshot 2025-01-30 '),
(9, 12, 'uploads/Screenshot 2025-01-30 '),
(10, 12, 'uploads/Screenshot 2025-01-30 '),
(11, 12, 'uploads/Screenshot 2025-01-30 '),
(12, 13, 'uploads/Screenshot 2025-01-30 '),
(13, 13, 'uploads/Screenshot 2025-01-30 ');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(4) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pass` varchar(30) NOT NULL,
  `phone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `pass`, `phone`) VALUES
(1, 'test', 'tempmail@gmail.com', '123', '123456'),
(2, 'rhyrey', 'gyertyr@jb.fbfb', '123', 'ggryr');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complain`
--
ALTER TABLE `complain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
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
-- AUTO_INCREMENT for table `complain`
--
ALTER TABLE `complain`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
