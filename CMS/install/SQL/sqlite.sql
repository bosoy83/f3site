CREATE TABLE IF NOT EXISTS `f3_acl` (
`UID` KEY int(11) NOT NULL,
`CatID` KEY int(11) NOT NULL,
`type` varchar(9) NOT NULL);

CREATE INDEX IF NOT EXISTS ID ON `f3_acl` (UID,CatID);

CREATE TABLE IF NOT EXISTS `f3_admmenu` (
`ID` varchar(9) PRIMARY KEY NOT NULL,
`text` varchar(30) NOT NULL,
`file` varchar(30) NOT NULL,
`menu` tinyint(1) NOT NULL,
`rights` tinyint(1) NOT NULL);

CREATE TABLE IF NOT EXISTS `f3_answers` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`IDP` int(10) NOT NULL,
`seq` int(11) NOT NULL DEFAULT 0,
`a` varchar(200),
`num` int(11) NOT NULL DEFAULT 0);

CREATE INDEX IF NOT EXISTS p ON `f3_answers` (IDP);

CREATE TABLE IF NOT EXISTS `f3_arts` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
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

CREATE INDEX IF NOT EXISTS cat ON `f3_arts` (cat);

CREATE TABLE IF NOT EXISTS `f3_artstxt` (
`ID` int(11) NOT NULL,
`page` tinyint(4) NOT NULL,
`cat` int(11) NOT NULL,
`text` mediumtext,
`opt` tinyint(2) NOT NULL,
PRIMARY KEY (ID,page));

CREATE TABLE IF NOT EXISTS `f3_banners` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`gen` tinyint(2) NOT NULL,
`name` varchar(50),
`ison` tinyint(1),
`code` text);

CREATE TABLE IF NOT EXISTS `f3_cats` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` varchar(255),
`access` varchar(9) NOT NULL,
`type` tinyint(1) NOT NULL DEFAULT 5,
`sc` int(11) NOT NULL DEFAULT 0,
`sort` tinyint(1) NOT NULL DEFAULT 2,
`text` text,
`num` int(10) NOT NULL DEFAULT 0,
`nums` int(10) NOT NULL DEFAULT 0,
`opt` tinyint(2) NOT NULL,
`lft` tinyint(4) NOT NULL,
`rgt` tinyint(4) NOT NULL);

CREATE INDEX IF NOT EXISTS sc ON `f3_cats` (sc);
CREATE INDEX IF NOT EXISTS pos ON `f3_cats` (lft,rgt);

CREATE TABLE IF NOT EXISTS `f3_comms` (
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

CREATE INDEX IF NOT EXISTS th ON `f3_comms` (TYPE,CID);

CREATE TABLE IF NOT EXISTS `f3_confmenu` (
`ID` varchar(50) NOT NULL,
`name` varchar(50),
`lang` varchar(5) NOT NULL,
`img` varchar(230));

CREATE INDEX IF NOT EXISTS ID ON `f3_confmenu` (ID);

CREATE TABLE IF NOT EXISTS `f3_files` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
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

CREATE INDEX IF NOT EXISTS cat ON `f3_files` (cat);

CREATE TABLE IF NOT EXISTS `f3_groups` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` varchar(3) NOT NULL,
`opened` tinyint(1) NOT NULL,
`who` int(11) NOT NULL DEFAULT 0,
`num` int(11) NOT NULL DEFAULT 0,
`date` int(11));

CREATE TABLE IF NOT EXISTS `f3_groupuser` (
`u` int(11) NOT NULL,
`g` int(11) NOT NULL,
`date` int(11),
PRIMARY KEY (u,g));

CREATE TABLE IF NOT EXISTS `f3_imgs` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
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

CREATE INDEX IF NOT EXISTS cat ON `f3_imgs` (cat);

