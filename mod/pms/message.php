<?php
if(iCMS!=1) exit;
if($id=='') exit('ID error!');
$pm['ID']='';
db_read('*','pms','pm','oa',' WHERE (owner='.UID.' OR (usr='.UID.' AND st=1)) AND ID='.$id);
if($pm['ID']=='')
{
 Info($lang['pms_9']);
}
else
{
 cTable($pm['topic'],2);
 #BBCode
 if($pm['bbc']==1)
 {
  if($cfg['bbc']==1)
  {
   require_once('inc/bbcode.php');
   $pm['txt']=ParseBBC($pm['txt']);
  }
 }
 #Przeczytana?
 if($pm['st']==1 && $pm['owner']==UID)
 {
  db_q('UPDATE {pre}pms SET st=2 WHERE ID='.$pm['ID']);
  db_q('UPDATE {pre}users SET pms=pms-1 WHERE ID='.$pm['owner']);
  $user[UID]['pms']--;
  $pm['st']=2;
 }
 echo '
 <tr>
  <td class="pth" align="right" style="width: 25%">'.$lang['author'].':&nbsp;</td>
  <td>'.Autor($pm['usr']).'</td>
 </tr>
 <tr>
  <td class="pth" align="right">'.$lang['sent'].':&nbsp;</td>
  <td>'.genDate($pm['date']).'</td>
 </tr>
 <tr>
  <td colspan="2">'.nl2br(Emots($pm['txt'])).'</td>
 </tr>
 <tr>
  <td class="eth" colspan="2"><input type="button" value="'.(($pm['st']==2)?$lang['pms_10'].'" onclick="location=\'?pm_r=1&amp;':$lang['edit'].'" onclick="location=\'?').'co=pms&amp;act=e&amp;id='.$pm['ID'].'\'" /> <input type="button" value="'.$lang['del'].'" onclick="location=\'?co=pms&amp;act=m&amp;act2=1&amp;id='.$pm['ID'].'\'" />'.(($pm['st']==2)?' <input type="button" value="'.$lang['pms_25'].'" onclick="location=\'?co=pms&amp;act=m&amp;pmsav=1&amp;id='.$id.'\'"':'').'</td>
 </tr>';
 eTable();
}
