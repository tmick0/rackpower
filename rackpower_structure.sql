SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `rackpower` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `rackpower`;

DROP TABLE IF EXISTS `entities`;
CREATE TABLE IF NOT EXISTS `entities` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` int(11) NOT NULL,
  `Group` int(11) DEFAULT NULL,
  `Rack` int(11) unsigned NOT NULL,
  `Position` int(11) unsigned NOT NULL,
  `Height` int(11) unsigned NOT NULL DEFAULT '1',
  `Hardware` varchar(32) NOT NULL,
  `Name` varchar(32) DEFAULT NULL,
  `Comment` varchar(192) DEFAULT NULL,
  `Ref1` int(11) DEFAULT NULL,
  `Ref2` int(11) DEFAULT NULL,
  `Ref3` int(11) DEFAULT NULL,
  `Ref4` int(11) DEFAULT NULL,
  `RefFlags` int(11) DEFAULT '0',
  `TotalLoad` int(11) DEFAULT NULL,
  `Capacity` int(11) DEFAULT NULL,
  `FormulaA` float DEFAULT NULL,
  `FormulaB` float DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Rack_idx` (`Rack`),
  KEY `Type_idx` (`Type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Color` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `racks`;
CREATE TABLE IF NOT EXISTS `racks` (
  `RackId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`RackId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

