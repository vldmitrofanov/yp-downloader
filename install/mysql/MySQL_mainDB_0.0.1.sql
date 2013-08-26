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

INSERT INTO `YP_admin_settings` VALUES (1,'threads_allowed','6','threads_allowed','admin','This value defines, how many simultaneous copies of \'background_run.php\' script could be run at same time'),(2,'curl_timeout','5','curl_timeout','admin',''),(3,'check_proxy_cycles','5','check_proxy_cycles','admin',''),(6,'proxy_amount_warning','10','proxy_amount_warning','',''),(5,'proxy_max_age','10','proxy_max_age','admin','Maximum days age of a proxy IP. Outdated proxy wont be use'),(7,'proxy_amount_disaster','3','','',''),(8,'warning_email','shirker2006@gmail.com','','','');

INSERT INTO `YP_US_state_city` VALUES (1,0,'AL','Alabama'),(2,0,'AK','Alaska'),(3,0,'AZ','Arizona'),(4,0,'AR','Arkansas'),(5,0,'CA','California'),(6,0,'CO','Colorado'),(7,0,'CT','Connecticut'),(8,0,'DE','Delaware'),(9,0,'FL','Florida'),(10,0,'GA','Georgia'),(11,0,'HI','Hawaii'),(12,0,'ID','Idaho'),(13,0,'IL','Illinois'),(14,0,'IN','Indiana'),(15,0,'IA','Iowa'),(16,0,'KS','Kansas'),(17,0,'KY','Kentucky'),(18,0,'LA','Louisiana'),(19,0,'ME','Maine'),(20,0,'MD','Maryland'),(21,0,'MA','Massachusetts'),(22,0,'MI','Michigan'),(23,0,'MN','Minnesota'),(24,0,'MS','Mississippi'),(25,0,'MO','Missouri'),(26,0,'MT','Montana'),(27,0,'NE','Nebraska'),(28,0,'NV','Nevada'),(29,0,'NH','New Hampshire'),(30,0,'NJ','New Jersey'),(31,0,'NM','New Mexico'),(32,0,'NY','New York'),(33,0,'NC','North Carolina'),(34,0,'ND','North Dakota'),(35,0,'OH','Ohio'),(36,0,'OK','Oklahoma'),(37,0,'OR','Oregon'),(38,0,'PA','Pennsylvania'),(39,0,'RI','Rhode Island'),(40,0,'SC','South Carolina'),(41,0,'SD','South Dakota'),(42,0,'TN','Tennessee'),(43,0,'TX','Texas'),(44,0,'UT','Utah'),(45,0,'VT','Vermont'),(46,0,'VA','Virginia'),(47,0,'WA','Washington'),(48,0,'WV','West Virginia'),(49,0,'WI','Wisconsin'),(50,0,'WY','Wyoming'),(115,1,'decatur','Decatur'),(114,1,'birmingham','Birmingham'),(113,5,'los-angeles','Los Angeles'),(116,22,'grand-rapids','Grand Rapids'),(117,43,'houston','Houston'),(118,43,'fort-worth','Fort Worth'),(119,9,'jacksonville','Jacksonville'),(120,40,'greenville','Greenville'),(121,20,'baltimore','Baltimore'),(122,9,'daytona-beach','Daytona Beach'),(123,43,'georgetown','Georgetown'),(124,30,'jersey-city','Jersey City'),(125,38,'york','York'),(126,9,'boca-raton','Boca Raton'),(127,18,'opelousas','Opelousas'),(128,24,'gulfport','Gulfport'),(129,3,'yaple-park-phoenix','Yaple Park, Phoenix'),(130,38,'harrisburg','Harrisburg'),(131,44,'salt-lake-city','Salt Lake City'),(132,10,'marietta','Marietta'),(133,6,'denver','Denver'),(134,5,'yorba-linda','Yorba Linda'),(135,43,'arlington','Arlington'),(136,5,'san-francisco','San Francisco'),(137,8,'wilmington','Wilmington'),(138,35,'vinton','Vinton'),(139,1,'abbeville','Abbeville'),(140,1,'bellwood','Bellwood'),(141,1,'black','Black'),(142,35,'scottown','Scottown'),(143,42,'knoxville','Knoxville'),(144,35,'kitts-hill','Kitts Hill'),(145,40,'charleston','Charleston');

INSERT INTO `yp_users` VALUES (1,'admin','shirker2006@gmail.com','98990fccff49ccb76cb0463ea88e114f','','','2013-01-29 01:42:26',15,'');