<?php
if(iCMS!='E123') exit;
global $cfg,$lang,$user;
if(LOGD==1)
{
 echo str_replace('%n','<a href="?co=user&amp;id='.$user[UID]['ID'].'">'.$user[UID]['login'].'</a>',$lang['uwlogd']).' <br />';
 if($user[UID]['lv']==3 || $user[UID]['lv']==2) mlink($lang['cpanel'],'adm.php',0);
 if($cfg['pms_on']==1) mlink((($user[UID]['pms']=='0')?$lang['pms']:'<b>'.$lang['pms'].' ('.$user[UID]['pms'].')</b>'),'?co=pms',0);
 mlink($lang['upanel'],'?co=uedit',0);
 mlink($lang['logout'],'login.php?logout=1',0);
}
else
{
 ?>
 <form style="margin: 0" action="login.php" method="post">
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
