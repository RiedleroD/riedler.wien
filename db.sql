-- mysql dump-ish format, meant to be looked at and/or imported
-- zero guarantees that this will work at all

--
-- Global tables
--

CREATE TABLE `Users` (
	`id` int(11) NOT NULL,
	`name` varchar(32) NOT NULL,
	`passwd` varchar(64), -- nologin if NULL
	`type` enum('Placeholder','User','Admin') NOT NULL,
	PRIMARY KEY (`id`)
);
INSERT INTO `Users` VALUES
	(0,'Nobody',null,'Placeholder'),
	(1,'Riedler',null,'Admin'); --replace null with sha256sum of your password

CREATE TABLE `Filetypes` (
	`id` int(11) NOT NULL,
	`name` varchar(16) NOT NULL,
	`mime` varchar(22) NOT NULL,
	`prio` int(11) NOT NULL,
	`ext` varchar(6) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `prio` (`prio`)
);
INSERT INTO `Filetypes` VALUES
	(1,'Ogg/Opus','audio/opus',0,'opus'),
	(2,'Ogg/Vorbis','audio/ogg',1,'ogg'),
	(3,'mp3','audio/mpeg',2,'mp3'),
	(4,'AAC','audio/aac',3,'aac'),
	(5,'Dolby AC-3','audio/ac3',4,'ac3'),
	(6,'ALAC','audio/x-m4a',11,'m4a'),
	(7,'WavPack','audio/x-wavpack',12,'wv'),
	(10,'Flac','audio/flac',10,'flac'),
	(20,'wav','audio/wav',20,'wav');

--
-- Music-related tables
--

CREATE TABLE `SongServices` (
	`id` int(11) NOT NULL,
	`abbr` varchar(5) NOT NULL,
	`prio` int(11) NOT NULL,
	`songlink` varchar(255),
	`mylink` varchar(255),
	`name` varchar(32) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `prio` (`prio`)
);
INSERT INTO `SongServices` VALUES
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
	(20,'sz',20,'www.shazam.com/track/','www.shazam.com/gb/artist/riedler/1544612571','Shazam'),
	(21,'vm',21,'vimeo.com/','vimeo.com/user125791194','Vimeo'),
	(22,'td',22,'tidal.com/browse/track/','tidal.com/browse/artist/22622017','Tidal'),
	(23,'ne',23,'music.163.com/song?id=','music.163.com/#/artist?id=46345905','NetEase'),
	(24,'qq',24,'y.qq.com/n/ryqq/songDetail/','y.qq.com/n/ryqq/singer/0031Xw4m1zLAUa','QQ'),
	(25,'vn',25,'vibe.naver.com/track/','vibe.naver.com/artist/4810478','Vibe/Naver'),
	(26,'psh',300,'push.fm/fl/','push.fm/','push.fm'),
	(27,'zng',26,'zingmp3.vn/bai-hat/','zingmp3.vn/nghe-si/Riedler.IW77ZI80','Zing MP3'),
	(28,'flo',27,'music-flo.com/detail/track/','music-flo.com/detail/artist/edlizeazz/track','FLO'),
	(29,'bgs',28,'music.bugs.co.kr/track/','music.bugs.co.kr/artist/12456842','Bugs!');

CREATE TABLE `Songs` (
	`id` int(11) NOT NULL,
	`name` varchar(64) DEFAULT NULL,
	`type` enum('RRemix','RRequested','Original','Commission') NOT NULL,
	`status` enum('Planned','Drafted','Finished','Uploaded','Removed') NOT NULL,
	`requesterid` int(11) DEFAULT NULL,
	`date` date DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `requesterid` (`requesterid`),
	FOREIGN KEY (`requesterid`) REFERENCES `Users` (`id`)
);

CREATE TABLE `SongVotes` (
	`songid` int(11) NOT NULL,
	`userid` int(11) NOT NULL,
	`type` enum('Like','Dislike') NOT NULL,
	PRIMARY KEY (`songid`,`userid`),
	FOREIGN KEY (`songid`) REFERENCES `Songs` (`id`),
	FOREIGN KEY (`userid`) REFERENCES `Users` (`id`)
);

