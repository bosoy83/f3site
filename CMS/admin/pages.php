<?php
if(iCMSa!=1 || !Admit('IP')) return;
require(LANG_DIR.'adm_o.php');

#Mo¿e usuwaæ?
$del=Admit('DEL')?1:0;

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

#Info
Info($lang['ap_pinfo'].'<br /><br /><center><a href="adm.php?a=editpage">'.$lang['ap_addp'].'</a></center>');
OpenBox($lang['ap_pman'],4);

echo '
<tr>
	<th>'.$lang['name'].'</th>
	<th style="width: 50px">ID</th>
	<th style="width: 70px">'.$lang['ap_disp'].'</th>
	<th>'.$lang['opt'].'</th>
</tr>';

#Odczyt
$res=$db->query('SELECT ID,name,access FROM '.PRE.'pages ORDER BY ID DESC');
$res->setFetchMode(3); //NUM

$ile=0;
foreach($res as $page)
{
	$xid=$page[0];
	echo '<tr>
  <td id="i'.$xid.'">'.++$ile.'. <a href="index.php?co=page&amp;id='.$xid.'">'.$page[1].'</a></td>
  <td align="center">'.$xid.'</td>
  <td align="center">'.(($page[2]!=2)?$lang['ap_ison']:$lang['ap_isoff']).'</td>
  <td align="center">
		<a href="adm.php?a=editpage&amp;id='.$xid.'">'.$lang['edit'].'</a>
		'.(($del)?' &middot; <a href="javascript:Del('.$xid.')">'.$lang['del'].'</a>':'').'
	</td>
</tr>';
}
CloseBox();
