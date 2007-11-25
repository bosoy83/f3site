<?php
if(iCMSa!=1 || !Admit('B')) exit;
require($catl.'adm_o.php');

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

#Info
Info($lang['bnrinfo'].'<br /><br /><center><a href="?a=editad">'.$lang['addbn'].'</a></center>');

#Odczyt
$res=$db->query('SELECT ID,gen,name,ison FROM '.PRE.'banners ORDER BY gen,name');
$res->setFetchMode(3); //NUM

OpenBox($lang['banners'],4);
echo '
<tr>
	<th>'.$lang['name'].'</th>
	<th style="width: 80px">'.$lang['ap_disp'].'</th>
	<th style="width: 50px">GID</th>
	<th>'.$lang['opt'].'</th>
</tr>';

$ile=0;
foreach($res as $ad)
{
	echo '<tr>
	<td id="i'.$ad[0].'">'.++$ile.'. '.$ad[2].'</td>
	<td align="center">'.(($ad[3]==1)?$lang['ap_ison']:$lang['ap_isoff']).'</td>
	<td align="center">'.$ad[1].'</td>
	<td align="center">
		<a href="?a=editad&amp;id='.$ad[0].'">'.$lang['edit'].'</a>'.
		((Admit('DEL'))?' &middot; <a href="javascript:Del('.$ad[0].')">'.$lang['del'].'</a>':'').'
	</td>
</tr>';
}

$res=null;
CloseBox();
?>
