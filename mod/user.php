<?php
if(iCMS!=1) exit;
if($_GET['id']) { $id=$_GET['id']; } else { $id=UID; }
require($catl.'profile.php');

#Pobierz dane
db_read('*','users','user',$id,' WHERE ID='.$id);
if($user[$id])
{
	$xuser=&$user[$id];
	$xdate=genDate($xuser['regt']);
	$xdate2=(($xuser['lvis']=='0000-00-00 00:00:00')?$lang['notp']:genDate($xuser['lvis']));
	$xuser['about']=Emots(nl2br($xuser['about']));
	
	#GG
	$ustat['gg']=(!empty($xuser['gg']))?'<a href="gg:'.$xuser['gg'].'"><img src="http://status.gadu-gadu.pl/users/status.asp?id='.$xuser['gg'].'" alt="GG" /></a> '.$xuser['gg']:$lang['notp'];

	#Tlen
	$ustat['t']=(!empty($xuser['tlen']))?'<a href="http://ludzie.tlen.pl/'.$xuser['tlen'].'/" target="_blank"><img src="http://status.tlen.pl/?u='.$xuser['tlen'].'&t=1" alt="TLEN" /></a> '.$xuser['tlen']:$lang['notp'];

	#ICQ
	$ustat['icq']=(!empty($xuser['icq']))?'<a href="http://www.icq.com/whitepages/about_me.php?Uin='.$xuser['icq'].'"><img src="http://status.icq.com/online.gif?icq='.$xuser['icq'].'&img=5" alt="ICQ" /></a> '.$xuser['icq']:$lang['notp'];

	#Skype
	$ustat['s']=(!empty($xuser['skype']))?'<!-- Skype My status button http://www.skype.com/go/skypebuttons --><script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js"></script><img src="http://mystatus.skype.com/smallclassic/'.$xuser['skype'].'" style="cursor: pointer" onclick="if(confirm(\''.$lang['callq'].$xuser['skype'].'?\')) location=\'skype:'.$xuser['skype'].'?call\' }" alt="My status" /></a>':$lang['notp'];
	
	#WWW
	$ustat['www']=(!empty($xuser['www']) && $xuser['www']!='http://')?'<a href="'.$xuser['www'].'" target="_blank">'.$xuser['www'].'</a>':$lang['notp'];
	
	#E-mail
	$xuser['mail']=str_replace('@','&#64;',$xuser['mail']);
	$xuser['mail']=str_replace('.','&#46;',$xuser['mail']);
	$ustat['m']=($xuser['mvis']==1)?'<a href="mailto:'.$xuser['mail'].'">'.$xuser['mail'].'</a>':$lang['private'];
	
	#Sk±d?
	$ustat['fr']=(!empty($xuser['city']))?$xuser['city']:$lang['notp'];
	
	#Opcje (PM)
	$ustat['o']=($cfg['pms_on'])?'&bull; <a href="?co=pms&amp;act=e&amp;a='.$xuser['login'].'">'.$lang['writepm'].'</a>':$lang['none'];
	
	#Wy¶w.
	require($catst.'profile.php');
}
else
{
 Info($lang['usrne']);
}
?>
