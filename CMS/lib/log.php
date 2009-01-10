<?php
#Zapisz zdarzenie
function event($name, $u=UID)
{
	global $db;
	$q = $db->prepare('INSERT INTO '.PRE.'log (name,ip,user) VALUES (?,?,?)');
	$q -> execute(array($name, $_SERVER['REMOTE_ADDR'], $u));
}