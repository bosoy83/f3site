<?php
if(iCMS!=1) exit;

#Zalogowany
if(LOGD==1)
{
	echo
	sprintf($lang['uwlogd'],'<a href="'.url('user').'">'.$user['login'].'</a>').'<ul>'.

	((LEVEL > 1) ?
	'<li><a href="'.url('edit').'">'.$lang['mantxt'].'</a></li>' : '').

	((LEVEL > 2) ?
	'<li><a href="admin">'.$lang['cpanel'].'</a></li>':'').

	((isset($cfg['pmOn']))?
	'<li><a href="'.url('pms').'"'.(($user['pms']>0)?' class="newpms"><b>'.$lang['pms'].' ('.$user['pms'].')</b>':'>'.$lang['pms']).'</a></li>':'').

	'<li><a href="'.url('account').'">'.$lang['upanel'].'</a></li><li><a href="login.php?logout">'.$lang['logout'].'</a></li></ul>';

	return;
}

?><form action="login.php" method="post"><div style="text-align: center">
	Login:
	<input name="u" style="height: 15px; width: 93%" />
	<?= $lang['pass'] ?>:
	<input name="p" type="password" style="height: 15px; width: 93%" />

	<div style="margin: 5px 0px">
	<input type="checkbox" name="auto" /> <?= $lang['remlog'] ?>
	</div>

	<input type="submit" value="<?= $lang['logme'] ?>" />
	<input type="submit" value="<?= $lang['regme'] ?>" name="reg" />
</div></form>