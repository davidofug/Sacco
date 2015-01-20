-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2015 at 07:18 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sacco_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `activity` varchar(35) COLLATE latin1_general_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `client_transactions`
--

CREATE TABLE IF NOT EXISTS `client_transactions` (
  `id` int(64) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `pay_date` date NOT NULL,
  `acnumber` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `nature` varchar(15) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `principal` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `interest` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `principalbal` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `interestbal` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `refund` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `due` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `balance` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `amount` varchar(11) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registered` date NOT NULL,
  `name` varchar(70) COLLATE latin1_general_ci NOT NULL,
  `pincode` varchar(260) COLLATE latin1_general_ci NOT NULL,
  `acnumber` varchar(6) COLLATE latin1_general_ci NOT NULL,
  `age` int(2) NOT NULL,
  `gender` varchar(7) COLLATE latin1_general_ci NOT NULL,
  `idnumber` varchar(18) COLLATE latin1_general_ci NOT NULL,
  `phnumber` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `address` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `type` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `particular` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `title` int(11) NOT NULL,
  `addedby` int(11) NOT NULL,
  `members` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE IF NOT EXISTS `income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `source` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `particular` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `postedby` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `opcash`
--

CREATE TABLE IF NOT EXISTS `opcash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userid` int(11) NOT NULL,
  `transid` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transid` (`transid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reserve`
--

CREATE TABLE IF NOT EXISTS `reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `type` tinyint(1) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `datecreated` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `datemodified` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `usercreated` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `usermodified` varchar(40) COLLATE latin1_general_ci NOT NULL,
  `interestrate` int(11) unsigned NOT NULL,
  `systeminitcash` int(10) unsigned NOT NULL,
  `maxloan` int(10) unsigned NOT NULL,
  `loanduration` int(10) unsigned NOT NULL,
  `leastcash` int(10) unsigned NOT NULL,
  `systemname` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `backupdir` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `datecreated`, `datemodified`, `usercreated`, `usermodified`, `interestrate`, `systeminitcash`, `maxloan`, `loanduration`, `leastcash`, `systemname`, `backupdir`) VALUES
(1, '0', '0', '0', 'administrator', 20, 31000000, 400000, 30, 500000, 'Microfin Ltd', '');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(64) NOT NULL,
  `date` date NOT NULL,
  `datetime` datetime NOT NULL,
  `repaydate` date NOT NULL,
  `acnumber` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `particulars` varchar(254) COLLATE latin1_general_ci NOT NULL,
  `nature` varchar(140) COLLATE latin1_general_ci NOT NULL,
  `b_type` varchar(180) COLLATE latin1_general_ci NOT NULL,
  `r_type` varchar(180) COLLATE latin1_general_ci NOT NULL,
  `principal` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `interest` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `arrears` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `amount` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `c_balance` varchar(11) COLLATE latin1_general_ci NOT NULL,
  `user` varchar(30) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `name` varchar(64) COLLATE latin1_general_ci NOT NULL,
  `uname` varchar(33) COLLATE latin1_general_ci NOT NULL,
  `upassword` varchar(33) COLLATE latin1_general_ci NOT NULL,
  `model` tinyint(1) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `onoroff` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `date`, `name`, `uname`, `upassword`, `model`, `status`, `onoroff`) VALUES
(6, '2010-09-15', 'User', 'user', '5f4dcc3b5aa765d61d8327deb882cf99', 1, 1, 1),
(8, '2011-01-07', 'Super Admin', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 2, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
