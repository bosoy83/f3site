<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript" src="lib/js.js"></script>
	<meta http-equiv="Content-Language" content="<?= $nlang ?>" />
	<meta name="Robots" content="no-index" />
	<link type="text/css" rel="stylesheet" href="style/continuous/s.css" />
	<link type="text/css" rel="stylesheet" href="style/continuous/admin.css" />
	<title>Admin :: <?= $cfg['title'] ?></title>
</head>
<body>

<table class="all" cellspacing="1" align="center">
<tbody style="background-color: #E8E8E6">
<tr>
	<td colspan="2" id="header">
		<img src="style/continuous/img/bnr.png" alt="Logo" />
	</td>
</tr>
<tr>
 <td id="gm" colspan="2">
  <div style="float: left">
		<a href="index.php"><?= $lang['escadm'] ?></a> |
		<a href="adm.php"><?= $lang['summary'] ?></a>
	</div>
  <div style="float: right"><?= TODAY ?></div>
 </td>
</tr>
<tr>
	<td style="width: 20%; padding: 9px" valign="top"><?= $menu ?></td>
	<td style="background-color: #EDEDEB; padding: 9px" valign="top" id="main"><?php include(MOD) ?></td>
</tr>
<tr>
	<td colspan="3" align="center" style="background-color: #DCDCD6"><?= $cfg['footer'] ?></td>
</tr>
</tbody>
</table>

</body>
</html>
