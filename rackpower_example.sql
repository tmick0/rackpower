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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=153 ;

INSERT INTO `entities` (`ID`, `Type`, `Group`, `Rack`, `Position`, `Height`, `Hardware`, `Name`, `Comment`, `Ref1`, `Ref2`, `Ref3`, `Ref4`, `RefFlags`, `TotalLoad`, `Capacity`, `FormulaA`, `FormulaB`) VALUES
(123, 2, 3, 0, 1, 2, 'UPS 2500', NULL, '', 0, 0, 0, 0, 0, 0, 2500, 45, -0.00035),
(124, 2, 3, 0, 3, 2, 'UPS 2500', NULL, '', 0, 0, 0, 0, 0, 0, 2500, 45, -0.00035),
(125, 1, 1, 0, 10, 2, 'www', NULL, '', 123, 124, 0, 0, 1, 185, 0, 0, 0),
(126, 1, 1, 0, 12, 4, 'db', NULL, '', 123, 124, 0, 0, 3, 210, 0, 0, 0),
(127, 1, 1, 0, 20, 1, 'dns', NULL, '', 124, 0, 0, 0, 1, 140, 0, 0, 0),
(129, 1, 1, 0, 21, 1, 'firewall', NULL, '', 123, 0, 0, 0, 1, 160, 0, 0, 0),
(130, 1, 5, 0, 32, 1, 'gigabit switch', NULL, '', 123, 124, 0, 0, 3, 80, 0, 0, 0),
(131, 1, 1, 1, 10, 2, 'nfs1', NULL, '', 132, 133, 0, 0, 3, 180, 0, 0, 0),
(132, 2, 3, 1, 1, 1, 'UPS 1000', NULL, '', 0, 0, 0, 0, 0, 0, 1000, 30, -0.00035),
(133, 2, 3, 1, 2, 1, 'UPS 1000', NULL, '', 0, 0, 0, 0, 0, 0, 1000, 30, -0.00035),
(134, 2, 3, 1, 3, 1, 'UPS 1000', NULL, '', 0, 0, 0, 0, 0, 0, 1000, 30, -0.00035),
(135, 2, 3, 1, 4, 1, 'UPS 1000', NULL, '', 0, 0, 0, 0, 0, 0, 1000, 30, -0.00035),
(136, 2, 3, 1, 6, 4, 'UPS 3000', NULL, '', 0, 0, 0, 0, 0, 0, 3000, 60, -0.0004),
(137, 3, 4, 1, 5, 1, 'shelf', NULL, '', 0, 0, 0, 0, 0, 0, 0, 0, 0),
(138, 1, 1, 1, 12, 2, 'nfs2', NULL, '', 134, 135, 0, 0, 3, 180, 0, 0, 0),
(139, 1, 2, 1, 30, 2, 'cluster_head', NULL, '', 136, 0, 0, 0, 1, 200, 0, 0, 0),
(140, 1, 2, 1, 29, 1, 'cluster_n0', NULL, '', 136, 0, 0, 0, 1, 150, 0, 0, 0),
(141, 1, 2, 1, 28, 1, 'cluster_n1', NULL, '', 136, 0, 0, 0, 1, 150, 0, 0, 0),
(142, 1, 2, 1, 27, 1, 'cluster_n2', NULL, '', 136, 0, 0, 0, 1, 150, 0, 0, 0),
(143, 1, 2, 1, 26, 1, 'cluster_n3', NULL, '', 136, 0, 0, 0, 1, 150, 0, 0, 0),
(144, 1, 5, 1, 32, 1, 'cluster gbit', NULL, '', 136, 0, 0, 0, 1, 80, 0, 0, 0),
(145, 1, 1, 1, 14, 2, 'nfs3', NULL, '', 132, 133, 0, 0, 3, 180, 0, 0, 0),
(146, 1, 1, 1, 16, 2, 'nfs4', NULL, '', 134, 135, 0, 0, 3, 180, 0, 0, 0),
(147, 1, 2, 1, 25, 1, 'cluster_n4', NULL, '', 136, 0, 0, 0, 1, 150, 0, 0, 0),
(148, 1, 2, 1, 24, 1, 'cluster_n5', NULL, '', 136, 0, 0, 0, 1, 150, 0, 0, 0),
(149, 1, 2, 0, 24, 1, 'kvm switch', NULL, '', 123, 0, 0, 0, 1, 10, 0, 0, 0),
(150, 1, 2, 0, 16, 1, 'kvm console', NULL, '', 123, 0, 0, 0, 1, 60, 0, 0, 0),
(151, 1, 1, 0, 19, 1, 'mail', NULL, '', 124, 0, 0, 0, 1, 140, 0, 0, 0);

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Color` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `groups` (`ID`, `Name`, `Color`) VALUES
(1, 'Core Servers', '#e88052'),
(2, 'Aux Services', '#49d6c7'),
(3, 'Power', '#30f05d'),
(4, 'Other', '#b9b8b8'),
(5, 'Network', '#ca67df');

DROP TABLE IF EXISTS `racks`;
CREATE TABLE IF NOT EXISTS `racks` (
  `RackId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`RackId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `racks` (`RackId`) VALUES
(0),
(1);

