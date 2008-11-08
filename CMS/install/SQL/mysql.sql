CREATE TABLE IF NOT EXISTS `{pre}acl` (
`UID` int(11) NOT NULL,
`CatID`int(11) NOT NULL,
`type` varchar(9) NOT NULL,
PRIMARY KEY(UID,CatID));

CREATE TABLE IF NOT EXISTS `{pre}admmenu` (
`ID` varchar(9) PRIMARY KEY NOT NULL,
`text` varchar(30) NOT NULL,
`file` varchar(30) NOT NULL,
`menu` tinyint(1) NOT NULL,
`rights` tinyint(1) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}answers` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`IDP` int(10) NOT NULL,
`seq` int(11),
`a` varchar(200),
`num` int(11) NOT NULL DEFAULT 0);

CREATE TABLE IF NOT EXISTS `{pre}arts` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text NOT NULL,
`date` datetime,
`author` varchar(50) NOT NULL,
`rate` tinyint(1),
`access` tinyint(4) NOT NULL,
`priority` tinyint(1) NOT NULL,
`pages` tinyint(2) NOT NULL,
`ent` int(11) NOT NULL DEFAULT 0);

CREATE TABLE IF NOT EXISTS `{pre}artstxt` (
`ID` int(11) NOT NULL,
`page` tinyint(4) NOT NULL,
`cat` int(11) NOT NULL,
`text` mediumtext,
`opt` tinyint(2) NOT NULL,
PRIMARY KEY (ID,page));

CREATE TRIGGER `{pre}artsd` AFTER DELETE ON `{pre}arts` FOR EACH ROW
BEGIN
	DELETE FROM `{pre}artstxt` WHERE ID = old.ID;
	DELETE FROM `{pre}comms` WHERE TYPE = 1 AND CID = old.ID;
END;

CREATE TABLE IF NOT EXISTS `{pre}banners` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`gen` tinyint(2) NOT NULL,
`name` varchar(50),
`ison` tinyint(1),
`code` text);

CREATE TABLE IF NOT EXISTS `{pre}cats` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`name` varchar(50) NOT NULL,
`dsc` varchar(255),
`access` varchar(9) NOT NULL,
`type` tinyint(1) NOT NULL DEFAULT 5,
`sc` int(11) NOT NULL DEFAULT 0,
`sort` tinyint(1) NOT NULL DEFAULT 0,
`text` text,
`num` int(10) NOT NULL DEFAULT 0,
`nums` int(10) NOT NULL DEFAULT 0,
`opt` tinyint(2) NOT NULL,
`lft` tinyint(4) NOT NULL,
`rgt` tinyint(4) NOT NULL);

CREATE INDEX sc ON {pre}cats (sc);

CREATE INDEX pos ON {pre}cats (lft,rgt);

CREATE TABLE IF NOT EXISTS `{pre}comms` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`TYPE` tinyint(1) NOT NULL,
`CID` int(11) NOT NULL,
`access` tinyint(1) NOT NULL,
`name` varchar(50) NOT NULL,
`author` varchar(50) NOT NULL,
`guest` tinyint(1) NOT NULL DEFAULT 1,
`ip` varchar(20) NOT NULL,
`date` int(11),
`text` text);

CREATE INDEX th ON {pre}comms (TYPE,CID);

CREATE TABLE IF NOT EXISTS `{pre}confmenu` (
`ID` varchar(50) NOT NULL,
`name` varchar(50),
`lang` varchar(5) NOT NULL,
`img` varchar(230),
PRIMARY KEY (ID));

CREATE TABLE IF NOT EXISTS `{pre}files` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`cat` int(11) NOT NULL,
`name` varchar(50),
`author` varchar(100),
`date` datetime,
`dsc` text,
`file` varchar(200),
`dls` int(11) NOT NULL DEFAULT 0,
`access` tinyint(1) NOT NULL,
`size` varchar(50),
`priority` tinyint(1) NOT NULL,
`rate` tinyint(1),
`fulld` mediumtext);

CREATE TRIGGER `{pre}filesd` AFTER DELETE ON `{pre}files` FOR EACH ROW
BEGIN
	DELETE FROM `{pre}comms` WHERE TYPE = 2 AND CID = old.ID;
END;

CREATE TABLE IF NOT EXISTS `{pre}fnews` (
`ID` int(11) NOT NULL,
`cat` int(11) NOT NULL,
`text` mediumtext,
PRIMARY KEY (ID));

CREATE TABLE IF NOT EXISTS `{pre}groups` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` varchar(3) NOT NULL,
`opened` tinyint(1) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}imgs` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`type` tinyint(1) NOT NULL,
`date` datetime,
`priority` tinyint(1) NOT NULL,
`access` tinyint(1) NOT NULL,
`rate` tinyint(1),
`author` varchar(50) NOT NULL,
`filem` varchar(255) NOT NULL,
`file` varchar(255) NOT NULL,
`size` varchar(9) NOT NULL);

CREATE TRIGGER `{pre}imgsd` AFTER DELETE ON `{pre}imgs` FOR EACH ROW
BEGIN
	DELETE FROM `{pre}comms` WHERE TYPE = 3 AND CID = old.ID;
END;

CREATE TABLE IF NOT EXISTS `{pre}links` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` tinyint(1) NOT NULL,
`adr` varchar(255) NOT NULL,
`priority` tinyint(1) NOT NULL,
`count` int(11) NOT NULL DEFAULT 0,
`nw` tinyint(1) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}log` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`name` varchar(50) NOT NULL,
`date` timestamp NOT NULL default CURRENT_TIMESTAMP,
`ip` varchar(40) NOT NULL,
`user` int(11) NOT NULL DEFAULT 0);

CREATE TABLE IF NOT EXISTS `{pre}menu` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`seq` int(11) NOT NULL,
`text` varchar(50) NOT NULL,
`disp` varchar(3) NOT NULL,
`menu` int(11) NOT NULL,
`type` tinyint(1) NOT NULL,
`img` varchar(200) NOT NULL,
`value` text);

CREATE TABLE IF NOT EXISTS `{pre}mitems` (
`menu` int(11) NOT NULL,
`text` varchar(50) NOT NULL,
`url` varchar(255) NOT NULL,
`nw` tinyint(1) NOT NULL DEFAULT 0,
`seq` tinyint(2) NOT NULL DEFAULT 0);

CREATE TRIGGER `{pre}menud` AFTER DELETE ON `{pre}menu` FOR EACH ROW
BEGIN
	DELETE FROM `{pre}mitems` WHERE menu = old.ID;
END;

CREATE TABLE IF NOT EXISTS `{pre}news` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`txt` text,
`date` datetime,
`author` varchar(50) NOT NULL,
`img` varchar(255) NOT NULL,
`comm` int(11) NOT NULL DEFAULT 0,
`access` tinyint(1) NOT NULL,
`opt` tinyint(2) NOT NULL DEFAULT 3);

CREATE INDEX cat ON {pre}news (cat);

CREATE TRIGGER `{pre}newsd` AFTER DELETE ON `{pre}news` FOR EACH ROW
BEGIN
	DELETE FROM `{pre}fnews` WHERE ID = old.ID;
	DELETE FROM `{pre}comms` WHERE TYPE = 5 AND CID = old.ID;
END;

CREATE TABLE IF NOT EXISTS `{pre}online` (
`IP` varchar(40) NOT NULL PRIMARY KEY,
`user` int(11) NOT NULL,
`time` timestamp NOT NULL default CURRENT_TIMESTAMP,
`site` varchar(50));

CREATE TABLE IF NOT EXISTS `{pre}pages` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`name` varchar(50) NOT NULL,
`access` varchar(3) NOT NULL,
`opt` tinyint(2) NOT NULL,
`text` mediumtext);

CREATE TRIGGER `{pre}pagesd` AFTER DELETE ON `{pre}pages` FOR EACH ROW
BEGIN
	DELETE FROM `{pre}comms` WHERE TYPE = 59 AND CID = old.ID;
END;

CREATE TABLE IF NOT EXISTS `{pre}plugins` (
`ID` varchar(30) NOT NULL,
`name` varchar(50) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}pms` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`topic` varchar(50) NOT NULL,
`usr` int(11) NOT NULL,
`owner` int(11) NOT NULL,
`st` tinyint(1) NOT NULL,
`date` int(11) NOT NULL,
`bbc` tinyint(1) NOT NULL,
`txt` text);

