-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2020 at 11:54 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ws_weddingplanner`
--

-- --------------------------------------------------------

--
-- Table structure for table `wdp_blogs`
--

CREATE TABLE `wdp_blogs` (
  `blog_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blog_title` varchar(255) NOT NULL,
  `blog_image` text NOT NULL,
  `blog_content` longtext NOT NULL,
  `posted_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wdp_blogs`
--

INSERT INTO `wdp_blogs` (`blog_id`, `event_id`, `user_id`, `blog_title`, `blog_image`, `blog_content`, `posted_date`, `updated_date`) VALUES
(1, 1, 5, '25 Wedding day', 'http://localhost/weddingplanner/api/user_uploads/51608723104.jpg', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', '2020-12-23 12:31:44', '2020-12-23 12:31:44');

-- --------------------------------------------------------

--
-- Table structure for table `wdp_blog_likes`
--

CREATE TABLE `wdp_blog_likes` (
  `like_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wdp_comments`
--

CREATE TABLE `wdp_comments` (
  `comment_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `parent_comment_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wdp_comments`
--

INSERT INTO `wdp_comments` (`comment_id`, `blog_id`, `user_id`, `comment`, `parent_comment_id`, `created_date`, `updated_date`) VALUES
(1, 1, 5, 'This is updated comment', 0, '2020-12-24 05:12:39', '2020-12-29 11:32:00'),
(2, 1, 2, 'yes , touch wood', 1, '2020-12-24 05:17:35', NULL),
(3, 1, 5, 'ohhh, really!!', 1, '2020-12-24 05:17:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wdp_template`
--

CREATE TABLE `wdp_template` (
  `template_id` int(11) NOT NULL,
  `type` varchar(150) DEFAULT NULL,
  `template_name` varchar(250) NOT NULL,
  `thumbnai_path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wdp_template`
--

INSERT INTO `wdp_template` (`template_id`, `type`, `template_name`, `thumbnai_path`) VALUES
(1, 'wedding', 'wedding', 'template01.jpg'),
(2, 'function', 'function', 'template02.jpg'),
(3, 'event', 'event', 'template03.jpg'),
(4, 'wedding', 'wedding-2', 'template04.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `wdp_users`
--

CREATE TABLE `wdp_users` (
  `user_id` int(11) NOT NULL,
  `source` varchar(100) DEFAULT NULL COMMENT 'gmail,facebook,web',
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` text NOT NULL,
  `code` varchar(150) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0-inactive,1-active',
  `device_id` varchar(50) DEFAULT NULL,
  `device_token` text DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wdp_users`
--

INSERT INTO `wdp_users` (`user_id`, `source`, `first_name`, `last_name`, `mobile`, `email`, `password`, `code`, `status`, `device_id`, `device_token`, `created_date`, `updated_date`) VALUES
(2, 'web', 'heer', 'sharma', '9876543210', 'heer@gmail.com', '$2y$10$ZtMFbMtqZy8t.fILiYqVW.mlhYtOFyvOEc2EHFfEJBE9jNnkFf3LS', NULL, '1', NULL, NULL, '2020-11-26 06:15:23', '2020-11-26 11:51:45'),
(3, 'web', 'jeet', 'mohan', '9176543210', 'jeet@gmail.com', '$2y$10$AKkSxJ7/jVR57k6tcwOUBOH1pcSMqgA7g6bFwi5S3A7RS79nOiWm6', NULL, '1', NULL, NULL, '2020-12-11 08:20:35', '2020-12-11 13:50:35'),
(4, 'web', 'roma', 'shah', '9276543212', 'roma@gmail.com', '$2y$10$DYyN/Rz9bND7DSxwCTxuh../7LxjAC5/NOU7sV3nD52TzM9gwtuEa', NULL, '1', NULL, NULL, '2020-12-15 04:25:46', '2020-12-15 09:55:46'),
(5, 'web', 'akash', 'sathavara', '9376543212', 'akash@gmail.com', '$2y$10$KNKPXfiVMxUCMNcF9j9/Zun0FLHDSnYuQVMfLCToLcnGv0VAUG.rW', '12345', '1', NULL, NULL, '2020-12-16 02:58:02', '2020-12-16 08:28:02');

-- --------------------------------------------------------

--
-- Table structure for table `wdp_user_event`
--

CREATE TABLE `wdp_user_event` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_type` varchar(250) NOT NULL,
  `remarks` varchar(255) NOT NULL COMMENT 'event name',
  `template_id` int(11) DEFAULT NULL,
  `is_domain` enum('no','yes') NOT NULL DEFAULT 'no',
  `site_url` text DEFAULT NULL,
  `selected_date` date DEFAULT NULL,
  `city` varchar(250) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wdp_user_event`
--

INSERT INTO `wdp_user_event` (`event_id`, `user_id`, `event_type`, `remarks`, `template_id`, `is_domain`, `site_url`, `selected_date`, `city`, `location`, `created_date`, `updated_date`) VALUES
(1, 5, 'wedding', 'Raj w/s Simran', 1, 'no', 'http://localhost/weddingplanner/raj-simran01', '2020-12-20', 'Ahmedabad', 'A-One Party plot, Ahmedabad , Gujarat', '2020-12-21 07:41:37', '2020-12-21 08:02:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wdp_blogs`
--
ALTER TABLE `wdp_blogs`
  ADD PRIMARY KEY (`blog_id`),
  ADD UNIQUE KEY `event_id` (`event_id`) USING BTREE;

--
-- Indexes for table `wdp_blog_likes`
--
ALTER TABLE `wdp_blog_likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `wdp_comments`
--
ALTER TABLE `wdp_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `wdp_template`
--
ALTER TABLE `wdp_template`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `wdp_users`
--
ALTER TABLE `wdp_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wdp_user_event`
--
ALTER TABLE `wdp_user_event`
  ADD PRIMARY KEY (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wdp_blogs`
--
ALTER TABLE `wdp_blogs`
  MODIFY `blog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wdp_comments`
--
ALTER TABLE `wdp_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wdp_template`
--
ALTER TABLE `wdp_template`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wdp_users`
--
ALTER TABLE `wdp_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wdp_user_event`
--
ALTER TABLE `wdp_user_event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
