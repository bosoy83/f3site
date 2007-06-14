<?php
if(iCMSa!='X159E' || !ChPrv('IP')) exit;
require($catl.'adm_z.php');
if(ChPrv('DEL')) { $del=1; } else { $del=2; }

#W³. wy³.
if($_GET['co'])
{
	if($_GET['id']) db_q('UPDATE {pre}pages SET access="'.(($_GET['co']=='on')?1:2).'" WHERE ID='.$_GET['id']);
}
?>
<script type="text/javascript">
<!--
function Del(id)
{
 if(confirm("<?=$lang['ap_delc']?>"))
 {
	del=new Request("adm.php?x=del&id="+id,'i'+id);
	del.method='POST';
	del.add('co','page')
	del.run()
 }
}
-->
</script>
<?php
Info($lang['ap_pinfo'].'<br /><br /><center><a href="adm.php?a=editpage">'.$lang['ap_addp'].'</a></center>');
cTable($lang['ap_pman'],4);

echo '
<tr>
 <th>'.$lang['name'].'</th>
 <th style="width: 50px">ID</th>
 <th style="width: 70px">'.$lang['ap_disp'].'</th>
 <th>'.$lang['opt'].'</th>
</tr>';

#Odczyt
db_read('ID,name,access','pages','xpage','tn',' ORDER BY ID DESC');
$ile=count($xpage);

for($i=0,$ii=0;$i<$ile;++$i)
{
 $xid=$xpage[$i][0];
 echo '
 <tr>
  <td id="i'.$xid.'">'.(($cfg['num']==1)?++$ii.'. ':'').'<a href="index.php?co=page&amp;id='.$xid.'">'.$xpage[$i][1].'</a></td>
  <td align="center">'.$xid.'</td>
  <td align="center">'.(($xpage[$i][2]!=2)?$lang['ap_ison']:$lang['ap_isoff']).'</td>
  <td align="center">
		<a href="adm.php?a=pages&amp;id='.$xid.'&amp;co='.(($xpage[$i][2]!=2)?'off">'.$lang['ap_toff']:'on">'.$lang['ap_ton']).'</a>
		&middot; <a href="adm.php?a=editpage&amp;id='.$xid.'">'.$lang['edit'].'</a>
		'.(($del==1)?' &middot; <a href="javascript:Del('.$xid.')">'.$lang['del'].'</a>':'').'
	</td>
 </tr>';
}
eTable();
