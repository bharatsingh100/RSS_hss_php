-- MySQL dump 10.11
--
-- Host: localhost    Database: devcrm_crm
-- ------------------------------------------------------
-- Server version	5.0.45-community-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Domain`
--

DROP TABLE IF EXISTS `Domain`;
CREATE TABLE `Domain` (
  `DOM_ID` smallint(6) NOT NULL auto_increment,
  `DOM_NAME` varchar(40) NOT NULL,
  `LAST_MOD_DT` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `LAST_MOD_USER` varchar(20) NOT NULL default 'System',
  `ACTIVE` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`DOM_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Table structure for table `Ref_Code`
--

DROP TABLE IF EXISTS `Ref_Code`;
CREATE TABLE `Ref_Code` (
  `REF_ID` smallint(5) unsigned zerofill NOT NULL auto_increment,
  `DOM_ID` smallint(6) NOT NULL,
  `REF_CODE` varchar(10) NOT NULL,
  `short_desc` varchar(30) NOT NULL,
  `long_desc` varchar(50) NOT NULL,
  `LAST_MOD_DT` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `LAST_MOD_USER` varchar(20) NOT NULL default 'System',
  PRIMARY KEY  (`REF_ID`),
  KEY `DOM_ID` (`DOM_ID`,`REF_CODE`),
  KEY `REF_CODE` (`REF_CODE`)
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=latin1;

--
-- Table structure for table `Table_Domain`
--

DROP TABLE IF EXISTS `Table_Domain`;
CREATE TABLE `Table_Domain` (
  `Table_Name` varchar(50) NOT NULL,
  `Colum_Name` varchar(50) NOT NULL,
  `DOM_NAME` varchar(40) NOT NULL,
  `LAST_MOD_DT` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `LAST_MOD_USER` varchar(20) NOT NULL default 'System',
  KEY `Table_Name` (`Table_Name`,`Colum_Name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `session_start` int(10) unsigned NOT NULL default '0',
  `session_last_activity` int(10) unsigned NOT NULL default '0',
  `session_ip_address` varchar(16) NOT NULL default '0',
  `session_user_agent` varchar(50) NOT NULL,
  `session_data` text NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `lists`
--

DROP TABLE IF EXISTS `lists`;
CREATE TABLE `lists` (
  `id` mediumint(8) NOT NULL auto_increment,
  `address` varchar(40) default NULL,
  `status` enum('Active','Creating','Deleting') NOT NULL,
  `size` int(8) default NULL,
  `style` int(1) NOT NULL,
  `level` varchar(2) NOT NULL,
  `level_id` varchar(5) NOT NULL,
  `members` text NOT NULL,
  `owner` varchar(50) NOT NULL,
  `owner_pass` varchar(180) NOT NULL,
  `mod1` int(8) unsigned default NULL,
  `mod2` int(8) unsigned default NULL,
  `mod3` int(8) unsigned default NULL,
  `mod_pass` varchar(256) NOT NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Table structure for table `loginlog`
--

DROP TABLE IF EXISTS `loginlog`;
CREATE TABLE `loginlog` (
  `ID` int(9) NOT NULL auto_increment,
  `contact_id` varchar(8) NOT NULL,
  `name` varchar(60) NOT NULL,
  `ip_addr` varchar(18) NOT NULL,
  `login` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `logout` datetime default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=182 DEFAULT CHARSET=latin1;

--
-- Table structure for table `markers`
--

DROP TABLE IF EXISTS `markers`;
CREATE TABLE `markers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `address` varchar(80) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `type` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;

--
-- Table structure for table `pass_reset`
--

DROP TABLE IF EXISTS `pass_reset`;
CREATE TABLE `pass_reset` (
  `SN` int(9) NOT NULL auto_increment,
  `contact_id` varchar(8) NOT NULL,
  `email` varchar(60) NOT NULL,
  `enc_email` varchar(180) NOT NULL,
  `ip_addr` varchar(18) NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`SN`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `responsibilities`
--

DROP TABLE IF EXISTS `responsibilities`;
CREATE TABLE `responsibilities` (
  `swayamsevak_id` mediumint(8) NOT NULL,
  `shakha_id` mediumint(8) default NULL,
  `nagar_id` varchar(10) default NULL,
  `vibhag_id` varchar(10) default NULL,
  `sambhag_id` varchar(10) default NULL,
  `level` varchar(8) NOT NULL,
  `responsibility` varchar(8) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  KEY `swayamsevak_id` (`swayamsevak_id`),
  KEY `responsibility` (`responsibility`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `sankhyas`
--

DROP TABLE IF EXISTS `sankhyas`;
CREATE TABLE `sankhyas` (
  `sankhya_id` int(10) unsigned NOT NULL auto_increment,
  `shakha_id` int(10) unsigned NOT NULL,
  `contact_id` int(10) unsigned NOT NULL,
  `ip` varchar(18) NOT NULL,
  `date` date default NULL,
  `shishu_m` mediumint(4) unsigned default NULL,
  `shishu_f` mediumint(4) unsigned default NULL,
  `bala_m` mediumint(4) unsigned default NULL,
  `bala_f` mediumint(4) unsigned default NULL,
  `kishor_m` mediumint(4) unsigned default NULL,
  `kishor_f` mediumint(4) unsigned default NULL,
  `yuva_m` mediumint(4) unsigned default NULL,
  `yuva_f` mediumint(4) unsigned default NULL,
  `tarun_m` mediumint(4) unsigned default NULL,
  `tarun_f` mediumint(4) unsigned default NULL,
  `praudh_m` mediumint(4) unsigned default NULL,
  `praudh_f` mediumint(4) unsigned default NULL,
  `total` mediumint(4) unsigned default NULL,
  `shakha_info` text,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`sankhya_id`),
  KEY `date` (`date`),
  KEY `shakha_id` (`shakha_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Table structure for table `shakhas`
--

DROP TABLE IF EXISTS `shakhas`;
CREATE TABLE `shakhas` (
  `shakha_id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(60) default NULL,
  `address1` varchar(100) default NULL,
  `address2` varchar(100) default NULL,
  `city` varchar(60) default NULL,
  `state` char(2) default NULL,
  `zip` varchar(5) default NULL,
  `frequency` varchar(2) default NULL,
  `frequency_day` varchar(20) default NULL,
  `time_from` time default NULL,
  `time_to` time default NULL,
  `shakha_status` tinyint(1) default '1',
  `modified` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `last_mod` varchar(8) NOT NULL default 'System',
  `nagar_id` varchar(10) default NULL,
  `vibhag_id` varchar(10) NOT NULL,
  `sambhag_id` varchar(10) NOT NULL,
  PRIMARY KEY  (`shakha_id`),
  KEY `vibhag_id` (`vibhag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Table structure for table `swayamsevaks`
--

DROP TABLE IF EXISTS `swayamsevaks`;
CREATE TABLE `swayamsevaks` (
  `contact_id` mediumint(8) unsigned NOT NULL auto_increment,
  `household_id` mediumint(8) default NULL,
  `shakha_id` mediumint(8) default NULL,
  `contact_type` varchar(2) NOT NULL default 'GC',
  `first_name` varchar(60) default NULL,
  `last_name` varchar(60) default NULL,
  `gender` enum('M','F') default NULL,
  `birth_year` varchar(4) default NULL,
  `company` varchar(100) default NULL,
  `position` varchar(60) default NULL,
  `email` varchar(60) default NULL,
  `email_status` enum('Active','Unsubscribed','Bounced') default NULL,
  `password` varchar(256) NOT NULL,
  `passwordmd5` varchar(128) NOT NULL,
  `ph_mobile` varchar(12) default NULL,
  `ph_home` varchar(12) default NULL,
  `ph_work` varchar(12) default NULL,
  `street_add1` varchar(100) default NULL,
  `street_add2` varchar(100) default NULL,
  `city` varchar(60) default NULL,
  `state` char(2) default NULL,
  `zip` varchar(10) default NULL,
  `ssv_completed` varchar(20) default NULL,
  `gatanayak` varchar(6) default NULL,
  `notes` text,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`contact_id`),
  KEY `shakha_id` (`shakha_id`),
  FULLTEXT KEY `first_name` (`first_name`,`last_name`,`company`,`position`,`city`,`notes`,`email`)
) ENGINE=MyISAM AUTO_INCREMENT=279 DEFAULT CHARSET=latin1;

--
-- Table structure for table `zipcodes`
--

DROP TABLE IF EXISTS `zipcodes`;
CREATE TABLE `zipcodes` (
  `zip` varchar(5) NOT NULL,
  `town` varchar(256) NOT NULL,
  `state` varchar(2) NOT NULL,
  KEY `town` (`town`),
  KEY `state` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-01-03 17:02:25
