-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2018 at 09:56 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `karatekl_test`
--
CREATE DATABASE IF NOT EXISTS `karatekl_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `karatekl_test`;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `account_id` bigint(20) NOT NULL,
  `user_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `user_password` varchar(50) COLLATE utf8_bin NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `contact_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `contact_email` varchar(50) COLLATE utf8_bin NOT NULL,
  `contact_phone` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `club_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `access_level` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (
  `class_id` bigint(20) NOT NULL,
  `comp_id` bigint(20) NOT NULL,
  `tatami_id` bigint(20) NOT NULL,
  `class_category` varchar(50) COLLATE utf8_bin NOT NULL,
  `class_discipline` varchar(50) COLLATE utf8_bin NOT NULL,
  `class_gender_category` varchar(50) COLLATE utf8_bin NOT NULL,
  `class_gender` varchar(7) COLLATE utf8_bin NOT NULL,
  `class_weight_length` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `class_age` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `class_fee` smallint(6) NOT NULL DEFAULT '200',
  `class_match_time` tinyint(4) NOT NULL DEFAULT '4',
  `class_total_time` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `clubregistration`
--

DROP TABLE IF EXISTS `clubregistration`;
CREATE TABLE `clubregistration` (
  `club_reg_id` bigint(20) NOT NULL,
  `coach_names` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `club_startorder` tinyint(4) DEFAULT NULL,
  `account_id` bigint(20) NOT NULL,
  `comp_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `competition`
--

DROP TABLE IF EXISTS `competition`;
CREATE TABLE `competition` (
  `comp_id` bigint(20) NOT NULL,
  `comp_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `comp_start_date` date NOT NULL,
  `comp_end_date` date NOT NULL,
  `comp_end_reg_date` date DEFAULT NULL,
  `comp_arranger` varchar(50) COLLATE utf8_bin NOT NULL,
  `comp_email` varchar(50) COLLATE utf8_bin NOT NULL,
  `comp_url` varchar(50) COLLATE utf8_bin NOT NULL,
  `comp_current` int(4) DEFAULT '0',
  `comp_raffled` tinyint(1) NOT NULL DEFAULT '0',
  `comp_max_regs` smallint(6) NOT NULL,
  `comp_start_time` time NOT NULL,
  `comp_limit_roundrobin` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `contestants`
--

DROP TABLE IF EXISTS `contestants`;
CREATE TABLE `contestants` (
  `contestant_id` bigint(20) NOT NULL,
  `contestant_name` varchar(50) COLLATE utf8_bin NOT NULL,
  `contestant_birth` date NOT NULL,
  `contestant_gender` varchar(7) COLLATE utf8_bin NOT NULL,
  `account_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `loginlog`
--

DROP TABLE IF EXISTS `loginlog`;
CREATE TABLE `loginlog` (
  `log_id` bigint(20) NOT NULL,
  `account_id` bigint(20) NOT NULL,
  `comp_id` bigint(20) NOT NULL,
  `login_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `message_id` bigint(20) NOT NULL,
  `message_subject` varchar(256) COLLATE utf8_bin NOT NULL,
  `message` text COLLATE utf8_bin NOT NULL,
  `message_how` varchar(15) COLLATE utf8_bin NOT NULL,
  `comp_id` bigint(20) NOT NULL,
  `message_to` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `message_from` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `message_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

DROP TABLE IF EXISTS `registration`;
CREATE TABLE `registration` (
  `reg_id` bigint(20) NOT NULL,
  `club_reg_id` bigint(20) NOT NULL,
  `contestant_id` bigint(20) NOT NULL,
  `contestant_height` smallint(3) DEFAULT NULL,
  `contestant_startnumber` smallint(4) DEFAULT NULL,
  `contestant_seeded` tinyint(1) DEFAULT '0',
  `contestant_result` tinyint(4) DEFAULT NULL,
  `class_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tatamis`
--

DROP TABLE IF EXISTS `tatamis`;
CREATE TABLE `tatamis` (
  `tatami_id` bigint(11) NOT NULL,
  `tatami_name` varchar(25) COLLATE utf8_bin DEFAULT NULL,
  `comp_id` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `user_name` (`user_name`,`contact_email`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `comp_id` (`comp_id`),
  ADD KEY `tatami_id` (`tatami_id`);

--
-- Indexes for table `clubregistration`
--
ALTER TABLE `clubregistration`
  ADD PRIMARY KEY (`club_reg_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `comp_id` (`comp_id`);

--
-- Indexes for table `competition`
--
ALTER TABLE `competition`
  ADD PRIMARY KEY (`comp_id`);

--
-- Indexes for table `contestants`
--
ALTER TABLE `contestants`
  ADD PRIMARY KEY (`contestant_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `loginlog`
--
ALTER TABLE `loginlog`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `comp_id` (`comp_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `comp_id` (`comp_id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`reg_id`),
  ADD KEY `club_reg_id` (`club_reg_id`),
  ADD KEY `contestant_id` (`contestant_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `tatamis`
--
ALTER TABLE `tatamis`
  ADD PRIMARY KEY (`tatami_id`),
  ADD KEY `class_id` (`comp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `clubregistration`
--
ALTER TABLE `clubregistration`
  MODIFY `club_reg_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `competition`
--
ALTER TABLE `competition`
  MODIFY `comp_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contestants`
--
ALTER TABLE `contestants`
  MODIFY `contestant_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loginlog`
--
ALTER TABLE `loginlog`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `reg_id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tatamis`
--
ALTER TABLE `tatamis`
  MODIFY `tatami_id` bigint(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `comp_id` FOREIGN KEY (`comp_id`) REFERENCES `competition` (`comp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clubregistration`
--
ALTER TABLE `clubregistration`
  ADD CONSTRAINT `account_id` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clubregistration_ibfk_1` FOREIGN KEY (`comp_id`) REFERENCES `classes` (`comp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `competition`
--
ALTER TABLE `competition`
  ADD CONSTRAINT `competition_ibfk_1` FOREIGN KEY (`comp_id`) REFERENCES `loginlog` (`comp_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `contestants`
--
ALTER TABLE `contestants`
  ADD CONSTRAINT `account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`comp_id`) REFERENCES `competition` (`comp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `registration`
--
ALTER TABLE `registration`
  ADD CONSTRAINT `class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `club_reg` FOREIGN KEY (`club_reg_id`) REFERENCES `clubregistration` (`club_reg_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `conestant` FOREIGN KEY (`contestant_id`) REFERENCES `contestants` (`contestant_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tatamis`
--
ALTER TABLE `tatamis`
  ADD CONSTRAINT `Drop` FOREIGN KEY (`comp_id`) REFERENCES `competition` (`comp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
