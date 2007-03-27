<?php
if(iCMS!='E123') exit;
db_read('ID,name,num,date','polls','apoll','ta',' WHERE access="'.db_esc($nlang).'" ORDER BY ID DESC');
cTable($lang['arch'],3);
echo '
<tr>
 <th>'.$lang['name'].'</th>
 <th style="width: 10%">'.$lang['votes'].'</th>
 <th style="width: 25%">'.$lang['added'].'</th>
</tr>
';
$ile=count($apoll);
for($i=0;$i<$ile;$i++)
{
 echo '
 <tr>
  <td>'.(($cfg['num']==1)?($i+1).'. ':'').'<a href="?co=poll&amp;id='.$apoll[$i]['ID'].'">'.$apoll[$i]['name'].'</a></td>
  <td align="center">'.$apoll[$i]['num'].'</td>
  <td align="center">'.genDate($apoll[$i]['date']).'</td>
 </tr>';
}
eTable();
?>
