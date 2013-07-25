-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2013 at 01:29 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `surveyor`
--

-- --------------------------------------------------------

--
-- Table structure for table `ages`
--

CREATE TABLE IF NOT EXISTS `ages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lower_age` int(11) NOT NULL,
  `upper_age` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ages`
--

INSERT INTO `ages` (`id`, `lower_age`, `upper_age`) VALUES
(1, 18, 22),
(2, 23, 25);

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE IF NOT EXISTS `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `region_id`, `title`) VALUES
(4, 4, 'Sylhet');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(120) NOT NULL,
  `total_target` int(11) NOT NULL DEFAULT '0',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `title`, `total_target`, `start_date`, `end_date`) VALUES
(3, 'Gold Campaign', 100000, '2013-07-25 00:00:00', '2013-07-26 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_details`
--

CREATE TABLE IF NOT EXISTS `campaign_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  `house_target` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `campaign_details`
--

INSERT INTO `campaign_details` (`id`, `campaign_id`, `house_id`, `house_target`) VALUES
(4, 3, 8, 60000),
(3, 3, 7, 40000);

-- --------------------------------------------------------

--
-- Table structure for table `consumptions`
--

CREATE TABLE IF NOT EXISTS `consumptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lower_no` int(11) NOT NULL,
  `upper_no` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `consumptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE IF NOT EXISTS `houses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `area_id`, `title`) VALUES
(7, 4, 'MBKB Sunamgonj'),
(8, 4, 'South Sylhet Company Limited');

-- --------------------------------------------------------

--
-- Table structure for table `mobiles`
--

CREATE TABLE IF NOT EXISTS `mobiles` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `representative_id` int(7) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5869 ;

--
-- Dumping data for table `mobiles`
--

INSERT INTO `mobiles` (`id`, `representative_id`, `mobile_no`) VALUES
(5868, 36, '8801712068773'),
(5867, 34, '8801915741711'),
(5866, 32, '8801915741710'),
(5865, 30, '8801915741709');

-- --------------------------------------------------------

--
-- Table structure for table `mo_logs`
--

CREATE TABLE IF NOT EXISTS `mo_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(15) NOT NULL,
  `sms` varchar(160) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `datetime` varchar(30) NOT NULL,
  `time_int` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mo_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `mt_logs`
--

CREATE TABLE IF NOT EXISTS `mt_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `outlet_id` int(11) NOT NULL,
  `msisdn` varchar(15) NOT NULL,
  `sms` varchar(165) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `datetime` varchar(30) NOT NULL,
  `time_int` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mt_logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `occupations`
--

CREATE TABLE IF NOT EXISTS `occupations` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `code` varchar(4) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `occupations`
--


-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `title`) VALUES
(4, 'Sylhet');

-- --------------------------------------------------------

--
-- Table structure for table `representatives`
--

CREATE TABLE IF NOT EXISTS `representatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `house_id` int(11) NOT NULL,
  `superviser_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `br_code` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `representatives`
--

INSERT INTO `representatives` (`id`, `house_id`, `superviser_id`, `name`, `br_code`) VALUES
(30, 7, 0, 'Mamun Ahmed Kha', ''),
(31, 7, 30, 'Sheiku Miftauzzaman', 'BR1'),
(32, 7, 0, 'Mamun Ahmed Kha', ''),
(33, 7, 32, 'Shah Soharul Islam', 'BR2'),
(34, 7, 0, 'Mamun Ahmed Kha', ''),
(35, 7, 34, 'Sohel Mia', 'BR3'),
(36, 8, 0, 'Ajit Bunarjee ', ''),
(37, 8, 36, 'Komol', 'BR1151'),
(38, 8, 36, 'Junayed Ahmed', 'BR1152'),
(39, 8, 36, 'Mahbub Alom', 'BR1153'),
(40, 8, 36, 'Biplab Miah', 'BR1154');

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE IF NOT EXISTS `surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `house_id` int(11) NOT NULL,
  `representative_id` int(11) NOT NULL,
  `mo_log_id` int(11) NOT NULL,
  `survey_counter` int(3) NOT NULL DEFAULT '1',
  `name` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `age_id` int(4) NOT NULL,
  `adc` int(11) NOT NULL,
  `occupation_id` int(3) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `surveys`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created`, `modified`) VALUES
(6, 'Mushfiqur', 'Rahman', 'mushfique@codetrio.com', 'c112e8d4ac9314e97bd1554c09245294e53b9095', '2013-07-24 10:58:31', '0000-00-00 00:00:00');
