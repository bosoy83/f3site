<?php
if(iCMSa!='X159E' || !ChPrv('B')) exit;
require($catl.'adm_o.php');
Info($lang['bnrinfo'].'<br /><br /><center><a href="?a=editad">'.$lang['addbn'].'</a></center>');
db_read('ID,gen,name,ison','banners','ad','tn',' ORDER BY gen,name');
?>
<script type="text/javascript">
<!--
function Del(id)
{
	if(confirm("<?=$lang['ap_delc']?>"))
	{
		del=new Request('adm.php?x=del&id='+id,'i'+id);
		del.method='POST';
		del.add('co','b')
		del.run();
	}
}
-->
</script>
<?php
cTable($lang['banners'],4);
echo '
<tr>
 <th>'.$lang['name'].'</th>
 <th style="width: 80px">'.$lang['ap_disp'].'</th>
 <th style="width: 50px">GID</th>
 <th>'.$lang['opt'].'</th>
</tr>
';
$ile=count($ad);
for($i=0,$ii=0;$i<$ile;$i++)
{
 echo '
 <tr>
  <td id="i'.$ad[$i][0].'">'.(($cfg['num']==1)?++$ii.'. ':'').$ad[$i][2].'</td>
  <td align="center">'.(($ad[$i][3]==1)?$lang['ap_ison']:$lang['ap_isoff']).'</td>
  <td align="center">'.$ad[$i][1].'</td>
  <td align="center"><a href="?a=editad&amp;id='.$ad[$i][0].'">'.$lang['edit'].'</a>'.((ChPrv('DEL'))?' &middot; <a href="javascript:Del('.$ad[$i][0].')">'.$lang['del'].'</a>':'').'</td>
 </tr>';
}
eTable();
?>
