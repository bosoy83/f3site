<?php
if(iCMS!='E123') exit;
#Strona
if($_GET['page'] && $_GET['page']!=1)
{
 $page=$_GET['page'];
 $st=($page-1)*20;
}
else
{
 $page=1;
 $st=0;
}
#Warunek
switch($id)
{
 case 1: $_qr=' WHERE p.st=4 AND p.owner='.UID; $_txt=&$lang['pms_6']; break; #Wys³.
 case 2: $_qr=' WHERE p.st=1 AND p.usr='.UID; $_txt=&$lang['pms_8']; Info($lang['pms_3i']); break; #Do wys³.
 case 3: $_qr=' WHERE p.st=3 AND p.owner='.UID; $_txt=&$lang['pms_7']; break; #Zap.
 default: $id=4; $_qr=' WHERE (p.st=1 OR p.st=2) AND p.owner='.UID; $_txt=&$lang['pms_5']; #Odebrane
}
$ilepm=db_count('p.ID','pms p',$_qr);
if($ilepm>0)
{
 db_read('p.ID,p.topic,p.usr,p.owner'.(($id==4)?',p.st':'').',p.date,u.ID as uid,u.login','pms p LEFT JOIN {pre}users u ON p.'.(($id==2)?'owner':'usr').'=u.ID','pm','ta',$_qr.' ORDER BY ID DESC LIMIT '.$st.',20');
 $ile=count($pm);
 echo '<form action="?co=pms&amp;act=m&amp;id='.$id.'" method="post">';
 cTable($_txt.' ('.$ilepm.')',3);
 unset($_txt,$_qr);
 echo '
 <tr>
  <th>'.$lang['title'].'</th>
  <th>'.(($id==4 || $id==3)?$lang['pms_12']:$lang['pms_13']).'</th>
  <th style="width: 7%"><input type="checkbox" onclick="for(i=0;x=document.getElementsByTagName(\'input\')[i];i++) { if(x.name.indexOf(\'pmdel\')==0) { if(this.checked) { x.checked=true } else { x.checked=false } } }" /></th>
 </tr>';
 #Lista
 for($i=0;$i<$ile;$i++)
 {
  echo '
  <tr>
   <td>'.(($cfg['num']==1)?($i+1).'. ':'').'<a style="font-weight: '.(($pm[$i]['st']==1)?'bold':'normal').'" href="?co=pms&amp;act=v&amp;id='.$pm[$i]['ID'].'">'.$pm[$i]['topic'].'</a></td>
   <td align="center"><a href="?co=user&amp;id='.$pm[$i]['uid'].'">'.$pm[$i]['login'].'</a></td>
   <td align="center"><input type="checkbox" name="pmdel['.$pm[$i]['ID'].']" /></td>
  </tr>
  ';
 }
 echo '<tr class="eth">
 <td><input type="button" onclick="javascript: if(confirm(\''.$lang['pms_26'].'\')) this.form.submit()" value="'.$lang['delch'].'" /> '.(($id==4)?'<input type="submit" name="pmsav" value="'.$lang['pms_25'].'" />':'').'</td>
 <td colspan="2">'.$lang['page'].' '.Pages($page,$ilepm,20,'?co=pms&amp;act=l&amp;id='.$id,1).'</td>
</tr>';
 eTable();
 echo '</form>';
}
else
{
 Info('<center>'.$_txt.'<br /><br />'.$lang['pms_11'].'</center>');
}
?>
