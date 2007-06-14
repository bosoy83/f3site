<?php
if(iCMSa!='X159E' || !ChPrv('CFG')) exit;
require($catl.'adm_conf.php');

cTable($lang['opt'],1);
?>
<tr><td>
 <table cellpadding="5" style="width: 100%"><tbody style="line-height: 18px; text-align: center">
 <tr>
  <td><a href="?a=cfg"><img src="img/admin/c1.png" alt="" /><br /><?= $lang['conf1'] ?></a></td>
	<td><a href="?a=cfgz"><img src="img/admin/c2.png" alt="" /><br /><?= $lang['content'] ?></a></td>
	<td><a href="?a=cfgm"><img src="img/admin/c3.png" alt="" /><br />E-mail</a></td>
	<td><a href="?a=cfgdb"><img src="img/admin/c4.png" alt="" /><br /><?= $lang['lang'] ?> &amp; SQL</a></td>
 </tr>
 <tr>
  <td><a href="?a=wr"><img src="img/admin/c5.png" alt="" /><br /><?= $lang['conf5'] ?></a></td>
	<td><a href="?a=emots"><img src="img/admin/c6.png" alt="" /><br /><?= $lang['emots'] ?></a></td>
	<td><a href="?a=cfguser"><img src="" alt="" /><br /><?= $lang['users'] ?></a></td>
 </tr>
 
<?php
#Odczyt SQL
db_read('ID,name,img','confmenu','item','tn',' WHERE lang=1 OR lang="'.$nlang.'"');
$ile=count($item);
if($ile>0)
{
 echo '<tr><td colspan="4">'.$lang['plugs'].':</td></tr>';
 $y=1;
 for($i=0;$i<$ile;$i++)
 {
  if($y==1) echo '<tr>';
  echo '<td><a href="?a='.$item[$i][0].'">'.(($item[$i][2]=='')?'':'<img src="'.$item[$i][2].'" alt="ENTER" /><br />').$item[$i][1].'</a></td>';
  if($y==3) { echo '</tr>'; $y=1; } else { $y++; }
 }
}
?>

 </tbody></table>
</tr></td>
<?php eTable(); ?>