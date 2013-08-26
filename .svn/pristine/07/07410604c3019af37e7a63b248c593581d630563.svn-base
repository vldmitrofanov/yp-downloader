-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 28, 2013 at 09:36 PM
-- Server version: 5.1.67-log
-- PHP Version: 5.3.20

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `testYP`
--

-- --------------------------------------------------------

--
-- Table structure for table `USA_zip_codes`
--

CREATE TABLE IF NOT EXISTS `USA_zip_codes` (
  `zip` int(5) DEFAULT NULL,
  `type` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `primary_city` varchar(27) CHARACTER SET utf8 DEFAULT NULL,
  `acceptable_cities` varchar(282) CHARACTER SET utf8 DEFAULT NULL,
  `unacceptable_cities` varchar(2208) CHARACTER SET utf8 DEFAULT NULL,
  `state` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `county` varchar(39) CHARACTER SET utf8 DEFAULT NULL,
  `timezone` varchar(28) CHARACTER SET utf8 DEFAULT NULL,
  `area_codes` varchar(39) CHARACTER SET utf8 DEFAULT NULL,
  `latitude` decimal(5,2) DEFAULT NULL,
  `longitude` decimal(6,2) DEFAULT NULL,
  `world_region` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `country` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `decommissioned` int(1) DEFAULT NULL,
  `estimated_population` int(5) DEFAULT NULL,
  `notes` varchar(124) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `YP_admin_settings`
--

CREATE TABLE IF NOT EXISTS `YP_admin_settings` (
  `set_id` int(10) NOT NULL AUTO_INCREMENT,
  `settings` varchar(100) COLLATE utf8_bin NOT NULL,
  `value` varchar(100) COLLATE utf8_bin NOT NULL,
  `short_description` varchar(100) COLLATE utf8_bin NOT NULL,
  `user` varchar(100) COLLATE utf8_bin NOT NULL,
  `long_description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`set_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `yp_proxy`
--

CREATE TABLE IF NOT EXISTS `yp_proxy` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `ipaddress` varchar(15) COLLATE utf8_bin NOT NULL,
  `port` varchar(5) COLLATE utf8_bin NOT NULL,
  `google` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT 'unknown',
  `status` varchar(11) COLLATE utf8_bin NOT NULL DEFAULT 'unknown',
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ipaddress` (`ipaddress`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=408 ;

-- --------------------------------------------------------

--
-- Table structure for table `yp_users`
--

CREATE TABLE IF NOT EXISTS `yp_users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `passwd` varchar(100) COLLATE utf8_bin NOT NULL,
  `users_ip` varchar(20) COLLATE utf8_bin NOT NULL,
  `hash` varchar(100) COLLATE utf8_bin NOT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `level` int(2) NOT NULL DEFAULT '1',
  `add_info` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `YP_US_industry`
--

CREATE TABLE IF NOT EXISTS `YP_US_industry` (
  `ind_id` int(10) NOT NULL AUTO_INCREMENT,
  `industry_code` varchar(100) COLLATE utf8_bin NOT NULL,
  `Industry_title` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ind_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `YP_US_industry_done`
--

CREATE TABLE IF NOT EXISTS `YP_US_industry_done` (
  `record_id` int(10) NOT NULL AUTO_INCREMENT,
  `ind_id_done` int(10) NOT NULL,
  `area_done` varchar(20) COLLATE utf8_bin NOT NULL,
  `entry_full_code` varchar(50) COLLATE utf8_bin NOT NULL,
  `pages_done` varchar(20) COLLATE utf8_bin NOT NULL,
  `date_done` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_finished` enum('yes','no') COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `enqueued` enum('yes','no') COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `url` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=179 ;

--
-- Triggers `YP_US_industry_done`
--
DROP TRIGGER IF EXISTS `insert_YP_US_industry_done_trigger`;
DELIMITER //
CREATE TRIGGER `insert_YP_US_industry_done_trigger` BEFORE INSERT ON `YP_US_industry_done`
 FOR EACH ROW set new.entry_full_code = concat(new.area_done, '-', new.ind_id_done)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `YP_US_state_city`
--

CREATE TABLE IF NOT EXISTS `YP_US_state_city` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL,
  `state_city_code` varchar(50) COLLATE utf8_bin NOT NULL,
  `state_city_title` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=138 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
