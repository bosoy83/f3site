<?php /* Prywatne wiadomo¶ci */
if(iCMS!=1) exit;

#Jêzyk
require(LANG_DIR.'pms.php');

#Tytu³ strony
$content->title = $lang['pm_3'];

#Jest dostêp?
if($cfg['pms_on']==1 && LOGD==1)
{
	#Nowe PM
	if($user[UID]['pms']==0)
	{
		$pm_new = $lang['pm_nm0'];
	}
	else
	{
		$pm_new = str_replace('%', '<b>'.$user[UID]['pms'].'</b>',
			(($user[UID]['pms']==1) ? $lang['pm_nm1'] : $lang['pm_nm']) );
	}

	#Dane do szablonu
	$content->data = array(
		'new'   => $pm_new,
		'limit' => (int)$cfg['pm_limit'],
		'size'  => db_count('ID','pms WHERE owner='.UID)
	);

	#Akcja
	if(isset($_GET['act']))
	{
		switch($_GET['act']) {
			case 'v': require('./mod/pms/message.php'); break;
			case 'e': require('./mod/pms/posting.php'); break;
			case 'm': require('./mod/pms/action.php'); break;
		}
	}
	else require('./mod/pms/list.php');
}
elseif(LOGD==2)
{
	$content->info($lang['pm_2']);
}
else
{
	$content->info($lang['pm_1']);
}
?>
