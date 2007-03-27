<?php
if(iCMSa!='X159E' || !ChPrv('IP')) exit;
require($catl.'adm_z.php');

#W³. wy³.
if($_GET['co'] && $_GET['id']) db_q('UPDATE {pre}pages SET access="'.(($_GET['co']=='pon')?1:2).'" WHERE ID='.$_GET['id']);

Info($lang['ap_pinfo'].'<br /><br /><div align="center"><a href="adm.php?a=editp">'.$lang['ap_addp'].'</a></div>');
cTable($lang['ap_pman'],4);

echo '
<tr>
 <th>'.$lang['name'].'</th>
 <th style="width: 50px">ID</th>
 <th style="width: 70px">'.$lang['ap_disp'].'</th>
 <th>'.$lang['opt'].'</th>
</tr>';

db_read('ID,name,access','pages','xpage','tn',' ORDER BY ID DESC');
$ile=count($xpage);
for($i=0;$i<$ile;$i++) {
 $ii=$i+1;
 echo '
 <tr>
  <td>'.(($cfg['num']==1)?$ii.'. ':'').'<a href="index.php?co=page&amp;id='.$xpage[$i][0].'">'.$xpage[$i][1].'</a></td>
  <td align="center">'.$xpage[$i][0].'</td>
  <td align="center">'.(($xpage[$i][2]!=2)?$lang['ap_ison'].(($xpage[$i][2]==3)?'*':''):$lang['ap_isoff']).'</td>
  <td align="center"><a href="adm.php?x=ch&amp;id='.$xpage[$i][0].'&amp;co=p'.(($xpage[$i][2]!=2)?'off">'.$lang['ap_toff']:'on">'.$lang['ap_ton']).'</a> &middot; <a href="adm.php?a=editp&amp;id='.$xpage[$i][0].'">'.$lang['edit'].'</a>'.((ChPrv('DEL'))?' &middot; <a href="javascript:a=confirm(\''.$lang['ap_delc'].'\'); if(a) { location=\'adm.php?x=del&amp;co=page&amp;id='.$xpage[$i][0].'\'; } void(0)">'.$lang['del'].'</a>':'').'</td>
 </tr>
 ';
}
eTable();
