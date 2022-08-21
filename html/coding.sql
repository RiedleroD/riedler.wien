-- MySQL dump 10.13  Distrib 5.5.60, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: rwiencoding
-- ------------------------------------------------------
-- Server version	5.5.60-0+deb8u1

--
-- Table structure for table `Projects`
--

CREATE TABLE `Projects` (
  `id` int(11) NOT NULL,
  `name` varchar(24) NOT NULL,
  `shortdesc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Services`
--

CREATE TABLE `Services` (
  `id` int(11),
  `abbr` varchar(5) NOT NULL,
  `prio` int,
  `name` varchar(32) NOT NULL,
  `mylink` varchar(255),
  `plink` varchar(255),
  PRIMARY KEY (`id`),
  UNIQUE KEY `prio` (`prio`),
  UNIQUE KEY `abbr` (`abbr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Services`
--

INSERT INTO `Services` VALUES
  (1,'gh',3,'github.com/RiedleroD/','github.com/RiedleroD','GitHub'),
  (2,'cb',2,'codeberg.org/Riedler/','codeberg.org/Riedler','CodeBerg'),
  (3,'usw',1,'userstyles.world/style/','userstyles.world/user/riedler','Userstyles.world');

--
-- Table structure for table `Links`
--

CREATE TABLE `Links` (
  `projectid` int(11) NOT NULL,
  `serviceid` int(11) NOT NULL,
  `link` varchar(64) NOT NULL,
  PRIMARY KEY (`projectid`,`serviceid`),
  KEY `serviceid` (`serviceid`),
  FOREIGN KEY (`projectid`) REFERENCES `Projects` (`id`),
  FOREIGN KEY (`serviceid`) REFERENCES `Services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump completed on 2022-08-04  9:38:30
