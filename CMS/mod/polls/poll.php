<?php

#Usuñ sondy i odbuduj cache
function DeletePoll($x = null)
{
	if(!is_numeric($x) && !$x = GetID(true)) return false;

	$db->exec('DELETE FROM '.PRE.'polls WHERE ID IN ('.$x.')');
	$db->exec('DELETE FROM '.PRE.'answers WHERE IDP IN ('.$x.')');
	$db->exec('DELETE FROM '.PRE.'comms WHERE TYPE=15 AND CID IN ('.$x.')');
	$db->exec('DELETE FROM '.PRE.'pollvotes WHERE ID IN ('.$x.')');

	RebuildPoll();
}

#Wyzeruj wyniki sondy
function ResetPoll($x = null)
{
	if(!is_numeric($x) && !$x = GetID(true)) return false;

	$db->exec('UPDATE '.PRE.'answers SET num=0 WHERE IDP IN ('.$x.')');
	$db->exec('UPDATE '.PRE.'polls SET num=0 WHERE ID IN ('.$x.')');
	$db->exec('DELETE FROM '.PRE.'pollvotes WHERE ID IN ('.$x.')');

	RebuildPoll();
}

#Odbuduj cache aktualnej sondy
function RebuildPoll($lang = null)
{
	global $db;
	require './lib/config.php';

	if(!$lang)
	{
		$res = $db->query('SELECT * FROM '.PRE.'polls GROUP BY access ORDER BY ID DESC');
	}
	elseif(ctype_alnum($lang))
	{
		$res = $db->query('SELECT * FROM '.PRE.'polls WHERE access="'.$lang.'" ORDER BY ID DESC LIMIT 1');
	}
	else
	{
		return false;
	}
	$all = $res -> fetchAll(2); //ASSOC

	foreach($all as $poll)
	{
		$o = $db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$poll['ID']) -> fetchAll(3); //NUM
		$file = new Config('./cache/poll_'.$poll['access'].'.php');
		$file->add('poll', $poll);
		$file->add('option', $o);
		$file->save();
	}
}