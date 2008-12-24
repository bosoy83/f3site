<?php /* Prywatne wiadomo¶ci */
if(iCMS!=1) exit;

#Jêzyk
require LANG_DIR.'pms.php';

#Tytu³ strony
$content->title = $lang['pm3'];

#Jest dostêp?
if(isset($cfg['pmOn']) && LOGD==1)
{
	#Nowe PM
	if($user[UID]['pms']==0)
	{
		$pm_new = $lang['zero'];
	}
	else
	{
		$pm_new = $user[UID]['pms']==1 ? $lang['new1'] :
			str_replace('%', '<b>'.$user[UID]['pms'].'</b>', $lang['new2']);
	}

	#Dane do szablonu
	$content->data = array(
		'new'   => $pm_new,
		'limit' => (int)$cfg['pmLimit'],
		'size'  => (int)$db->query('SELECT COUNT(*) FROM '.PRE.'pms WHERE owner='.UID)->fetch(7)
	);

	#Akcja
	if(isset($_GET['act']))
	{
		switch($_GET['act']) {
			case 'v': require './mod/pms/message.php'; break;
			case 'e': require './mod/pms/posting.php'; break;
			case 'm': require './mod/pms/action.php'; break;
		}
	}
	else require './mod/pms/list.php';
}
elseif(LOGD!=1)
{
	$content->info($lang['pm2']);
}
else
{
	$content->info($lang['pm1']);
}