<script type="text/javascript">
<!--
function Del(co) { if(confirm('<?=$lang['ap_delc']?>')) { location='?a=groups&del=1&id='+co; } }
-->
</script>
<?php if(iCMSa!='X159E' || !ChPrv('UG')) exit;
#Usuwanie
if($_GET['del']==1)
{
 if($_GET['id'] && ChPrv('DEL')) db_q('DELETE FROM {pre}groups WHERE ID='.$_GET['id']);
}
require($catl.'adm_o.php');
Info($lang['ugrw'].'<br /><br /><center><a href="?a=egroup">'.$lang['gradd'].'</a></center>');
db_read('ID,name,opened','groups','group','tn','');
$ile=count($group);
cTable($lang['ugr'],4);
echo '<tr>
 <th>'.$lang['name'].'</th>
 <th>ID</th>
 <th>'.$lang['opened'].'?</th>
 <th>'.$lang['opt'].'</th>
</tr>';
for($i=0;$i<$ile;$i++)
{
 echo '<tr align="center">
  <td align="left">'.(($cfg['num']==1)?($i+1).'. ':'').'<a href="?a=users&amp;gid='.$group[$i][0].'">'.$group[$i][1].'</a></td>
  <td>'.$group[$i][0].'</td>
  <td>'.(($group[$i][2]==1)?$lang['yes']:$lang['no']).'</td>
  <td><a href="?a=egroup&amp;id='.$group[$i][0].'">'.$lang['edit'].'</a> &middot; <a href="javascript:Del('.$group[$i][0].')">'.$lang['del'].'</a></td>
 </tr>';
}
eTable();
?>
