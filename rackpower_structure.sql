SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `rackpower` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `rackpower`;

DROP TABLE IF EXISTS `entities`;
CREATE TABLE IF NOT EXISTS `entities` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,             -- internal ID field
  `Type` int(11) NOT NULL,                          -- specifies consumer/provider/other
  `Group` int(11) DEFAULT NULL,                     -- ID of assigned group
  `Rack` int(11) unsigned NOT NULL,                 -- ID of rack the entity is in
  `Position` int(11) unsigned NOT NULL,             -- position of entity in the rack
  `Height` int(11) unsigned NOT NULL DEFAULT '1',   -- units occupied by entity
  `Hardware` varchar(32) NOT NULL,                  -- displayed name of entity
  `Name` varchar(32) DEFAULT NULL,                  -- not used
  `Comment` varchar(192) DEFAULT NULL,              -- notes on entity

                                                    -- consumer fields:
  `Ref1` int(11) DEFAULT NULL,                      -- ID of power source 1
  `Ref2` int(11) DEFAULT NULL,                      -- ID of power source 2
  `Ref3` int(11) DEFAULT NULL,                      -- ID of power source 3
  `Ref4` int(11) DEFAULT NULL,                      -- ID of power source 4
  `RefFlags` int(11) DEFAULT '0',                   -- bitmask indicates which power sources are enabled
  `TotalLoad` int(11) DEFAULT NULL,                 -- total load of consumer in watts

                                                    -- provider fields:
  `Capacity` int(11) DEFAULT NULL,                  -- capacity of provider in watts
  `FormulaA` float DEFAULT NULL,                    -- constant A in the runtime formula R = A * e^(B*W)
  `FormulaB` float DEFAULT NULL,                    -- constant B in the runtime formula
  
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

