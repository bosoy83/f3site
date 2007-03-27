<?php
if(iCMSa!='X159E' || !ChPrv('AD')) exit;
require($catl.'rights.php');
Info($lang['ap_iadms']);
cTable($lang['admins'],3);
echo '
<tr>
 <th>'.$lang['login'].'</th>
 <th>'.$lang['privs'].'</th>
 <th>'.$lang['opt'].'</th>
</tr>
';
db_read('ID,login,adm','users','ausr','tn',' WHERE lv=2 OR lv=4');
$ile=count($ausr);
for($i=0;$i<$ile;$i++)
{
 echo '
 <tr>
  <td>'.(($cfg['num']==1)?($i+1).'. ':'').$ausr[$i][1].'</td>
  <td align="center" style="width: 40%">'.str_replace('|',' ',$ausr[$i][2]).'</td>
  <td align="center">'.$lang['edit'].': <a href="?a=eadm&amp;id='.$ausr[$i][0].'">'.strtolower($lang['privs']).'</a> &middot; <a href="?a=uedit&amp;id='.$ausr[$i][0].'">'.$lang['profile'].'</a></td>
 </tr>
 ';
}
eTable();
?>