CREATE TABLE IF NOT EXISTS `{pre}polls` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`name` varchar(50) NOT NULL,
`q` varchar(80) NOT NULL,
`ison` tinyint(1) NOT NULL,
`type` tinyint(1) NOT NULL,
`num` int(11) NOT NULL DEFAULT 0,
`access` varchar(3) NOT NULL,
`date` date);

CREATE TABLE IF NOT EXISTS `{pre}pollvotes` (
`user` varchar(40) NOT NULL,
`ID` int(11) NOT NULL,
`date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP);

CREATE TABLE IF NOT EXISTS `{pre}rates` (
`type` tinyint(2) NOT NULL,
`ID` int(11) NOT NULL,
`mark` tinyint(1) NOT NULL DEFAULT 5,
`IP` varchar(50) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}tmp` (
`KEYID` varchar(50) NOT NULL,
`UID` int(11) NOT NULL,
`type` varchar(9) NOT NULL
);

CREATE TABLE IF NOT EXISTS `{pre}users` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`login` varchar(50) UNIQUE NOT NULL,
`pass` char(32) NOT NULL,
`mail` varchar(80) NOT NULL,
`opt` tinyint(2) NOT NULL DEFAULT 2,
`gid` int(11) NOT NULL DEFAULT 1,
`lv` tinyint(1) NOT NULL DEFAULT 1,
`adm` text,
`regt` int(11),
`lvis` int(11),
`pms` tinyint(4) NOT NULL DEFAULT 0,
`about` text,
`mails` tinyint(1) NOT NULL DEFAULT 0,
`www` varchar(200) NOT NULL,
`city` varchar(50) NOT NULL,
`icq` varchar(15),
`skype` varchar(50) NOT NULL,
`tlen` varchar(50) NOT NULL,
`gg` int(11),
`photo` varchar(150) NOT NULL);

CREATE TRIGGER `{pre}userd` AFTER DELETE ON `{pre}users` FOR EACH ROW
BEGIN
	DELETE FROM `{pre}pollvotes` WHERE user = old.ID;
	DELETE FROM `{pre}comms` WHERE (guest != 1 AND author = old.ID) OR (type = 10 AND CID = old.ID);
END;