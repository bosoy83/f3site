<?php
if(iCMSa!=1 || !Admit('AD')) exit;
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
	$adms[] = array(
		'url'   => MOD_REWRITE ? '/user/'.$adm[0] : 'index.php?co=user&amp;id='.$adm[0],
		'rights'=> str_replace('|',' ',$adm[3]),
		'login' => $adm[1],
		'allow' => $adm[2]==4 || $adm[0]==UID ? false : true,
		'url1'  => '?a=editAdmin&amp;id='.$adm[0],
		'url2'  => '?a=editUser&amp;id='.$adm[0],
	);
}

$content->title = $lang['admins'];
$content->data['admin'] =& $adms;