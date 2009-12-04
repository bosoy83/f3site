<?php /* Wy¶wietl profil u¿ytkownika */
if(iCMS!=1) exit;
require LANG_DIR.'profile.php';

#User ID or login
if(isset($URL[1]))
{
	$login = $URL[1];
}
elseif(LOGD)
{
	$login = $user['login'];
}
else return;

#Query
$q = $db->prepare('SELECT * FROM '.PRE.'users WHERE login=?');
$q->execute(array($login));

#If does not exist
if(!$u = $q->fetch(2)) return;

#N/A
define('NA',$lang['na']);

#O sobie
$u['about'] = nl2br(emots($u['about']));

#BBCode
if(isset($cfg['bbcode']))
{
	include_once './lib/bbcode.php';
	$u['about'] = BBCode($u['about']);
}

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

#P³eæ
switch($u['sex'])
{
	case 1: $u['sex'] = $lang['male']; break;
	case 2: $u['sex'] = $lang['female']; break;
	default: $u['sex'] = false;
}

#PM
$pm = isset($cfg['pmOn']) && LOGD==1 ? 'pms/edit?to='.$u['ID'] : false;

#URL linku EDYTUJ
if(LOGD)
{
	if($u['ID'] == UID)
	{
		$may = url('account');
	}
	elseif(LEVEL > 2 && admit('U'))
	{
		$may = url('editUser/'.$u['ID'], '', 'admin');
	}
	else
	{
		$may = false;
	}
}
else
{
	$may = false;
}

#Do szablonu
$content->title = $u['login'];
$content->data  = array(
	'u'  => &$u,
	'pm' => $pm,
	'edit' => $may,
	'users'  => url('users'),
	'join_date' => genDate($u['regt'],1), //Data rejestracji
	'last_visit'=> $u['lvis'] ? genDate($u['lvis'],1) : NA
);

if(isset($cfg['userComm']) && $u['opt'] & 2)
{
	include './lib/comm.php';
	comments($u['ID'], 10);
}