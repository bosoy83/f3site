<?php
if(iCMSa!=1 || !admit('U')) exit;
require LANG_DIR.'rights.php';

#Odczyt
$res = $db->query('SELECT ID,login,lv,adm FROM '.PRE.'users WHERE lv>1 OR adm!=""');
$res->setFetchMode(3); //NUM

#Info
$content->info($lang['iadms'], array(url('editUser','','admin')=>$lang['addUser']));

$num  = 0;
$adms = array();

foreach($res as $adm)
{
	switch($adm[2])
	{
		case '0': $lv = $lang['locked']; break;
		case '1': $lv = $lang['user']; break;
		case '2': $lv = $lang['editor']; break;
		case '3': $lv = $lang['admin']; break;
		case '4': $lv = $lang['owner']; break;
		default: $lv = '!?';
	}
	$adms[] = array(
		'url'   => url('user/'.urlencode($adm[1])),
		'rights'=> str_replace('|',' ',$adm[3]),
		'level' => $lv,
		'login' => $adm[1],
		'url1'  => $adm[2] < LEVEL || LEVEL == 4 ? url('editAdmin/'.$adm[0], '', 'admin') : false,
		'url2'  => $adm[2] < LEVEL || LEVEL == 4 ? url('editUser/'.$adm[0], '', 'admin') : false,
	);
}

$content->title = $lang['admins'];
$content->data['admin'] =& $adms;