CREATE TABLE IF NOT EXISTS `f3_acl` (
`UID` int(11) NOT NULL,
`CatID`int(11) NOT NULL,
`type` varchar(9) NOT NULL,
PRIMARY KEY (UID,CatID)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_admmenu` (
`ID` varchar(9) PRIMARY KEY NOT NULL,
`text` varchar(30) NOT NULL,
`file` varchar(30) NOT NULL,
`menu` tinyint(1) NOT NULL,
`rights` tinyint(1) NOT NULL) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_answers` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`IDP` int(10) NOT NULL,
`seq` int(11),
`a` varchar(200),
`num` int(11) NOT NULL DEFAULT 0,
KEY (IDP)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_arts` (
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
`ent` int(11) NOT NULL DEFAULT 0,
KEY (cat)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_artstxt` (
`ID` int(11) NOT NULL,
`page` tinyint(4) NOT NULL,
`cat` int(11) NOT NULL,
`text` mediumtext,
`opt` tinyint(2) NOT NULL,
PRIMARY KEY (ID,page)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_banners` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`gen` tinyint(2) NOT NULL,
`name` varchar(50),
`ison` tinyint(1),
`code` text) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_cats` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
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
`rgt` tinyint(4) NOT NULL,
KEY `sc` (sc),
KEY `pos` (lft,rgt)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_comms` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`TYPE` tinyint(1) NOT NULL,
`CID` int(11) NOT NULL,
`access` tinyint(1) NOT NULL,
`name` varchar(50) NOT NULL,
`author` varchar(50) NOT NULL,
`guest` tinyint(1) NOT NULL DEFAULT 1,
`ip` varchar(20) NOT NULL,
`date` int(11),
`text` text,
KEY `th` (TYPE,CID)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_confmenu` (
`ID` varchar(50) NOT NULL,
`name` varchar(50),
`lang` varchar(5) NOT NULL,
`img` varchar(230),
KEY (ID)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_files` (
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
`fulld` mediumtext,
KEY (cat)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_groups` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` varchar(3) NOT NULL,
`opened` tinyint(1) NOT NULL,
`who` int(11) NOT NULL DEFAULT 0,
`num` int(11) NOT NULL DEFAULT 0,
`date` int(11)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_groupuser` (
`u` int(11) NOT NULL,
`g` int(11) NOT NULL,
`date` int(11),
PRIMARY KEY (u,g)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_imgs` (
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
`size` varchar(9) NOT NULL,
KEY (cat)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_links` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` text,
`access` tinyint(1) NOT NULL,
`adr` varchar(255) NOT NULL,
`priority` tinyint(1) NOT NULL,
`count` int(11) NOT NULL DEFAULT 0,
`nw` tinyint(1) NOT NULL,
KEY (cat)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_log` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`name` varchar(50) NOT NULL,
`date` timestamp NOT NULL default CURRENT_TIMESTAMP,
`ip` varchar(40) NOT NULL,
`user` int(11) NOT NULL DEFAULT 0) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_menu` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`seq` int(11) NOT NULL,
`text` varchar(50) NOT NULL,
`disp` varchar(3) NOT NULL,
`menu` int(11) NOT NULL,
`type` tinyint(1) NOT NULL,
`img` varchar(200) NOT NULL,
`value` text) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_mitems` (
`menu` int(11) NOT NULL,
`text` varchar(50) NOT NULL,
`url` varchar(255) NOT NULL,
`nw` tinyint(1) NOT NULL DEFAULT 0,
`seq` tinyint(2) NOT NULL DEFAULT 0) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_news` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`cat` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`txt` text,
`date` datetime,
`author` varchar(50) NOT NULL,
`img` varchar(255) NOT NULL,
`comm` int(11) NOT NULL DEFAULT 0,
`access` tinyint(1) NOT NULL,
`opt` tinyint(2) NOT NULL DEFAULT 3,
KEY (cat)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_newstxt` (
`ID` int(11) NOT NULL PRIMARY KEY,
`cat` int(11) NOT NULL,
`text` mediumtext) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_online` (
`IP` varchar(40) NOT NULL PRIMARY KEY,
`user` int(11) NOT NULL,
`name` varchar(50),
`time` timestamp NOT NULL default CURRENT_TIMESTAMP) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_pages` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`name` varchar(50) NOT NULL,
`access` varchar(3) NOT NULL,
`opt` tinyint(2) NOT NULL,
`text` mediumtext) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_plugins` (
`ID` varchar(30) NOT NULL,
`name` varchar(50) NOT NULL) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_pms` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`topic` varchar(50) NOT NULL,
`usr` int(11) NOT NULL,
`owner` int(11) NOT NULL,
`st` tinyint(1) NOT NULL,
`date` int(11) NOT NULL,
`bbc` tinyint(1) NOT NULL,
`txt` text,
KEY (owner)) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_polls` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`name` varchar(50) NOT NULL,
`q` varchar(80) NOT NULL,
`ison` tinyint(1) NOT NULL,
`type` tinyint(1) NOT NULL,
`num` int(11) NOT NULL DEFAULT 0,
`access` varchar(3) NOT NULL,
`date` date) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_pollvotes` (
`user` varchar(40) NOT NULL,
`ID` int(11) NOT NULL,
`date` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_rates` (
`type` tinyint(2) NOT NULL,
`ID` int(11) NOT NULL,
`mark` tinyint(1) NOT NULL DEFAULT 5,
`IP` varchar(50) NOT NULL) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_rss` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`auto` tinyint(1) NOT NULL,
`name` varchar(50) NOT NULL,
`dsc` varchar(99) NOT NULL,
`url` varchar(80) NOT NULL,
`lang` varchar(3) NOT NULL,
`cat` int(11) NOT NULL,
`num` int(11) NOT NULL
) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_tmp` (
`KEYID` varchar(50) NOT NULL PRIMARY KEY,
`UID` int(11) NOT NULL,
`type` varchar(9) NOT NULL
) ENGINE=InnoDB CHARACTER SET='utf8';

CREATE TABLE IF NOT EXISTS `f3_users` (
`ID` INT NOT NULL auto_increment PRIMARY KEY,
`login` varchar(50) UNIQUE NOT NULL,
`pass` char(32) NOT NULL,
`mail` varchar(80) NOT NULL,
`sex` tinyint(1) NOT NULL DEFAULT 0,
`opt` tinyint(2) NOT NULL DEFAULT 2,
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
`jabber` varchar(60) NOT NULL,
`tlen` varchar(50) NOT NULL,
`gg` int(11),
`photo` varchar(150) NOT NULL) ENGINE=InnoDB CHARACTER SET='utf8';