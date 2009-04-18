<?php
function Install()
{
	global $db,$lang;

	if(!file_exists('./cfg/gb.php'))
	{
		if(!copy('./plugins/guestbook/cfg.php', './cfg/gb.php'))
		{
			throw new Exception('Cannot create configuration file! CHMOD <b>cfg</b> directory to 777!');
		}
	}

	$db->beginTransaction();
	$db->exec('CREATE TABLE IF NOT EXISTS '.PRE.'guestbook (
		ID '.AUTONUM.',
		UID  int NOT NULL DEFAULT 0,
		who  varchar(30) NOT NULL DEFAULT "",
		lang varchar(3) NOT NULL DEFAULT "en",
		date int NOT NULL,
		gg   int unsigned,
		tlen varchar(30) NOT NULL DEFAULT "",
		icq  int unsigned,
		skype  varchar(30) NOT NULL DEFAULT "",
		jabber varchar(80) NOT NULL DEFAULT "",
		mail varchar(70) NOT NULL DEFAULT "",
		www  varchar(70) NOT NULL DEFAULT "",
		ip   varchar(50) NOT NULL DEFAULT "",
		txt  mediumtext)');

	$q = $db->prepare('INSERT INTO '.PRE.'confmenu (ID,name,lang,img) VALUES (?,?,?,?)');
	$q -> execute(array('guestbook', 'Guestbook', 'en', 'img/admin/c1.png'));
	$q -> execute(array('guestbook', 'Księga gości', 'pl', 'img/admin/c1.png'));

	$db->exec('INSERT INTO '.PRE.'admmenu (ID,text,file,menu,rights) VALUES ("GB","Guestbook","guestbook",1,1)');
	$db->commit();
}

function Uninstall()
{
	global $db;
	$db->beginTransaction();
	$db->exec('DROP TABLE IF EXISTS '.PRE.'guestbook');
	$db->exec('DELETE FROM '.PRE.'admmenu WHERE ID="GB"');
	$db->exec('DELETE FROM '.PRE.'confmenu WHERE ID="guestbook"');
	$db->commit();
}