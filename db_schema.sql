-- phpMyAdmin SQL Dump
-- version 2.11.9.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 09, 2009 at 07:23 PM
-- Server version: 5.1.31
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `web5db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `session_start` int(10) unsigned NOT NULL DEFAULT '0',
  `session_last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `session_ip_address` varchar(16) NOT NULL DEFAULT '0',
  `session_user_agent` varchar(50) NOT NULL,
  `session_data` text NOT NULL,
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Domain`
--

CREATE TABLE IF NOT EXISTS `Domain` (
  `DOM_ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `DOM_NAME` varchar(40) NOT NULL,
  `LAST_MOD_DT` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `LAST_MOD_USER` varchar(20) NOT NULL DEFAULT 'System',
  `ACTIVE` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`DOM_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

CREATE TABLE IF NOT EXISTS `lists` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `address` varchar(40) DEFAULT NULL,
  `status` enum('Active','Creating','Deleting') NOT NULL,
  `size` int(8) DEFAULT NULL,
  `style` int(1) NOT NULL,
  `level` varchar(2) NOT NULL,
  `level_id` varchar(5) NOT NULL,
  `members` text NOT NULL,
  `emails` blob,
  `owner` varchar(50) NOT NULL,
  `owner_pass` varchar(180) NOT NULL,
  `mod1` int(8) unsigned DEFAULT NULL,
  `mod2` int(8) unsigned DEFAULT NULL,
  `mod3` int(8) unsigned DEFAULT NULL,
  `mod_pass` varchar(256) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Table structure for table `loginlog`
--

CREATE TABLE IF NOT EXISTS `loginlog` (
  `ID` int(9) NOT NULL AUTO_INCREMENT,
  `contact_id` varchar(8) NOT NULL,
  `name` varchar(60) NOT NULL,
  `ip_addr` varchar(18) NOT NULL,
  `login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logout` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2745 ;

-- --------------------------------------------------------

--
-- Table structure for table `pass_reset`
--

CREATE TABLE IF NOT EXISTS `pass_reset` (
  `SN` int(9) NOT NULL AUTO_INCREMENT,
  `contact_id` varchar(8) NOT NULL,
  `email` varchar(60) NOT NULL,
  `enc_email` varchar(180) NOT NULL,
  `ip_addr` varchar(18) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`SN`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

-- --------------------------------------------------------

--
-- Table structure for table `Ref_Code`
--

CREATE TABLE IF NOT EXISTS `Ref_Code` (
  `REF_ID` smallint(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `DOM_ID` smallint(6) NOT NULL,
  `REF_CODE` varchar(10) NOT NULL,
  `short_desc` varchar(30) NOT NULL,
  `long_desc` varchar(50) NOT NULL,
  `LAST_MOD_DT` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `LAST_MOD_USER` varchar(20) NOT NULL DEFAULT 'System',
  PRIMARY KEY (`REF_ID`),
  KEY `DOM_ID` (`DOM_ID`,`REF_CODE`),
  KEY `REF_CODE` (`REF_CODE`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=178 ;

-- --------------------------------------------------------

--
-- Table structure for table `responsibilities`
--

CREATE TABLE IF NOT EXISTS `responsibilities` (
  `swayamsevak_id` mediumint(8) NOT NULL,
  `shakha_id` mediumint(8) DEFAULT NULL,
  `nagar_id` varchar(10) DEFAULT NULL,
  `vibhag_id` varchar(10) DEFAULT NULL,
  `sambhag_id` varchar(10) DEFAULT NULL,
  `level` varchar(8) NOT NULL,
  `responsibility` varchar(8) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `swayamsevak_id` (`swayamsevak_id`),
  KEY `responsibility` (`responsibility`),
  KEY `shakha_id` (`shakha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sankhyas`
--

CREATE TABLE IF NOT EXISTS `sankhyas` (
  `sankhya_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shakha_id` int(10) unsigned NOT NULL,
  `contact_id` int(10) unsigned NOT NULL,
  `ip` varchar(18) NOT NULL,
  `date` date DEFAULT NULL,
  `shishu_m` mediumint(4) unsigned DEFAULT NULL,
  `shishu_f` mediumint(4) unsigned DEFAULT NULL,
  `bala_m` mediumint(4) unsigned DEFAULT NULL,
  `bala_f` mediumint(4) unsigned DEFAULT NULL,
  `kishor_m` mediumint(4) unsigned DEFAULT NULL,
  `kishor_f` mediumint(4) unsigned DEFAULT NULL,
  `yuva_m` mediumint(4) unsigned DEFAULT NULL,
  `yuva_f` mediumint(4) unsigned DEFAULT NULL,
  `tarun_m` mediumint(4) unsigned DEFAULT NULL,
  `tarun_f` mediumint(4) unsigned DEFAULT NULL,
  `praudh_m` mediumint(4) unsigned DEFAULT NULL,
  `praudh_f` mediumint(4) unsigned DEFAULT NULL,
  `families` mediumint(4) unsigned DEFAULT NULL,
  `total` mediumint(4) unsigned DEFAULT NULL,
  `shakha_info` text,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sankhya_id`),
  KEY `date` (`date`),
  KEY `shakha_id` (`shakha_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=569 ;

-- --------------------------------------------------------

--
-- Table structure for table `shakhas`
--

CREATE TABLE IF NOT EXISTS `shakhas` (
  `shakha_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip` varchar(5) DEFAULT NULL,
  `frequency` varchar(2) DEFAULT NULL,
  `frequency_day` varchar(20) DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `shakha_status` tinyint(1) DEFAULT '1',
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_mod` varchar(8) NOT NULL DEFAULT 'System',
  `nagar_id` varchar(10) DEFAULT NULL,
  `vibhag_id` varchar(10) NOT NULL,
  `sambhag_id` varchar(10) NOT NULL,
  PRIMARY KEY (`shakha_id`),
  KEY `vibhag_id` (`vibhag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=162 ;

-- --------------------------------------------------------

--
-- Table structure for table `swayamsevaks`
--

CREATE TABLE IF NOT EXISTS `swayamsevaks` (
  `contact_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `household_id` mediumint(8) DEFAULT NULL,
  `shakha_id` mediumint(8) DEFAULT NULL,
  `contact_type` varchar(2) DEFAULT 'GC',
  `first_name` varchar(60) DEFAULT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `birth_year` varchar(4) DEFAULT NULL,
  `gana` enum('1','2','3','4','5','6') DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `position` varchar(60) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `email_status` enum('Active','Unsubscribed','Bounced') DEFAULT NULL,
  `password` varchar(256) NOT NULL,
  `passwordmd5` varchar(128) NOT NULL,
  `ph_mobile` varchar(12) DEFAULT NULL,
  `ph_home` varchar(12) DEFAULT NULL,
  `ph_work` varchar(12) DEFAULT NULL,
  `street_add1` varchar(100) DEFAULT NULL,
  `street_add2` varchar(100) DEFAULT NULL,
  `city` varchar(60) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `ssv_completed` varchar(20) DEFAULT NULL,
  `gatanayak` varchar(6) DEFAULT NULL,
  `notes` text,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`contact_id`),
  KEY `shakha_id` (`shakha_id`),
  FULLTEXT KEY `first_name` (`first_name`,`last_name`,`company`,`position`,`city`,`notes`,`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2931 ;

-- --------------------------------------------------------

--
-- Table structure for table `Table_Domain`
--

CREATE TABLE IF NOT EXISTS `Table_Domain` (
  `Table_Name` varchar(50) NOT NULL,
  `Colum_Name` varchar(50) NOT NULL,
  `DOM_NAME` varchar(40) NOT NULL,
  `LAST_MOD_DT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LAST_MOD_USER` varchar(20) NOT NULL DEFAULT 'System',
  KEY `Table_Name` (`Table_Name`,`Colum_Name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `status` enum('Pending','Close') NOT NULL DEFAULT 'Pending',
  `message` text NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_add` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `variables`
--

CREATE TABLE IF NOT EXISTS `variables` (
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
