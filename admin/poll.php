<?php
if(iCMSa!='X159E' || !ChPrv('f3s') || $_REQUEST['poll']) exit;
require($catl.'f3s.php');
Info($lang['ap_ipoll'].'<br /><br /><center><a href="?a=editpoll">'.$lang['addpoll'].'</a></center>');
#Operacje
if($_POST)
{
 $ile=count($_POST['chk']);
 if($ile>0 && ChPrv('DEL'))
 {
  $_pqr=GetIDs($_POST['chk']);
  if($_POST['delp'])
  {
   db_q('DELETE FROM {pre}polls WHERE ID IN ('.join(',',$_pqr).')');
   db_q('DELETE FROM {pre}answers WHERE IDP IN ('.join(',',$_pqr).')');
	 db_q('DELETE FROM {pre}comms WHERE th="12_'.join('" || th="12_',$_pqr).'"');
	 db_q('DELETE FROM {pre}pollvotes WHERE ID IN ('.join(',',$_pqr).')');
  }
  elseif($_POST['zerp'])
  {
   db_q('UPDATE {pre}answers SET num=0 WHERE IDP IN ('.join(',',$_pqr).')');
   db_q('UPDATE {pre}polls SET num=0 WHERE ID IN ('.join(',',$_pqr).')');
	 db_q('DELETE FROM {pre}pollvotes WHERE ID IN ('.join(',',$_pqr).')');
  }
  unset($_pqr);
 }
}
#Lista
echo '<form action="?a=poll" method="post">';
cTable($lang['polls'],5);
db_read('ID,name,num,access','polls','poll','ta',' ORDER BY ID DESC');
$ile=count($poll);
echo '
<tr>
 <th>'.$lang['name'].'</th>
 <th style="width: 55px">'.$lang['votes'].'</th>
 <th style="width: 60px">'.$lang['lang'].'</th>
 <th>'.$lang['opt'].'</th>
 <th style="width: 30px"></th>
</tr>';
for($i=0;$i<$ile;$i++)
{
 echo '
 <tr align="center">
  <td align="left">'.(($cfg['num']==1)? ($i+1).'. ':'').$poll[$i]['name'].'</td>
  <td>'.$poll[$i]['num'].'</td>
  <td>'.$poll[$i]['access'].'</td>
  <td><a href="?a=editpoll&amp;id='.$poll[$i]['ID'].'">'.$lang['edit'].'</a></td>
  <td><input type="checkbox" name="chk['.$poll[$i]['ID'].']" /></td>
 </tr>';
}
if(ChPrv('DEL')) echo '<tr><td colspan="5" class="eth"><input type="submit" style="display: none" /><input type="submit" name="delp" value="'.$lang['del'].'" /> <input type="submit" name="zerp" value="'.$lang['zerp'].'" /></td></tr>';
eTable(); echo '</form>';
?>