CREATE TABLE IF NOT EXISTS `f3_links` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` tinyint(1) NOT NULL,
`adr` varchar(255) NOT NULL,
`priority` tinyint(1) NOT NULL,
`count` int(11) NOT NULL DEFAULT 0,
`nw` tinyint(1) NOT NULL);

CREATE INDEX IF NOT EXISTS cat ON `f3_links` (cat);

CREATE TABLE IF NOT EXISTS `f3_log` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`name` varchar(50) NOT NULL,
`date` timestamp NOT NULL default CURRENT_TIMESTAMP,
`ip` varchar(40),
`user` int(11) NOT NULL DEFAULT 0);

CREATE TABLE IF NOT EXISTS `f3_menu` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`seq` int(11) NOT NULL,
`text` varchar(50) NOT NULL,
`disp` varchar(3) NOT NULL,
`menu` int(11) NOT NULL,
`type` tinyint(1) NOT NULL,
`img` varchar(200),
`value` text);

CREATE TABLE IF NOT EXISTS `f3_mitems` (
`menu` int(11) NOT NULL,
`text` varchar(50) NOT NULL,
`url` varchar(255) NOT NULL,
`nw` tinyint(1) NOT NULL DEFAULT 0,
`seq` tinyint(2) NOT NULL DEFAULT 0);

CREATE TABLE IF NOT EXISTS `f3_news` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`txt` text,
`date` datetime,
`author` varchar(50) NOT NULL,
`img` varchar(255) NOT NULL DEFAULT '',
`comm` int(11) NOT NULL DEFAULT 0,
`access` tinyint(1) NOT NULL,
`opt` tinyint(2) NOT NULL DEFAULT 3);

CREATE INDEX IF NOT EXISTS cat ON `f3_news` (cat);

CREATE TABLE IF NOT EXISTS `f3_newstxt` (
`ID` int(11) NOT NULL,
`cat` int(11) NOT NULL,
`text` mediumtext,
PRIMARY KEY (ID));

CREATE TABLE IF NOT EXISTS `f3_online` (
`IP` varchar(40) PRIMARY KEY NOT NULL,
`user` int(11) NOT NULL,
`name` varchar(50),
`time` timestamp NOT NULL default CURRENT_TIMESTAMP);

CREATE TABLE IF NOT EXISTS `f3_pages` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`name` varchar(50) NOT NULL,
`access` varchar(3) NOT NULL,
`opt` tinyint(2) NOT NULL,
`text` mediumtext);

CREATE TABLE IF NOT EXISTS `f3_plugins` (
`ID` varchar(30) NOT NULL,
`name` varchar(50) NOT NULL);

CREATE TABLE IF NOT EXISTS `f3_pms` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`topic` varchar(50) NOT NULL,
`usr` int(11) NOT NULL,
`owner` int(11) NOT NULL,
`st` tinyint(1) NOT NULL,
`date` int(11) NOT NULL,
`bbc` tinyint(1) NOT NULL,
`txt` text);

CREATE INDEX IF NOT EXISTS o ON `f3_pms` (owner);

CREATE TABLE IF NOT EXISTS `f3_polls` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`name` varchar(50) NOT NULL,
`q` varchar(80) NOT NULL,
`ison` tinyint(1) NOT NULL,
`type` tinyint(1) NOT NULL,
`num` int(11) NOT NULL DEFAULT 0,
`access` varchar(3) NOT NULL,
`date` date);

CREATE TABLE IF NOT EXISTS `f3_pollvotes` (
`user` varchar(40) NOT NULL,
`ID` int(11) NOT NULL,
`date` date NOT NULL DEFAULT CURRENT_DATE);

CREATE TABLE IF NOT EXISTS `f3_rates` (
`type` tinyint(2) NOT NULL,
`ID` int(11) NOT NULL,
`mark` tinyint(1) NOT NULL DEFAULT 5,
`IP` varchar(50) NOT NULL);

CREATE TABLE IF NOT EXISTS `f3_rss` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`auto` tinyint(1) NOT NULL DEFAULT 0,
`name` varchar(50) NOT NULL DEFAULT '',
`dsc` varchar(99) NOT NULL DEFAULT '',
`url` varchar(80) NOT NULL DEFAULT '',
`lang` varchar(3) NOT NULL DEFAULT 'en',
`cat` int(11) NOT NULL DEFAULT 0,
`num` int(11) NOT NULL DEFAULT 0);

CREATE TABLE IF NOT EXISTS `f3_tmp` (
`KEYID` varchar(50) NOT NULL PRIMARY KEY,
`UID` int(11) NOT NULL,
`type` varchar(9) NOT NULL);

CREATE TABLE IF NOT EXISTS `f3_users` (
`ID` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
`login` varchar(50) UNIQUE NOT NULL,
`pass` char(32) NOT NULL,
`mail` varchar(80),
`sex` tinyint(1) NOT NULL DEFAULT 0,
`opt` tinyint(2) NOT NULL DEFAULT 2,
`lv` tinyint(1) NOT NULL DEFAULT 1,
`adm` text,
`regt` int(11),
`lvis` int(11),
`pms` tinyint(4) NOT NULL DEFAULT 0,
`about` text,
`mails` tinyint(1) NOT NULL DEFAULT 0,
`www` varchar(200) NOT NULL DEFAULT '',
`city` varchar(50) NOT NULL DEFAULT '',
`icq` varchar(15),
`skype` varchar(50),
`jabber` varchar(60),
`tlen` varchar(50),
`gg` int(11),
`photo` varchar(150));