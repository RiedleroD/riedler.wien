-- DO NOT EXECUTE THIS FILE
-- it's only a collection of sql commands to help migrate between versions
-- it's meant to be looked at and understood

-- if you want to migrate from one commit to another,
-- look at the commands between those commit hashes
-- the last commit hash isn't specified, naturally

-- bd1a3793770c86f2f4ae57fb478267c272b29cbb

create database rwienusers;

CREATE TABLE rwienusers.Users(
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `passwd` varchar(32),
  `type` enum('Placeholder','User','Admin') NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO rwienusers.Users (`id`,`name`,`passwd`)
SELECT `id`,`name`,null FROM rwienmusic.Users;

SET foreign_key_checks = 0;

DROP TABLE rwienmusic.Users;
CREATE VIEW rwienmusic.Users AS SELECT * FROM rwienusers.Users;

SET foreign_key_checks = 1;

update Users set type='Admin' where id=1; -- assuming admin is id=1, which is, you know, logical.