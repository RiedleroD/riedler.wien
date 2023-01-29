-- MySQL dump 10.13  Distrib 5.5.60, for debian-linux-gnu (x86_64)

use rwienusers;

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `passwd` varchar(64), -- impossible to login if NULL
  `type` enum('Placeholder','User','Admin') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- insert null user
INSERT INTO `Users` (`id`,`name`,`passwd`,`type`)
VALUES (0,'Nobody',null,'Placeholder'),
(1,'Riedler',null,'Admin'); --replace null with sha256sum of your password