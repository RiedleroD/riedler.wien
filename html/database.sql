-- MySQL dump 10.13  Distrib 5.5.60, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: rwienmusic
-- ------------------------------------------------------
-- Server version	5.5.60-0+deb8u1

--
-- Table structure for table `Files`
--

CREATE TABLE `Files` (
  `songid` int(11) NOT NULL,
  `filetypeid` int(11) NOT NULL,
  PRIMARY KEY (`songid`,`filetypeid`),
  KEY `filetypeid` (`filetypeid`),
  CONSTRAINT `Files_ibfk_1` FOREIGN KEY (`songid`) REFERENCES `Songs` (`id`),
  CONSTRAINT `Files_ibfk_2` FOREIGN KEY (`filetypeid`) REFERENCES `Filetypes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Filetypes`
--

CREATE TABLE `Filetypes` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `mime` varchar(22) NOT NULL,
  `prio` int(11) NOT NULL,
  `ext` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prio` (`prio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Filetypes`
--

INSERT INTO `Filetypes` VALUES
  (1,'Ogg/Opus','audio/opus',0,'opus'),
  (2,'Ogg/Vorbis','audio/ogg',1,'ogg'),
  (3,'mp3','audio/mpeg',2,'mp3'),
  (4,'Flac','audio/flac',3,'flac'),
  (5,'wav','audio/wav',4,'wav');

--
-- Table structure for table `Services`
--

CREATE TABLE `Services` (
  `id` int(11) NOT NULL,
  `abbr` varchar(5) NOT NULL,
  `prio` int(11) NOT NULL,
  `songlink` varchar(255),
  `mylink` varchar(255),
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prio` (`prio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Services`
--

INSERT INTO `Services` VALUES
  (1,'yt',1,'youtu.be/','youtube.com/channel/UC0aIZx6FIHB5Fq_sr0TMXSw','youtube'),
  (2,'lmms',2,'lmms.io/lsp/?action=show&file=','lmms.io/lsp/?action=browse&user=Riedler','LMMS'),
  (3,'pt',3,'www.patreon.com/posts/','patreon.com/RiedlerM','Patreon'),
  (4,'lbry',4,'lbry://@Riedler:6/','lbry://@Riedler:6','LBRY'),
  (5,'oy',5,'odysee.com/@Riedler:6/','odysee.com/@Riedler:6','Odysee'),
  (7,'bl',6,'www.bandlab.com/riedler/','bandlab.com/riedler','BandLab'),
  (8,'sy',7,'open.spotify.com/track/','open.spotify.com/artist/7k9sRjqYP68ZI8Bw8BwmuG','Spotify'),
  (9,'ht',8,'hearthis.at/riedler/','hearthis.at/riedler','Hearthis'),
  (10,'bp',9,'boomplay.com/songs/','www.boomplay.com/artists/19145926','Boomplay'),
  (11,'az',10,'amazon.com/dp/','www.amazon.com/s?k=Riedler&i=digital-music&search-type=ss','Amazon'),
  (12,'am',11,'music.amazon.com/albums/','music.amazon.com/artists/B08QG41MYN/riedler','Amazon Music'),
  (13,'ih',12,'www.iheart.com/artist/riedler-36682916/songs/','www.iheart.com/artist/riedler-36682916/','iHeartRadio'),
  (14,'sc',13,'soundcloud.com/riedler-music/','soundcloud.com/riedler-music','SoundCloud'),
  (15,'sc',14,'soundcloud.com/riedler-musics/','soundcloud.com/riedler-musics','SoundCloud'),
  (16,'apm',15,'music.apple.com/gb/album/','music.apple.com/gb/artist/riedler/1544612571','Apple Music'),
  (17,'yx',16,'music.yandex.com/album/','music.yandex.com/artist/10521437','Yandex Music'),
  (18,'sbzv',17,'sber-zvuk.com/release/','sber-zvuk.com/artist/157131',0xD0A1D0B1D0B5D180D097D0B2D183D0BA),
  (19,'dz',18,'www.deezer.com/en/track/','www.deezer.com/en/artist/116666602','Deezer'),
  (20,'sz',20,'www.shazam.com/track/','www.shazam.com/gb/artist/211983431','Shazam'),
  (21,'vm',21,'vimeo.com/','vimeo.com/user125791194','Vimeo'),
  (22,'td',22,'tidal.com/browse/track/','tidal.com/browse/artist/22622017','Tidal'),
  (23,'ne',23,'music.163.com/song?id=','music.163.com/#/artist?id=46345905','NetEase'),
  (24,'qq',24,'y.qq.com/n/ryqq/songDetail/','y.qq.com/n/ryqq/singer/0031Xw4m1zLAUa','QQ'),
  (25,'vn',25,'vibe.naver.com/track/','vibe.naver.com/artist/4810478','Vibe/Naver'),
  (26,'psh',26,'push.fm/fl/','push.fm/','push.fm');

--
-- Table structure for table `Songs`
--

CREATE TABLE `Songs` (
  `id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `type` enum('rremix','rrequested','original','commission') DEFAULT NULL,
  `status` enum('planned','drafted','finished','uploaded') DEFAULT NULL,
  `requesterid` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `requesterid` (`requesterid`),
  FOREIGN KEY (`requesterid`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Links`
--

CREATE TABLE `Links` (
  `songid` int(11) NOT NULL,
  `serviceid` int(11) NOT NULL,
  `link` varchar(64) NOT NULL,
  PRIMARY KEY (`songid`,`serviceid`),
  KEY `serviceid` (`serviceid`),
  FOREIGN KEY (`songid`) REFERENCES `Songs` (`id`),
  FOREIGN KEY (`serviceid`) REFERENCES `Services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `requests`
--

CREATE TABLE `Requests` (
  `id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `ytid` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ytid` (`ytid`),
  KEY `userid` (`userid`),
  FOREIGN KEY (`userid`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump completed on 2022-08-04  9:38:30
