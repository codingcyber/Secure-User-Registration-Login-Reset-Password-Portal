-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 25, 2020 at 08:40 AM
-- Server version: 10.3.15-MariaDB
-- PHP Version: 7.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin-portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_fail`
--

CREATE TABLE `login_fail` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `loginfailed` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_fail`
--

INSERT INTO `login_fail` (`id`, `uid`, `loginfailed`) VALUES
(1, 1, '2019-07-25 09:54:20');

-- --------------------------------------------------------

--
-- Table structure for table `login_log`
--

CREATE TABLE `login_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `loggedin` datetime NOT NULL,
  `loggedout` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_log`
--

INSERT INTO `login_log` (`id`, `uid`, `loggedin`, `loggedout`) VALUES
(1, 1, '2019-07-24 16:50:31', '2019-07-24 16:50:48'),
(2, 2, '2019-07-24 16:50:56', '2019-07-24 16:51:06'),
(3, 2, '2019-07-24 16:51:21', '2019-07-24 21:08:10'),
(4, 1, '2019-07-24 21:08:40', '2019-07-24 21:08:45'),
(5, 2, '2019-07-24 21:09:02', '2019-07-25 09:33:28'),
(6, 4, '2019-07-25 10:22:29', '2019-07-25 10:22:31'),
(7, 2, '2019-07-25 10:58:05', '2019-07-25 12:10:01'),
(8, 5, '2019-07-25 12:13:08', '2019-07-25 12:15:45'),
(9, 2, '2019-07-25 12:15:51', '2019-07-25 12:16:31'),
(10, 5, '2019-07-25 12:17:21', '2019-07-25 12:17:27'),
(11, 2, '2019-07-25 12:34:24', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `password_reset`
--

INSERT INTO `password_reset` (`id`, `uid`, `reset_token`, `created`) VALUES
(1, 1, 'd41d8cd98f00b204e9800998ecf8427e', '2019-07-04 16:40:24'),
(2, 1, '098f6bcd4621d373cade4e832627b4f6', '2019-07-04 16:40:40'),
(3, 1, '098f6bcd4621d373cade4e832627b4f61562238778', '2019-07-04 16:42:58'),
(10, 4, '6e056e513c734a013126312022a43c3a1564030941', '2019-07-25 10:32:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `activate` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `activate`, `role`, `created`, `updated`) VALUES
(1, 'test', 'test@gmail.com', '$2y$10$mZATo/AHCY1Jh32wyi1ISefLE28OibYAd979kYz.EznxBwlAwFJcS', 1, '', '2019-07-04 16:38:20', '2019-07-04 21:23:48'),
(2, 'vivek', 'vivek@pixelw3.com', '$2y$10$OsgxIz.LS4GPH2tPysPj6OpRfKq7iNfgtpDB3LMKnxmoVXBdwrrHy', 1, 'admin', '2019-07-05 19:45:23', '2019-07-24 13:25:29'),
(5, 'vivek1', 'vivek@codingcyber.com', '$2y$10$U0r7jIM9vZW0AULPoYVwtuNfRRy/llL.9X4wrfUinJ3h4ayN2Iwei', 1, '', '2019-07-25 12:12:22', '2019-07-25 12:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `user_active`
--

CREATE TABLE `user_active` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `active_token` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_active`
--

INSERT INTO `user_active` (`id`, `uid`, `active_token`, `created`) VALUES
(1, 1, '098f6bcd4621d373cade4e832627b4f6', '2019-07-04 16:38:20'),
(2, 3, '6e056e513c734a013126312022a43c3a1564030236', '2019-07-25 10:20:36');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `uid`, `activity`, `created`) VALUES
(1, 1, 'User Registered', '2019-07-04 16:38:20'),
(2, 1, 'Password Reset Intiated', '2019-07-04 16:40:24'),
(3, 1, 'Password Reset Intiated', '2019-07-04 16:40:40'),
(4, 1, 'Password Reset Intiated', '2019-07-04 16:42:58'),
(5, 1, 'Password Reset Intiated', '2019-07-04 16:51:11'),
(6, 1, 'Password Updated with Reset Password', '2019-07-04 21:19:56'),
(7, 1, 'Password Reset Intiated', '2019-07-04 21:23:00'),
(8, 1, 'Password Updated with Reset Password', '2019-07-04 21:23:48'),
(9, 2, 'User LogOut', '2019-07-05 19:49:13'),
(10, 2, 'Password Reset Intiated', '2019-07-05 19:52:20'),
(11, 2, 'Password Updated with Reset Password', '2019-07-05 19:53:04'),
(12, 2, 'Password Reset Intiated', '2019-07-05 19:54:20'),
(13, 2, 'Password Updated with Reset Password', '2019-07-05 19:55:56'),
(14, 2, 'Password Reset Intiated', '2019-07-05 19:56:50'),
(15, 2, 'Password Updated with Reset Password', '2019-07-05 19:57:09'),
(16, 1, 'User LoggedIn', '2019-07-23 21:33:21'),
(17, 1, 'User LogOut', '2019-07-23 21:33:40'),
(18, 1, 'User LoggedIn', '2019-07-24 10:51:51'),
(19, 2, 'Profile Updated', '2019-07-24 12:05:37'),
(20, 2, 'Profile Updated', '2019-07-24 12:05:40'),
(21, 2, 'Profile Updated', '2019-07-24 13:12:18'),
(22, 2, 'Profile Updated', '2019-07-24 13:12:28'),
(23, 2, 'Profile Updated', '2019-07-24 13:12:34'),
(24, 2, 'Profile Updated', '2019-07-24 13:16:29'),
(25, 2, 'Profile Updated', '2019-07-24 13:19:01'),
(26, 2, 'Password Updated', '2019-07-24 13:19:01'),
(27, 2, 'Profile Updated', '2019-07-24 13:23:26'),
(28, 2, 'Password Updated', '2019-07-24 13:23:26'),
(29, 2, 'Profile Updated', '2019-07-24 13:23:52'),
(30, 2, 'Password Updated', '2019-07-24 13:23:52'),
(31, 2, 'Profile Updated', '2019-07-24 13:25:28'),
(32, 2, 'Password Updated', '2019-07-24 13:25:29'),
(33, 2, 'Profile Updated', '2019-07-24 14:06:11'),
(34, 2, 'Profile Updated', '2019-07-24 14:06:30'),
(35, 2, 'Profile Updated', '2019-07-24 14:22:57'),
(36, 2, 'Profile Updated', '2019-07-24 14:23:43'),
(37, 2, 'Profile Updated', '2019-07-24 15:12:22'),
(38, 2, 'Profile Updated', '2019-07-24 15:36:10'),
(39, 2, 'Profile Updated', '2019-07-24 15:52:48'),
(40, 2, 'Profile Updated', '2019-07-24 15:53:03'),
(41, 1, 'User LoggedIn', '2019-07-24 16:49:18'),
(42, 1, 'User LogOut', '2019-07-24 16:50:24'),
(43, 1, 'User LoggedIn', '2019-07-24 16:50:31'),
(44, 1, 'User LogOut', '2019-07-24 16:50:48'),
(45, 2, 'User LoggedIn', '2019-07-24 16:50:56'),
(46, 2, 'User LogOut', '2019-07-24 16:51:06'),
(47, 2, 'User LoggedIn', '2019-07-24 16:51:21'),
(48, 2, 'Profile Updated', '2019-07-24 20:10:18'),
(49, 2, 'User Permissions Updated', '2019-07-24 20:22:24'),
(50, 2, 'User Permissions Updated', '2019-07-24 20:25:31'),
(51, 2, 'User Permissions Updated', '2019-07-24 20:44:51'),
(52, 2, 'User Permissions Updated', '2019-07-24 20:46:17'),
(53, 2, 'User LogOut', '2019-07-24 21:08:10'),
(54, 1, 'User LoggedIn', '2019-07-24 21:08:40'),
(55, 1, 'User LogOut', '2019-07-24 21:08:45'),
(56, 2, 'User LoggedIn', '2019-07-24 21:09:02'),
(57, 2, 'User LogOut', '2019-07-25 09:33:28'),
(58, 2, 'User LoggedIn', '2019-07-25 09:33:44'),
(59, 2, 'User LogOut', '2019-07-25 09:34:03'),
(60, 1, 'User LoggedIn', '2019-07-25 09:45:10'),
(61, 1, 'User LogOut', '2019-07-25 09:46:26'),
(62, 2, 'User LoggedIn', '2019-07-25 09:46:29'),
(63, 2, 'User LogOut', '2019-07-25 09:51:44'),
(64, 1, 'User LoggedIn', '2019-07-25 09:51:47'),
(65, 1, 'User LogOut', '2019-07-25 09:52:23'),
(66, 2, 'User LoggedIn', '2019-07-25 09:52:28'),
(67, 2, 'User LogOut', '2019-07-25 09:54:17'),
(68, 1, 'User LogIn Failed', '2019-07-25 09:54:20'),
(69, 1, 'User LoggedIn', '2019-07-25 09:54:31'),
(70, 1, 'User LogOut', '2019-07-25 09:54:36'),
(71, 2, 'User LoggedIn', '2019-07-25 10:00:06'),
(72, 2, 'User LogOut', '2019-07-25 10:00:24'),
(73, 1, 'User LoggedIn', '2019-07-25 10:00:26'),
(74, 1, 'User LogOut', '2019-07-25 10:00:31'),
(75, 3, 'User Registered', '2019-07-25 10:20:36'),
(76, 4, 'User Registered', '2019-07-25 10:21:32'),
(77, 4, 'User Registered', '2019-07-25 10:21:55'),
(78, 4, 'User LoggedIn', '2019-07-25 10:22:29'),
(79, 4, 'User LogOut', '2019-07-25 10:22:31'),
(80, 4, 'Password Reset Intiated', '2019-07-25 10:22:42'),
(81, 4, 'Password Updated with Reset Password', '2019-07-25 10:24:34'),
(82, 4, 'Password Reset Intiated', '2019-07-25 10:32:21'),
(83, 4, 'Password Reset Intiated', '2019-07-25 10:32:49'),
(84, 4, 'Password Updated with Reset Password', '2019-07-25 10:36:42'),
(85, 2, 'User LoggedIn', '2019-07-25 10:58:05'),
(86, 2, 'Profile Updated', '2019-07-25 12:08:36'),
(87, 2, 'Profile Updated', '2019-07-25 12:08:43'),
(88, 2, 'Profile Updated', '2019-07-25 12:08:48'),
(89, 2, 'User LogOut', '2019-07-25 12:10:01'),
(90, 5, 'User Registered', '2019-07-25 12:12:22'),
(91, 5, 'User Registered', '2019-07-25 12:12:42'),
(92, 5, 'User LoggedIn', '2019-07-25 12:13:08'),
(93, 5, 'Profile Updated', '2019-07-25 12:14:59'),
(94, 5, 'User Permissions Created', '2019-07-25 12:15:20'),
(95, 5, 'User LogOut', '2019-07-25 12:15:45'),
(96, 2, 'User LoggedIn', '2019-07-25 12:15:51'),
(97, 2, 'User Permissions Updated', '2019-07-25 12:15:58'),
(98, 2, 'User Permissions Updated', '2019-07-25 12:16:23'),
(99, 2, 'User LogOut', '2019-07-25 12:16:31'),
(100, 5, 'Password Reset Intiated', '2019-07-25 12:16:43'),
(101, 5, 'Password Updated with Reset Password', '2019-07-25 12:17:03'),
(102, 5, 'User LoggedIn', '2019-07-25 12:17:21'),
(103, 5, 'User LogOut', '2019-07-25 12:17:27'),
(104, 2, 'User LoggedIn', '2019-07-25 12:34:24'),
(105, 2, 'Profile Updated', '2019-07-25 12:36:05'),
(106, 2, 'Profile Updated', '2019-07-25 12:36:46'),
(107, 2, 'Profile Updated', '2019-07-25 12:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `profilepic` varchar(255) NOT NULL,
  `bio` varchar(255) NOT NULL,
  `fb` varchar(255) NOT NULL,
  `twitter` varchar(255) NOT NULL,
  `linkedin` varchar(255) NOT NULL,
  `blog` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `uid`, `fname`, `lname`, `mobile`, `age`, `gender`, `profilepic`, `bio`, `fb`, `twitter`, `linkedin`, `blog`, `website`, `created`, `updated`) VALUES
(1, 1, '', '', '9876543210', '', '', '', '', '', '', '', '', '', '2019-07-04 16:38:20', '2019-07-04 16:38:20'),
(2, 2, 'Vivek', 'Vengala', '9876543210', '30', 'male', 'uploads/2.jpg', 'Bio', 'https://www.facebook.com/vivekvengala.in/', 'https://twitter.com/vivek_vengala', 'https://in.linkedin.com/in/vivekvengala', 'https://codingcyber.org/author/vivek/', 'https://codingcyber.org', '2019-07-05 19:45:23', '2019-07-25 12:36:52'),
(5, 5, 'Vivek', 'V', '9876543210', '30', 'male', 'uploads/5.jpg', 'Bio', 'https://www.facebook.com/', 'https://twitter.com/', 'https://in.linkedin.com/in/', 'https://codingcyber.org/', 'https://codingcyber.org', '2019-07-25 12:12:22', '2019-07-25 12:14:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_permission`
--

CREATE TABLE `user_permission` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `show_fname` tinyint(1) NOT NULL DEFAULT 1,
  `show_lname` tinyint(1) NOT NULL DEFAULT 1,
  `show_mobile` tinyint(1) NOT NULL DEFAULT 1,
  `show_email` tinyint(1) NOT NULL DEFAULT 1,
  `show_age` tinyint(1) NOT NULL DEFAULT 1,
  `show_gender` tinyint(1) NOT NULL DEFAULT 1,
  `show_pic` tinyint(1) NOT NULL DEFAULT 1,
  `show_bio` tinyint(1) NOT NULL DEFAULT 1,
  `show_fb` tinyint(1) NOT NULL DEFAULT 1,
  `show_twitter` tinyint(1) NOT NULL DEFAULT 1,
  `show_linkedin` tinyint(1) NOT NULL DEFAULT 1,
  `show_blog` tinyint(1) NOT NULL DEFAULT 1,
  `show_website` tinyint(1) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_permission`
--

INSERT INTO `user_permission` (`id`, `uid`, `show_fname`, `show_lname`, `show_mobile`, `show_email`, `show_age`, `show_gender`, `show_pic`, `show_bio`, `show_fb`, `show_twitter`, `show_linkedin`, `show_blog`, `show_website`, `created`, `updated`) VALUES
(1, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, '2019-07-24 20:10:18', '2019-07-25 12:16:23'),
(2, 5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '2019-07-25 12:15:20', '2019-07-25 12:15:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_fail`
--
ALTER TABLE `login_fail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `user_active`
--
ALTER TABLE `user_active`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_permission`
--
ALTER TABLE `user_permission`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login_fail`
--
ALTER TABLE `login_fail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_active`
--
ALTER TABLE `user_active`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_permission`
--
ALTER TABLE `user_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
