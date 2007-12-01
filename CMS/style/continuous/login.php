<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Log in :: <?= $cfg['title'] ?></title>
	<meta name="robots" content="no-index" />
	<link type="text/css" rel="stylesheet" href="style/continuous/s.css" />
</head>
<body>
<div style="margin: auto; width: 320px; text-align: center">
	<form action="login.php" method="post">
	<h1>Control Panel</h1>
	<?php OpenBox($lang['user'],2) ?>
	<tr>
		<td style="width: 30%"><b>1. <?= $lang['login'] ?>:</b></td>
		<td><input name="snduser" /></td>
	</tr>
	<tr>
		<td><b>2. <?= $lang['pass'] ?>:</b></td>
		<td><input type="password" name="sndpass" /></td>
	</tr>
	<tr>
		<td class="pth"><input type="submit" value="OK" /><input name="fromadm" type="hidden" /></td>
		<td class="pth"><input type="checkbox" name="sndr" /> <?= $lang['remlog'] ?></td>
	</tr>
	<?php CloseBox() ?>
	</form>
</div>
</body>
</html>