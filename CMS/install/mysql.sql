--SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;

CREATE TABLE IF NOT EXISTS `{pre}acl` (
`UID` int(11) unsigned NOT NULL,
`CatID` int(11) unsigned NOT NULL,
`all` tinyint(2) NOT NULL,
`type` varchar(9) NOT NULL,
KEY CatID (CatID), KEY UID (UID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}admmenu` (
`ID` varchar(9) NOT NULL,
`text` varchar(30) NOT NULL,
`file` varchar(30) NOT NULL,
`menu` tinyint(1) NOT NULL,
`rights` tinyint(1) NOT NULL,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}answers` (
`ID` int(11) NOT NULL auto_increment,
`IDP` int(10) unsigned NOT NULL,
`seq` int(11),
`a` varchar(200),
`num` int(11) NOT NULL,
PRIMARY KEY (ID)) ENGINE=InnoDB;

-- CREATE TABLE IF NOT EXISTS `{pre}artrates` (`user` varchar(100) NOT NULL,`ID` int(11) NOT NULL,`rate` varchar(100) NOT NULL default '') ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}arts` (
`ID` int(11) unsigned NOT NULL auto_increment,
`cat` int(11) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text NOT NULL,
`date` date,
`author` varchar(50) NOT NULL,
`rate` varchar(4),
`access` tinyint(4) NOT NULL,
`priority` tinyint(1) NOT NULL,
`pages` tinyint(2) NOT NULL,
`ent` int(11) NOT NULL,
PRIMARY KEY (ID), KEY cat (cat)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}artstxt` (
`ID` int(11) unsigned NOT NULL,
`page` tinyint(4) NOT NULL,
`cat` int(11) NOT NULL,
`text` text,
`opt` tinyint(2) NOT NULL,
PRIMARY KEY (ID,page)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}banners` (
`ID` int(11) unsigned NOT NULL auto_increment,
`gen` tinyint(2) NOT NULL,
`name` varchar(50),
`ison` tinyint(1),
`code` text,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}cats` (
`ID` int(11) unsigned NOT NULL auto_increment,
`name` varchar(50) NOT NULL,
`dsc` varchar(255),
`access` varchar(9) NOT NULL,
`type` tinyint(1) NOT NULL DEFAULT 5,
`sc` int(11) unsigned NOT NULL,
`sort` tinyint(1) NOT NULL,
`text` mediumtext,
`num` int(10) unsigned NOT NULL,
`nums` int(10) unsigned NOT NULL,
`opt` tinyint(2) NOT NULL,
`lft` tinyint(4) unsigned NOT NULL,
`rgt` tinyint(4) unsigned NOT NULL,
PRIMARY KEY (ID), KEY sc (sc), KEY pos (lft,rgt)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}comms` (
`ID` int(11) unsigned NOT NULL auto_increment,
`TYPE` tinyint(1) NOT NULL,
`CID` int(11) unsigned NOT NULL,
`access` tinyint(1) NOT NULL,
`name` varchar(50) NOT NULL,
`author` varchar(50) NOT NULL,
`guest` tinyint(1) NOT NULL,
`ip` varchar(20) NOT NULL,
`date` datetime,
`text` text,
PRIMARY KEY (ID), KEY th (TYPE,CID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}confmenu` (
`ID` varchar(50) NOT NULL,
`name` varchar(50),
`lang` varchar(5) NOT NULL,
`img` varchar(230),
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}files` (
`ID` int(11) unsigned NOT NULL auto_increment,
`cat` int(11) unsigned NOT NULL,
`name` varchar(50),
`author` varchar(100),
`date` date,
`dsc` mediumtext,
`file` varchar(200),
`dls` int(11),
`access` tinyint(1),
`size` varchar(50),
`priority` tinyint(1),
`rate` varchar(4),
`fulld` text,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}fnews` (
`ID` int(11) unsigned NOT NULL,
`cat` int(11) unsigned NOT NULL,
`text` text,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}groups` (
`ID` int(11) unsigned NOT NULL auto_increment,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` varchar(3) NOT NULL,
`opened` tinyint(1) NOT NULL,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}imgs` (
`ID` int(11) unsigned NOT NULL auto_increment,
`cat` int(11) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`type` tinyint(1) NOT NULL,
`date` date,
`priority` tinyint(1) NOT NULL,
`access` tinyint(1) NOT NULL,
`rate` varchar(4),
`author` varchar(50) NOT NULL,
`filem` varchar(255) NOT NULL,
`file` varchar(255) NOT NULL,
`size` varchar(9) NOT NULL,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}links` (
`ID` int(11) unsigned NOT NULL auto_increment,
`cat` int(11) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` tinyint(1) NOT NULL,
`adr` varchar(255) NOT NULL,
`priority` tinyint(1) NOT NULL,
`count` int(11) NOT NULL,
`nw` tinyint(1) NOT NULL,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}log` (
`ID` int(11) unsigned NOT NULL auto_increment,
`name` varchar(50) NOT NULL,
`date` datetime,
`ip` varchar(40) NOT NULL,
`user` int(11) unsigned NOT NULL,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}menu` (
`ID` int(11) unsigned NOT NULL auto_increment,
`seq` int(11) NOT NULL,
`text` varchar(50) NOT NULL,
`disp` varchar(3) NOT NULL,
`menu` int(11) unsigned NOT NULL,
`type` tinyint(1) NOT NULL,
`img` varchar(200) NOT NULL,
`value` text,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}mitems` (
`seq` int(11) NOT NULL,
`menu` int(11) unsigned NOT NULL,
`text` varchar(50) NOT NULL,
`url` varchar(255) NOT NULL,
`nw` tinyint(1) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}news` (
`ID` int(11) unsigned NOT NULL auto_increment,
`cat` int(11) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`txt` text,
`date` datetime,
`author` varchar(50) NOT NULL,
`img` varchar(255) NOT NULL,
`comm` int(11) unsigned NOT NULL,
`access` tinyint(1) NOT NULL,
`opt` tinyint(2) NOT NULL,
PRIMARY KEY (ID), KEY cat (cat)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}online` (
`IP` varchar(40) NOT NULL,
`user` int(11) unsigned NOT NULL,
`time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
`site` varchar(50),
PRIMARY KEY (IP)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}pages` (
`ID` int(11) unsigned NOT NULL auto_increment,
`name` varchar(50) NOT NULL,
`access` varchar(3) NOT NULL,
`opt` tinyint(2) unsigned NOT NULL,
`text` mediumtext,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}plugins` (
`ID` varchar(30) NOT NULL,
`name` varchar(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}pms` (
`ID` int(11) unsigned NOT NULL auto_increment,
`topic` varchar(50) NOT NULL,
`usr` int(11) unsigned NOT NULL,
`owner` int(11) unsigned NOT NULL,
`st` tinyint(1) NOT NULL,
`date` datetime,
`bbc` tinyint(1) NOT NULL,
`txt` text,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}polls` (
`ID` int(11) unsigned NOT NULL auto_increment,
`name` varchar(50) NOT NULL,
`q` varchar(80) NOT NULL,
`ison` tinyint(1) NOT NULL,
`type` tinyint(1) NOT NULL,
`num` int(11) NOT NULL,
`access` varchar(3) NOT NULL,
`date` date,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}pollvotes` (
`user` varchar(40) NOT NULL,
`ID` int(11) unsigned NOT NULL,
`date` date,
PRIMARY KEY (ID)) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}tmp` (
`KEYID` varchar(50) NOT NULL,
`UID` int(11) unsigned NOT NULL,
`type` varchar(9) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `{pre}users` (
`ID` int(11) unsigned NOT NULL auto_increment,
`login` varchar(50) NOT NULL,
`pass` char(32) NOT NULL,
`mail` varchar(80) NOT NULL,
`mvis` tinyint(1) NOT NULL,
`gid` int(11) unsigned NOT NULL DEFAULT 1,
`lv` tinyint(1) NOT NULL DEFAULT 1,
`adm` text,
`regt` date,
`lvis` datetime,
`pms` tinyint(4) unsigned NOT NULL,
`about` text,
`mails` tinyint(1) NOT NULL,
`www` varchar(200) NOT NULL,
`city` varchar(50) NOT NULL,
`icq` varchar(15) NOT NULL,
`skype` varchar(50) NOT NULL,
`tlen` varchar(50) NOT NULL,
`gg` int(11),
PRIMARY KEY (ID), UNIQUE KEY login (login)) ENGINE=InnoDB;

COMMIT;
SET AUTOCOMMIT=1;