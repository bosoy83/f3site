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
if($u['opt'] & 1)
{
	$u['mail'] = str_replace('@', '&#64;', $u['mail']);
	$u['mail'] = str_replace('.', '&#46;', $u['mail']);
}
else
{
	$u['mail'] = null;
}

#PM
$pm = isset($cfg['pmOn']) && LOGD==1 ? '?co=pms&amp;act=e&amp;to='.$id : false;

#Do szablonu
$content->title = $u['login'];
$content->data  = array(
	'u'  => &$u,
	'pm' => $pm,
	'users'  => '?co=users',
	'join_date' => genDate($u['regt'],1), //Data rejestracji
	'last_visit'=> $u['lvis'] ? genDate($u['lvis'],1) : NA
);

if(isset($cfg['userComm']) && $u['opt'] & 2)
{
	define('CT','10');
	include './lib/comm.php';
}
