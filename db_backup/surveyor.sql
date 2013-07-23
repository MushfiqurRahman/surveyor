-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 23, 2013 at 10:59 AM
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ages`
--


-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE IF NOT EXISTS `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `areas`
--


-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(120) NOT NULL,
  `total_target` int(11) NOT NULL DEFAULT '0',
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `campaigns`
--


-- --------------------------------------------------------

--
-- Table structure for table `campaign_details`
--

CREATE TABLE IF NOT EXISTS `campaign_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `region_total` int(11) NOT NULL,
  `area_total` int(11) NOT NULL,
  `house_totals` varchar(256) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `campaign_details`
--


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
  `code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `houses`
--


-- --------------------------------------------------------

--
-- Table structure for table `mobiles`
--

CREATE TABLE IF NOT EXISTS `mobiles` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `representative_id` int(7) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5857 ;

--
-- Dumping data for table `mobiles`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=242 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=155 ;

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
  `code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `regions`
--


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `representatives`
--


-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE IF NOT EXISTS `surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
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
  `modified` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

