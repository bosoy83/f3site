<?php
if(iCMS!=1) exit;
global $cfg,$lang,$user;

#Zalogowany
if(LOGD==1)
{
	echo
	str_replace('%n','<a href="?co=user">'.$user[UID]['login'].'</a>',$lang['uwlogd']).'<ul>'.
 
	(($user[UID]['lv']==3 || $user[UID]['lv']==2)?
	'<li><a href="adm.php">'.$lang['cpanel'].'</a></li>':'').
		
	(($cfg['pms_on']==1)?
	'<li><a href="?co=pms"'.(($user[UID]['pms']==0)?'>'.$lang['pms']
	:' class="newpms"><b>'.$lang['pms'].' ('.$user[UID]['pms'].')</b>').'</a></li>':'').
		
	'<li><a href="?co=account">'.$lang['upanel'].'</a></li><li><a href="login.php?logout=1">'.$lang['logout'].'</a></li></ul>';
}

#Form
else
{
 ?>
 <form action="login.php" method="post">
 <div align="center">
 Login:<br />
 <input name="snduser" style="height: 15px; width: 93%" /><br />
 <?= $lang['pass'] ?>:<br />
 <input name="sndpass" type="password" style="height: 15px; width: 93%" /><br />

 <div style="margin: 3px 0px">
 <input type="checkbox" name="sndr" style="margin: 3px 0px" /> <?= $lang['remlog'] ?>
 </div>

 <input type="submit" value="<?= $lang['logme'] ?>" />
 <?= (($cfg['reg_on']==1)?'<input type="button" value="'.$lang['regme'].'" onclick="location=\'?co=account\'" />':'') ?>
 </div>
 </form>
 <?php
}
?>
