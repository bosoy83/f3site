<?php
if(iCMSa!=1 || !Admit('CFG')) exit;
require $catl.'adm_conf.php';

#Opce wtyczki?
if(isset($_GET['file']))
{
	$file='./plugins/'.str_replace(array('/','.'),'',$_GET['file']).'/config.php';
	if(file_exists($file))
	{
		include './lib/config.php';
		include $file;
	}
}

OpenBox($lang['opt'],1);
?>
<tr><td>
	<table cellpadding="5" style="width: 100%"><tbody style="line-height: 18px; text-align: center">
	<tr>
		<td><a href="?a=cfg"><img src="img/admin/c1.png" alt="" /><br /><?= $lang['conf1'] ?></a></td>
		<td><a href="?a=cfgz"><img src="img/admin/c2.png" alt="" /><br /><?= $lang['content'] ?></a></td>
		<td><a href="?a=cfgm"><img src="img/admin/c3.png" alt="" /><br />E-mail</a></td>
		<td><a href="?a=wr"><img src="img/admin/c5.png" alt="" /><br /><?= $lang['conf5'] ?></a></td>
		<td><a href="?a=emots"><img src="img/admin/c6.png" alt="" /><br /><?= $lang['emots'] ?></a></td>
		<td><a href="?a=cfguser"><img src="" alt="" /><br /><?= $lang['users'] ?></a></td>
	</tr>
 
<?php
#Odczyt opcji wtyczek
$res=$db->query('SELECT ID,name,img FROM '.PRE.'confmenu WHERE lang=1 OR lang="'.$nlang.'"');
$res->setFetchMode(3); //NUM

#Do zmiennej
$out='';
$y=1;
foreach($res as $item)
{	
  if($y==1) $out.='<tr>';
	$out.='<td><a href="?a='.$item[0].'"><img src="'.$item[2].'" alt="ENTER" /><br />'.$item[1].'</a></td>';
  if($y==3) { $out.='</tr>'; $y=1; } else { $y++; }
}

#Wyœwietl
if($out!='')
{
	echo '<tr><td colspan="6">'.$lang['plugs'].':</td></tr><tr><td>'.$out.'</td></tr>';
}
?>

	</tbody></table>
</tr></td>
<?php CloseBox(); ?>
