-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 31, 2015 at 06:33 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `management`
--

-- --------------------------------------------------------

--
-- Table structure for table `avatars`
--

CREATE TABLE IF NOT EXISTS `avatars` (
  `user_id` int(100) NOT NULL,
  `avatar_path` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `avatars`
--

INSERT INTO `avatars` (`user_id`, `avatar_path`) VALUES
(1, 'ugolkova.jpg'),
(2, 'goncharenko.jpeg'),
(3, 'krivitsky.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE IF NOT EXISTS `fields` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_type` varchar(100) NOT NULL,
  `field_label` varchar(255) NOT NULL,
  `field_instructions` text,
  `field_required` tinyint(1) DEFAULT '0',
  `field_settings` text NOT NULL,
  `owner_id` int(100) NOT NULL,
  PRIMARY KEY (`field_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`field_id`, `field_type`, `field_label`, `field_instructions`, `field_required`, `field_settings`, `owner_id`) VALUES
(7, 'select', 'test field', 'aaaa', 1, '', 1),
(8, 'text', 'my_select label', 'my Select inst', 1, 'a:3:{s:9:"maxlength";s:0:"";s:9:"minlength";s:0:"";s:11:"placeholder";s:0:"";}', 1),
(9, 'text', 'test label', 'test instruction', 0, 'a:3:{s:9:"maxlength";s:2:"11";s:9:"minlength";s:2:"12";s:11:"placeholder";s:8:"30 30 10";}', 1),
(10, 'text', 'aaaaaaaab', 'cdddd', 0, 'a:3:{s:9:"maxlength";s:2:"20";s:9:"minlength";s:2:"53";s:11:"placeholder";s:1:"7";}', 1),
(11, 'file', 'test select', 'select instruction', 1, 'a:1:{s:10:"file-types";s:15:"JPG | GIF | PNG";}', 1),
(12, 'text', 'test', 'ssssbb333', 0, 'a:3:{s:9:"maxlength";s:2:"20";s:9:"minlength";s:1:"5";s:11:"placeholder";s:4:"2333";}', 1),
(13, 'text', 'test', 'ssssbb', 0, 'a:3:{s:9:"maxlength";s:2:"20";s:9:"minlength";s:1:"6";s:11:"placeholder";s:1:"3";}', 1),
(14, 'text', 'test 123', 'test instruction', 1, 'a:3:{s:9:"maxlength";s:2:"24";s:9:"minlength";s:2:"25";s:11:"placeholder";s:2:"26";}', 1),
(15, 'text', 'abi', 'cdk', 0, 'a:3:{s:9:"maxlength";s:2:"el";s:9:"minlength";s:2:"fm";s:11:"placeholder";s:2:"gn";}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lm_users`
--

CREATE TABLE IF NOT EXISTS `lm_users` (
  `lm_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  PRIMARY KEY (`user_id`,`lm_id`),
  KEY `lm_id` (`lm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lm_users`
--

INSERT INTO `lm_users` (`lm_id`, `user_id`) VALUES
(2, 1),
(2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pm_users`
--

CREATE TABLE IF NOT EXISTS `pm_users` (
  `pm_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  PRIMARY KEY (`user_id`,`pm_id`),
  KEY `pm_id` (`pm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pm_users`
--

INSERT INTO `pm_users` (`pm_id`, `user_id`) VALUES
(3, 1),
(3, 2),
(3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(40) NOT NULL,
  `user_password` varchar(32) NOT NULL,
  `user_email` varchar(40) NOT NULL,
  `user_type` enum('admin','manager','user') NOT NULL DEFAULT 'user',
  `user_name` varchar(100) NOT NULL,
  `user_skype` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_email`, `user_type`, `user_name`, `user_skype`) VALUES
(1, 'ugolkova', 'sasdfasdfas', 'login1@cogniance.com', 'user', 'Tetiana Ugolkova', 'ugolkova.t'),
(2, 'goncharenko', 'sasdfasdfas', 'login2@cogniance.com', 'user', 'Michael Goncharenko', 'mgoncharenko'),
(3, 'koval', 'sasdfasdfas', 'login2@cogniance.com', 'user', 'Alexey Koval', 'alexey_koval'),
(5, 'krivitskiy', 'sasdfasdfas', 'skrivitskiy@cogniance.com', 'user', 'Stanislav Krivitskiy', 'krivoy20008');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avatars`
--
ALTER TABLE `avatars`
  ADD CONSTRAINT `avatars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `fields`
--
ALTER TABLE `fields`
  ADD CONSTRAINT `fields_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `lm_users`
--
ALTER TABLE `lm_users`
  ADD CONSTRAINT `lm_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lm_users_ibfk_2` FOREIGN KEY (`lm_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pm_users`
--
ALTER TABLE `pm_users`
  ADD CONSTRAINT `pm_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pm_users_ibfk_2` FOREIGN KEY (`pm_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
