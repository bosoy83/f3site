<?php

#Usuñ sondy i odbuduj cache
function DeletePoll($x = null)
{
	global $db;
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
	global $db;
	if(!is_numeric($x) && !$x = GetID(true)) return false;

	$db->exec('UPDATE '.PRE.'answers SET num=0 WHERE IDP IN ('.$x.')');
	$db->exec('UPDATE '.PRE.'polls SET num=0 WHERE ID IN ('.$x.')');
	$db->exec('DELETE FROM '.PRE.'pollvotes WHERE ID IN ('.$x.')');

	RebuildPoll();
}

#Odbuduj cache aktualnej sondy
function RebuildPoll($only = null)
{
	global $db;
	require_once './lib/config.php';
	$lang = array();
	$used = array();

	if(!$only)
	{
		foreach(scandir('lang') as $x)
		{
			if(ctype_alnum($x)) $lang[] = $x;
		}
	}
	elseif(ctype_alnum($only))
	{
		$lang[] = $x;
	}
	else
	{
		return false;
	}
	$poll = $db->query('SELECT * FROM '.PRE.'polls WHERE access IN (\''.join('\',\'', $lang).'\') ORDER BY access, ID DESC') -> fetchAll(2); //ASSOC

	$i = 0;
	foreach($lang as $x)
	{
		if(isset($used[$x]))
		{
			++$i; continue;
		}
		if(isset($poll[$i]) && $x == $poll[$i]['access'])
		{
			$o = $db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$poll[$i]['ID'].' ORDER BY seq') -> fetchAll(3); //NUM
			$file = new Config('./cache/poll_'.$x.'.php');
			$file->add('poll', $poll[$i++]);
			$file->add('option', $o);
			$file->save();
			$used[$x] = 1;
		}
		elseif(file_exists('./cache/poll_'.$x.'.php'))
		{
			unlink('./cache/poll_'.$x.'.php');
		}
	}
}