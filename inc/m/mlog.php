<?php
if(iCMS!='E123') exit;
global $cfg,$lang,$user;

#Zalogowany
if(LOGD==1)
{
	echo
		str_replace('%n','<a href="?co=user&amp;id='.$user[UID]['ID'].'">'.$user[UID]['login'].'</a>',$lang['uwlogd']).'<ul>'.
 
	(($user[UID]['lv']==3 || $user[UID]['lv']==2)?
		'<li><a href="adm.php">'.$lang['cpanel'].'</a></li>':'').
		
	(($cfg['pms_on']==1)?
		'<li><a href="?co=pms"'.(($user[UID]['pms']=='0')?'>'.$lang['pms']
		:' class="newpms"><b>'.$lang['pms'].' ('.$user[UID]['pms'].')</b>').'</a></li>':'').
		
	'<li><a href="?co=uedit">'.$lang['upanel'].'</a></li><li><a href="login.php?logout=1">'.$lang['logout'].'</a></li></ul>';
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
 <span style="font-size: 3px"><br /></span>
 <input type="checkbox" name="sndr" value="1" /> <?= $lang['remlog'] ?><br />
 <span style="font-size: 3px"><br /></span>
 <input type="submit" value="<?= $lang['logme'] ?>" style="width: 40%; margin-bottom: 2px" />
 <?= (($cfg['reg_on']==1)?'<input type="button" value="'.$lang['regme'].'" style="width: 53%; margin-bottom: 2px" onclick="location=\'?co=uedit\'" /><br />':'') ?>
 
 </div>
 </form>
 <?php
}
?>
