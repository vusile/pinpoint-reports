-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 22, 2013 at 04:20 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pinpoint_reports`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisers`
--

CREATE TABLE IF NOT EXISTS `advertisers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advertiser_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `advertisers`
--

INSERT INTO `advertisers` (`id`, `advertiser_name`) VALUES
(1, 'Airtel');

-- --------------------------------------------------------

--
-- Table structure for table `campaignz`
--

CREATE TABLE IF NOT EXISTS `campaignz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `advertiser` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `budget` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `booked` int(11) NOT NULL,
  `delivered` int(11) NOT NULL,
  `effective_cpa_cpc_or_cpm` float NOT NULL,
  `previous_campaign` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `campaignz`
--

INSERT INTO `campaignz` (`id`, `name`, `advertiser`, `start_date`, `end_date`, `budget`, `type`, `booked`, `delivered`, `effective_cpa_cpc_or_cpm`, `previous_campaign`, `payment_status`) VALUES
(1, 'Airtel SMS Kichizi', 1, '2012-12-01', '2012-12-31', 350, 2, 700, 695, 0.5, 2, 0),
(2, 'Airtel Branding Campaign', 1, '2012-11-01', '2012-11-30', 350, 2, 700, 752, 0.465426, 3, 0),
(3, 'Airtel Modem', 1, '2012-10-01', '2012-10-31', 350, 2, 700, 766, 0.456919, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `campaign_default_values`
--

CREATE TABLE IF NOT EXISTS `campaign_default_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `value` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `campaign_default_values`
--

INSERT INTO `campaign_default_values` (`id`, `type`, `value`) VALUES
(1, 1, 1.75),
(2, 2, 0.5),
(3, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `campaign_types`
--

CREATE TABLE IF NOT EXISTS `campaign_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `campaign_types`
--

INSERT INTO `campaign_types` (`id`, `type`) VALUES
(1, 'CPM'),
(2, 'CPC'),
(3, 'CPA'),
(4, 'Exclusive'),
(5, 'SOV');

-- --------------------------------------------------------

--
-- Table structure for table `webs`
--

CREATE TABLE IF NOT EXISTS `webs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_name` varchar(30) NOT NULL,
  `contact_person` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `webs`
--

INSERT INTO `webs` (`id`, `website_name`, `contact_person`, `email`, `phone`, `url`) VALUES
(1, 'ZoomTanzania.com', 'Kirk Gillis', 'kirk@zoomtanzania.com', '+255 784 402 463', 'http://www.zoomtanzania.com'),
(2, 'JamiiForums', 'Mike McKee', 'filgga@gmail.com', '+255 755642 929', 'http://www.jamiiforums.com'),
(3, 'Shaffih Dauda', 'Shaffih', 'shaffidauda@gmail.com', '', 'http://www.shaffihdauda.com/'),
(4, '8020 Fashions', '', '', '', 'http://www.8020fashions.blogspot.com/'),
(5, 'Mama Zuri', 'Nancy Sumari', '', '', 'http://mamazuri.com'),
(6, 'DJ Choka', '', '', '', 'http://www.djchoka.blogspot.com/'),
(7, 'Bongo5', 'Luca Neghesti', 'luca@bongo5.com', '+255 786 009 009 ', 'http://www.bongo5.com/'),
(8, 'DJ Fetty', 'DJ Fetty', 'fatty10_2@hotmail.com', '+255 713 333 343', 'http://www.djfetty.blogspot.com/');

-- --------------------------------------------------------

--
-- Table structure for table `websites_campaigns`
--

CREATE TABLE IF NOT EXISTS `websites_campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website` int(11) NOT NULL,
  `campaign` int(11) NOT NULL,
  `percentage` int(11) NOT NULL,
  `value_before_percentage` float NOT NULL,
  `value_after_percentage` float NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `websites_campaigns`
--

INSERT INTO `websites_campaigns` (`id`, `website`, `campaign`, `percentage`, `value_before_percentage`, `value_after_percentage`, `priority`) VALUES
(1, 4, 1, 0, 0, 0, 0),
(2, 7, 1, 0, 0, 0, 1),
(3, 6, 1, 0, 0, 0, 2),
(4, 8, 1, 0, 0, 0, 3),
(5, 2, 1, 0, 0, 0, 4),
(6, 5, 1, 0, 0, 0, 5),
(7, 3, 1, 0, 0, 0, 6),
(8, 1, 1, 0, 0, 0, 7),
(9, 4, 2, 0, 0, 0, 0),
(10, 7, 2, 0, 0, 0, 1),
(11, 6, 2, 0, 0, 0, 2),
(12, 8, 2, 0, 0, 0, 3),
(13, 2, 2, 0, 0, 0, 4),
(14, 5, 2, 0, 0, 0, 5),
(15, 3, 2, 0, 0, 0, 6),
(16, 1, 2, 0, 0, 0, 7),
(17, 4, 3, 0, 0, 0, 0),
(18, 7, 3, 0, 0, 0, 1),
(19, 6, 3, 0, 0, 0, 2),
(20, 8, 3, 0, 0, 0, 3),
(21, 2, 3, 0, 0, 0, 4),
(22, 5, 3, 0, 0, 0, 5),
(23, 3, 3, 0, 0, 0, 6),
(24, 1, 3, 0, 0, 0, 7);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
