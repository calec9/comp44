-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: custsql-ipg42.eigbox.net
-- Generation Time: Mar 27, 2014 at 09:45 AM
-- Server version: 5.5.32
-- PHP Version: 4.4.9
-- 
-- Database: `eyespyhq`
-- 
CREATE DATABASE `eyespyhq` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `eyespyhq`;

-- --------------------------------------------------------

-- 
-- Table structure for table `category`
-- 

CREATE TABLE `category` (
  `cID` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(256) NOT NULL,
  PRIMARY KEY (`cID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `category`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `dynamicStatistics`
-- 

CREATE TABLE `dynamicStatistics` (
  `day` varchar(256) NOT NULL,
  `value` int(11) NOT NULL,
  KEY `day` (`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `dynamicStatistics`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `entries`
-- 

CREATE TABLE `entries` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `URL` varchar(256) NOT NULL,
  `lastvisit` datetime NOT NULL,
  `firstvisit` datetime NOT NULL,
  `hitcounter` bigint(20) NOT NULL,
  `hits_today` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `entries`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `logon`
-- 

CREATE TABLE `logon` (
  `ID` tinyint(4) NOT NULL,
  `user` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `lastlogin` datetime DEFAULT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `logon`
-- 

INSERT INTO `logon` VALUES (1, 'admin', 'admin', '2014-03-02 13:38:19');

-- --------------------------------------------------------

-- 
-- Table structure for table `staticStatistics`
-- 

CREATE TABLE `staticStatistics` (
  `day` varchar(256) NOT NULL,
  `meanVisits` varchar(256) NOT NULL,
  KEY `day` (`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `staticStatistics`
-- 

INSERT INTO `staticStatistics` VALUES ('1', '95');
INSERT INTO `staticStatistics` VALUES ('2', '60');
INSERT INTO `staticStatistics` VALUES ('3', '60');
INSERT INTO `staticStatistics` VALUES ('4', '10');
INSERT INTO `staticStatistics` VALUES ('5', '90');
INSERT INTO `staticStatistics` VALUES ('6', '199');
INSERT INTO `staticStatistics` VALUES ('7', '240');

-- --------------------------------------------------------

-- 
-- Table structure for table `websiteCategoryLink`
-- 

CREATE TABLE `websiteCategoryLink` (
  `wID` int(11) NOT NULL,
  `cID` int(11) NOT NULL,
  KEY `wID` (`wID`),
  KEY `cID` (`cID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `websiteCategoryLink`
-- 

