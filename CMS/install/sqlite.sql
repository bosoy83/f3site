--SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
--SET AUTOCOMMIT=0;

BEGIN TRANSACTION;

CREATE TABLE IF NOT EXISTS `{pre}acl` (
`UID` KEY int(11) NOT NULL,
`CatID` KEY int(11) NOT NULL,
`all` tinyint(2) NOT NULL,
`type` varchar(9) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}admmenu` (
`ID` varchar(9) PRIMARY KEY NOT NULL,
`text` varchar(30) NOT NULL,
`file` varchar(30) NOT NULL,
`menu` tinyint(1) NOT NULL,
`rights` tinyint(1) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}answers` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`IDP` int(10) NOT NULL,
`seq` int(11),
`a` varchar(200),
`num` int(11) NOT NULL DEFAULT 0);

-- CREATE TABLE IF NOT EXISTS `{pre}artrates` (`user` varchar(100) NOT NULL,`ID` int(11) NOT NULL,`rate` varchar(100) NOT NULL default '');

CREATE TABLE IF NOT EXISTS `{pre}arts` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text NOT NULL,
`date` date,
`author` varchar(50) NOT NULL,
`rate` varchar(4),
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

CREATE TABLE IF NOT EXISTS `{pre}banners` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`gen` tinyint(2) NOT NULL,
`name` varchar(50),
`ison` tinyint(1),
`code` text);

CREATE TABLE IF NOT EXISTS `{pre}cats` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` varchar(255),
`access` varchar(9) NOT NULL,
`type` tinyint(1) NOT NULL DEFAULT 5,
`sc` int(11) NOT NULL,
`sort` tinyint(1) NOT NULL,
`text` text,
`num` int(10) NOT NULL DEFAULT 0,
`nums` int(10) NOT NULL DEFAULT 0,
`opt` tinyint(2) NOT NULL,
`lft` tinyint(4) NOT NULL,
`rgt` tinyint(4) NOT NULL);

CREATE INDEX sc ON {pre}cats (sc);
CREATE INDEX pos ON {pre}cats (lft,rgt);

CREATE TABLE IF NOT EXISTS `{pre}comms` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
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
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`cat` int(11) NOT NULL,
`name` varchar(50),
`author` varchar(100),
`date` date,
`dsc` text,
`file` varchar(200),
`dls` int(11) NOT NULL DEFAULT 0,
`access` tinyint(1) NOT NULL,
`size` varchar(50),
`priority` tinyint(1) NOT NULL,
`rate` varchar(4),
`fulld` mediumtext);

CREATE TABLE IF NOT EXISTS `{pre}fnews` (
`ID` int(11) NOT NULL,
`cat` int(11) NOT NULL,
`text` mediumtext,
PRIMARY KEY (ID));

CREATE TABLE IF NOT EXISTS `{pre}groups` (
`ID` INTEGER NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` varchar(3) NOT NULL,
`opened` tinyint(1) NOT NULL,
PRIMARY KEY (ID));

CREATE TABLE IF NOT EXISTS `{pre}imgs` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`type` tinyint(1) NOT NULL,
`date` datetime,
`priority` tinyint(1) NOT NULL,
`access` tinyint(1) NOT NULL,
`rate` varchar(4),
`author` varchar(50) NOT NULL,
`filem` varchar(255) NOT NULL,
`file` varchar(255) NOT NULL,
`size` varchar(9) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}links` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` tinyint(1) NOT NULL,
`adr` varchar(255) NOT NULL,
`priority` tinyint(1) NOT NULL,
`count` int(11) NOT NULL DEFAULT 0,
`nw` tinyint(1) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}log` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`name` varchar(50) NOT NULL,
`date` datetime,
`ip` varchar(40) NOT NULL,
`user` int(11) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}menu` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
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

CREATE TABLE IF NOT EXISTS `{pre}news` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`txt` text,
`date` datetime,
`author` varchar(50) NOT NULL,
`img` varchar(255) NOT NULL,
`comm` int(11) NOT NULL DEFAULT 0,
`access` tinyint(1) NOT NULL,
`opt` tinyint(2) NOT NULL);

CREATE INDEX cat ON {pre}news (cat);

CREATE TABLE IF NOT EXISTS `{pre}online` (
`IP` varchar(40) PRIMARY KEY NOT NULL,
`user` int(11) NOT NULL,
`time` timestamp NOT NULL default CURRENT_TIMESTAMP,
`site` varchar(50));

CREATE TABLE IF NOT EXISTS `{pre}pages` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`name` varchar(50) NOT NULL,
`access` varchar(3) NOT NULL,
`opt` tinyint(2) NOT NULL,
`text` mediumtext);

CREATE TABLE IF NOT EXISTS `{pre}plugins` (
`ID` varchar(30) NOT NULL,
`name` varchar(50) NOT NULL);

CREATE TABLE IF NOT EXISTS `{pre}pms` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`topic` varchar(50) NOT NULL,
`usr` int(11) NOT NULL,
`owner` int(11) NOT NULL,
`st` tinyint(1) NOT NULL,
`date` int(11) NOT NULL,
`bbc` tinyint(1) NOT NULL,
`txt` text);

CREATE TABLE IF NOT EXISTS `{pre}polls` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
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
`date` date);

CREATE INDEX ID ON {pre}news (ID);

CREATE TABLE IF NOT EXISTS `{pre}tmp` (
`KEYID` varchar(50) NOT NULL,
`UID` int(11) NOT NULL,
`type` varchar(9) NOT NULL
);

CREATE TABLE IF NOT EXISTS `{pre}users` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
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
`gg` int(11));

COMMIT TRANSACTION;
--SET AUTOCOMMIT=1;