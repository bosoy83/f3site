<?php /* Prywatne wiadomo�ci */
if(iCMS!=1) exit;

#J�zyk
require LANG_DIR.'pms.php';

#Tytu� strony
$content->title = $lang['pm3'];

#Jest dost�p?
if($cfg['pmOn']==1 && LOGD==1)
{
	#Nowe PM
	if($user[UID]['pms']==0)
	{
		$pm_new = $lang['zero'];
	}
	else
	{
		$pm_new = str_replace('%', '<b>'.$user[UID]['pms'].'</b>',
			(($user[UID]['pms']==1) ? $lang['new1'] : $lang['new2']) );
	}

	#Dane do szablonu
	$content->data = array(
		'new'   => $pm_new,
		'limit' => (int)$cfg['pmLimit'],
		'size'  => db_count('ID','pms WHERE owner='.UID)
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
elseif(LOGD==2)
{
	$content->info($lang['pm2']);
}
else
{
	$content->info($lang['pm1']);
}