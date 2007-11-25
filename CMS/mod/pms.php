<?php
if(iCMS!=1) exit;
require($catl.'pms.php');
$id=isset($_GET['id'])?$_GET['id']:0;

if($cfg['pms_on']==1 && LOGD==1)
{
	#Ilo¶æ
	$res=$db->query('SELECT COUNT(ID) FROM '.PRE.'pms WHERE owner='.UID);
	$pm_ile=$lang['pm_15'].$res->fetchColumn().' / '.$cfg['pm_limit'];
	$res=null;
	
	#Nowe PM
	if($user[UID]['pms']==0)
	{
		$pm_new=$lang['pm_nm0'];
	}
	else
	{
		$pm_new=str_replace('%','<b>'.$user[UID]['pms'].'</b>',
		(($user[UID]['pms']==1)?$lang['pm_nm1']:$lang['pm_nm']) );
	}
	#Styl
	include($catst.'pm-top.php');

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
	Info($lang['pm_2']);
}
else
{
	Info($lang['pm_1']);
}
?>
