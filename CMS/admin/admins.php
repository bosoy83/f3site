<?php
if(iCMSa!=1 || !Admit('U')) exit;
require LANG_DIR.'rights.php';

#Odczyt
$res = $db->query('SELECT ID,login,lv,adm FROM '.PRE.'users WHERE lv>1');
$res->setFetchMode(3); //NUM

#Info
$content->info($lang['iadms']);

$num  = 0;
$adms = array();

foreach($res as $adm)
{
	#Kim jest
	switch($adm[2])
	{
		case 0: $lv = $lang['locked']; break;
		case 1: $lv = $lang['user']; break;
		case 2: $lv = $lang['editor']; break;
		case 3: $lv = $lang['admin']; break;
		case 4: $lv = $lang['owner']; break;
		default: $lv = 'ERR!';
	}
	$adms[] = array(
		'url'   => '.?co=user&amp;id='.$adm[0],
		'rights'=> str_replace('|',' ',$adm[3]),
		'level' => $lv,
		'login' => $adm[1],
		'allow' => $adm[0] != UID && (UID == 1 OR $adm[2] < LEVEL),
		'url1'  => '?a=editAdmin&amp;id='.$adm[0],
		'url2'  => '?a=editUser&amp;id='.$adm[0],
	);
}

$content->title = $lang['admins'];
$content->data['admin'] =& $adms;