CREATE TABLE `SongLinks` (
	`songid` int(11) NOT NULL,
	`serviceid` int(11) NOT NULL,
	`link` varchar(64) NOT NULL,
	PRIMARY KEY (`songid`,`serviceid`),
	KEY `serviceid` (`serviceid`),
	FOREIGN KEY (`songid`) REFERENCES `Songs` (`id`),
	FOREIGN KEY (`serviceid`) REFERENCES `SongServices` (`id`)
);

CREATE TABLE `SongFiles` (
	`songid` int(11) NOT NULL,
	`filetypeid` int(11) NOT NULL,
	PRIMARY KEY (`songid`,`filetypeid`),
	KEY `filetypeid` (`filetypeid`),
	FOREIGN KEY (`songid`) REFERENCES `Songs` (`id`),
	FOREIGN KEY (`filetypeid`) REFERENCES `Filetypes` (`id`)
);

CREATE TABLE `SongComments` ( -- separate from Songs because they'll build the basis for later user-comments
	`songid` int(11) NOT NULL,
	`comment` varchar(1023) NOT NULL,
	PRIMARY KEY (`songid`),
	FOREIGN KEY (`songid`) REFERENCES `Songs` (`id`)
);

CREATE VIEW `SongsWithData` AS
SELECT
	`Songs`.`id` AS `id`,
	`Songs`.`name` AS `name`,
	`Songs`.`type` AS `type`,
	`Songs`.`status` AS `status`,
	`Users`.`name` AS `requester`,
	DATE_FORMAT(`Songs`.`date`,"%d.%m.%Y") AS `fdate`,
	`Songs`.`date` AS `date`,
	`SongComments`.`comment` AS `comment`,
	(SELECT COUNT(`sr`.`userid`) FROM `SongVotes` `sr` WHERE `sr`.`type`='Like' AND `sr`.`songid`=`Songs`.`id`) AS `likes`,
	(SELECT COUNT(`sr`.`userid`) FROM `SongVotes` `sr` WHERE `sr`.`type`='Dislike' AND `sr`.`songid`=`Songs`.`id`) AS `dislikes`
FROM `Songs`
	LEFT JOIN `Users` ON `Songs`.`requesterid`=`Users`.`id`
	LEFT JOIN `SongComments` ON `SongComments`.`songid`=`Songs`.`id`;



--
-- coding-related tables
--

CREATE TABLE `CodingProjects` (
	`id` int(11) NOT NULL,
	`name` varchar(24) NOT NULL,
	`shortdesc` varchar(1024) NOT NULL,
	`status` enum('Active','Maintained','On Hold','Dead') NOT NULL,
	`date` date NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `CodingServices` (
	`id` int(11) NOT NULL,
	`abbr` varchar(5) NOT NULL,
	`prio` int NOT NULL,
	`plink` varchar(255),
	`mylink` varchar(255),
	`name` varchar(32) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `prio` (`prio`),
	UNIQUE KEY `abbr` (`abbr`)
);
INSERT INTO `CodingServices` VALUES
	(0,'lnk',255,'','','Custom Link'),
	(1,'gh',4,'github.com/RiedleroD/','github.com/RiedleroD','GitHub'),
	(2,'cb',3,'codeberg.org/Riedler/','codeberg.org/Riedler','CodeBerg'),
	(3,'usw',1,'userstyles.world/style/','userstyles.world/user/riedler','Userstyles.world'),
	(4,'pypi',2,'pypi.org/project/','pypi.org/user/Riedler/','PyPI');

CREATE TABLE `CodingLinks` (
	`projectid` int(11) NOT NULL,
	`serviceid` int(11) NOT NULL,
	`link` varchar(64) NOT NULL,
	PRIMARY KEY (`projectid`,`serviceid`),
	KEY `projectid` (`projectid`),
	KEY `serviceid` (`serviceid`),
	FOREIGN KEY (`projectid`) REFERENCES `CodingProjects` (`id`),
	FOREIGN KEY (`serviceid`) REFERENCES `CodingServices` (`id`)
);
