<?php /* Wy¶wietl profil u¿ytkownika */
if(iCMS!=1) exit;

$id = $id ? $id : UID;
require LANG_DIR.'profile.php'; #Jêzyk

#N/A
define('NA',$lang['na']);

#Pobierz dane
$u = $db->query('SELECT * FROM '.PRE.'users WHERE ID='.$id)->fetch(2);

#Gdy nie istnieje...
if(!$u) return;

#O sobie
$u['about'] = nl2br(Emots($u['about']));

#WWW
$u['www'] = ($u['www'] && $u['www']!='http://') ? $u['www'] : null;

#E-mail
if($u['mvis']==1)
{
	$u['mail']=str_replace('@','&#64;',$u['mail']);
	$u['mail']=str_replace('.','&#46;',$u['mail']);
}
else
{
	$u['mail']=null;
}

#Sk±d?
if(!$u['city']) $u['city'] = NA;

#PM
$pm_url = isset($cfg['pmsOn']) && LOGD==1 ? '?co=pms&amp;act=e&amp;a='.$u['login'] : '';

#Do szablonu
$content->title = $u['login'];
$content->data  = array(
	'u' => &$u,
	'pm_url' => $pm_url,
	'users'  => MOD_REWRITE ? '/users' : '?co=users',
	'join_date' => genDate($u['regt'], true), //Data rejestracji
	'last_visit'=> $u['lvis'] ? genDate($u['lvis'],true) : NA
);

if(isset($cfg['userComm']))
{
	define('CT','10');
	include './lib/comm.php';
}