<?php
if(iCMS!='E123') exit;
#Do³±cz
if($_GET['act']=='join' && $_GET['id'])
{
 if(LOGD==1 && db_count('ID','groups',' WHERE opened=1 AND access!=3 AND ID='.$_GET['id'])==1) { db_q('UPDATE '.$db_pre.'users SET gid='.$_GET['id'].' WHERE ID='.UID); $user[UID]['gid']=$_GET['id']; }
}
#Lista
db_read('ID,name,dsc,opened','groups','group','ta',' WHERE (access=1 || access="'.$nlang.'")');
$ile=count($group);
cTable($lang['groups'].((ChPrv('UGR'))?' <span style="font-weight: normal">(<a href="adm.php?a=groups">'.$lang['edit'].'</a>)</span>':''),1);
for($i=0;$i<$ile;$i++)
{
 echo '<tr><td class="txt"><b><a href="?co=users&amp;id='.$group[$i]['ID'].'">'.$group[$i]['name'].'</a></b>'.(($group[$i]['opened']==1 && LOGD==1 && $user[UID]['gid']!=$group[$i]['ID'])?' (<a href="?co=groups&amp;act=join&amp;id='.$group[$i]['ID'].'">'.$lang['join'].'</a>)':'').'<br />'.nl2br($group[$i]['dsc']).'</td></tr>';
}
eTable();
?>
