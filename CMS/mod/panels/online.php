<div id="online">
<?php
if(iCMS!=1) exit;
$online = isset($_SESSION['online']) ? $_SESSION['online'] : 0;

#Licznik
if(file_exists('./cfg/visits.txt'))
{
	$licznik = file_get_contents('./cfg/visits.txt');
}
else
{
	$licznik = 0;
}
if(!$online)
{
	file_put_contents('./cfg/visits.txt', ++$licznik, 2); //LOCK_EX
}

#Google?
if(strstr($_SERVER['HTTP_USER_AGENT'],'Googlebot'))
{
	$r='Googlebot';
	#Zapisz do logu
	if(!$online) $db->exec('INSERT INTO '.PRE.'log VALUES ("","Google Visit",NOW(),"-",0)');
}
else
{
	$r = $db->quote($_SERVER['REMOTE_ADDR']. ((isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		?' '.$_SERVER['HTTP_X_FORWARDED_FOR']:'') );
}

#Online (10 minut)
if($online < ($_SERVER['REQUEST_TIME']-600))
{
	$db->exec('DELETE FROM '.PRE.'online WHERE time<('.$_SERVER['REQUEST_TIME'].'-600) OR IP="'.$r.'"');
	$db->exec('INSERT INTO '.PRE.'online VALUES ("'.$r.'",'.UID.','.$_SERVER['REQUEST_TIME'].',"")');
	$_SESSION['online'] = $_SERVER['REQUEST_TIME'];
}

#Wyœwietl dane
echo
	$lang['visits'].'<br /><b>'.$licznik.'</b><br />'.
	$lang['online'].'<br /><b>'.db_count('online').'</b>';
unset($online);
?>
</div>