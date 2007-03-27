<?php
if(iCMSa!='X159E' || !ChPrv('LOG')) exit;
#Usuñ?
if($_POST)
{
 $x=array();
 foreach($_POST['item'] as $key=>$val)
 {
  $x[]=$key;
 }
 unset($key,$val);
 db_q('DELETE FROM {pre}log WHERE ID IN ('.join(',',$x).')');
}
#Strona
if($_GET['page'] && $_GET['page']!=1)
{
 $page=$_GET['page'];
 $st=($page-1)*30;
}
else
{
 $page=1;
 $st=0;
}
#SQL
db_read('l.*,u.login','log l LEFT JOIN {pre}users u ON l.user=u.ID AND l.user!=0','log','ta',' LIMIT '.$st.',30');
$ilec=db_count('ID','log','');
$ile=count($log);
echo '<form action="?a=log&amp;page='.$page.'" method="post">';
#Lista
cTable('Log',4);
echo '<tr>
 <th>'.$lang['title'].'</th>
 <th>'.$lang['added'].'</th>
 <th>'.$lang['user'].'</th>
 <th style="width: 30px"><input type="checkbox" onclick="x=document.getElementsByTagName(\'input\'); ile=x.length; for(i=0;i<ile;i++) { if(x[i].name.indexOf(\'item\')==0) { if(this.checked) { x[i].checked=true } else { x[i].checked=false } } }" /></th>
</tr>';
for($i=0;$i<$ile;$i++)
{
 echo '<tr align="center">
  <td align="left">'.$log[$i]['name'].'</td>
	<td>'.genDate($log[$i]['date']).'</td>
	<td>'.(($log[$i]['user']==0)?$log[$i]['ip']:'<a href="index.php?co=user&amp;id='.$log[$i]['user'].'" title="IP: '.$log[$i]['ip'].'">'.$log[$i]['login'].'</a>').'</td>
	<td><input type="checkbox" name="item['.$log[$i]['ID'].']" /></td>
 </tr>';
}
echo '<tr class="eth">
 <td colspan="2"><input type="button" value="'.$lang['del'].'" onclick="if(confirm(\''.$lang['ap_delc'].'\')) submit()" /></td>
 <td colspan="2">'.$lang['page'].': '.Pages($page,$ilec,30,'?a=log',1).'</td>
 
</tr>';
eTable();
?>
</form>
