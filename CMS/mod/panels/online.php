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

#IP
$ip = $_SERVER['REMOTE_ADDR'];

#Google?
if(strstr($_SERVER['HTTP_USER_AGENT'],'Googlebot'))
{
	$name = 'Google';
}
elseif(UID)
{
	$name = $user[UID]['login'];
}
else
{
	$name = '';
}

#Online (10 minut)
if($online < ($_SERVER['REQUEST_TIME']-600))
{
	$db->exec('DELETE FROM '.PRE.'online WHERE time < CURRENT_TIMESTAMP-600 OR IP="'.$ip.'"');
	$db->prepare('INSERT INTO '.PRE.'online (IP,user,name) VALUES (?,?,?)')->execute(array($ip,UID,$name));
	$_SESSION['online'] = $_SERVER['REQUEST_TIME'];
}

#Lista osób online
$res = $db->query('SELECT user,name FROM '.PRE.'online');
$res->setFetchMode(3);
$list = '';
$num = 0;

foreach($res as $x)
{
	if($x[1])
	{
		$list .= '<br /><a href="?co=user&amp;id='.$x[0].'">'.$x[1].'</a>';
	}
	++$num;
}
echo
	$lang['visits'].'<br /><b>'.$licznik.'</b><br />'.
	$lang['online'].'<br /><b>'.$num.'</b>'.$list;
unset($online);
?>
</div>
