<center style="line-height: 22px">
<?php
if(iCMS!=1) exit;
$online=isset($_SESSION['online'])?$_SESSION['online']:0;

#Licznik
if(file_exists('cfg/visits.txt'))
{
	$licznik=file_get_contents('cfg/visits.txt');
}
else
{
	$licznik=0;
}
if(!$online)
{
	file_put_contents(++$licznik,2); //LOCK_EX
}

#Google?
if(strstr($_SERVER['HTTP_USER_AGENT'],'Googlebot'))
{
	$r='Googlebot';
	#Zapisz do logu
	if(!$online) $db->exec('INSERT INTO '.PRE.'log VALUES ("","Google",NOW(),"-",0)');
}
else
{
	$r=$db->quote($_SERVER['REMOTE_ADDR']. ((isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		?' '.$_SERVER['HTTP_X_FORWARDED_FOR']:'') );
}

#Online
if($online>(time()-600))
{
	$db->exec('UPDATE '.PRE.'online SET time=NOW(), user='.UID.' WHERE IP="'.$r.'"');
}
else
{
	$db->exec('DELETE FROM '.PRE.'online WHERE time<(NOW()-600) OR IP="'.$r.'"');
	$db->exec('INSERT INTO '.PRE.'online VALUES ("'.$r.'",'.UID.',NOW(),"")');
	$_SESSION['online']=time();
}

$res=$db->query('SELECT COUNT(IP) FROM '.PRE.'online');
echo $lang['visits'].'<br /><b>'.$licznik.'</b><br />'.$lang['online'].'<br />
	<b>'.$res->fetchColumn().'</b>';
$res=null;
unset($online);
?>
</center>
