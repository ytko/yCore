-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 12, 2012 at 07:23 PM
-- Server version: 5.1.40
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `j1`
--

-- --------------------------------------------------------

--
-- Table structure for table `j7_ytko`
--

CREATE TABLE IF NOT EXISTS `j7_ytko` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `type` varchar(16) NOT NULL,
  `fields` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `j7_ytko`
--

INSERT INTO `j7_ytko` (`id`, `key`, `name`, `type`, `fields`) VALUES
(18, 'dsfg', 'gdsf', '', 'a:0:{}'),
(19, 'users', 'users', 'users', 'a:2:{s:5:"login";O:8:"stdClass":2:{s:4:"type";s:4:"text";s:4:"name";s:5:"login";}s:8:"password";a:2:{s:4:"type";s:4:"text";s:4:"name";s:8:"password";}}'),
(15, 'ert', 'sdfg', 'tre', 'a:1:{s:3:"gsd";a:2:{s:4:"type";s:4:"text";s:4:"name";s:4:"dsgf";}}'),
(17, 'gdf', 'dfg', 'dsfg', 'a:0:{}');

-- --------------------------------------------------------

--
-- Table structure for table `j7_ytko_dsfg`
--

CREATE TABLE IF NOT EXISTS `j7_ytko_dsfg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `j7_ytko_dsfg`
--


-- --------------------------------------------------------

--
-- Table structure for table `j7_ytko_ert`
--

CREATE TABLE IF NOT EXISTS `j7_ytko_ert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gsd` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `j7_ytko_ert`
--

INSERT INTO `j7_ytko_ert` (`id`, `gsd`) VALUES
(1, 'gsdgdfstryh'),
(2, 'gsd75'),
(9, 'hgfh'),
(5, 'hdfgh'),
(8, 'hgfh'),
(10, 'hgfh'),
(12, 'fsdgdsfРїР°РІС‹');

-- --------------------------------------------------------

--
-- Table structure for table `j7_ytko_gdf`
--

CREATE TABLE IF NOT EXISTS `j7_ytko_gdf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `j7_ytko_gdf`
--


-- --------------------------------------------------------

--
-- Table structure for table `j7_ytko_users`
--

CREATE TABLE IF NOT EXISTS `j7_ytko_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `j7_ytko_users`
--

INSERT INTO `j7_ytko_users` (`id`, `login`, `password`) VALUES
(1, 'user1', '202cb962ac59075b964b07152d234b70'),
(2, 'user2', '827ccb0eea8a706c4c34a16891f84e7b');
