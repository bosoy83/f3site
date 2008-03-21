<?php
if(iCMSa!=1 || !Admit('UG')) exit;
require(LANG_DIR.'adm_o.php'); //Jêzyk

#Mo¿e usuwaæ?
$del=Admit('DEL')?1:0;

?>
<script type="text/javascript">
<!--
function Del(id)
{
	if(confirm("<?= $lang['ap_delc'] ?>"))
	{
		del=new Request("adm.php?x=del&id="+id,'i'+id)
		del.method='POST'
		del.add('co','group')
		del.run()
	}
}
-->
</script>
<?php

#Info
Info($lang['ugrw'].'<br /><br /><center><a href="?a=editgroup">'.$lang['gradd'].'</a></center>');

#Odczyt
$res=$db->query('SELECT ID,name,opened FROM '.PRE.'groups');
$res->setFetchMode(3); //NUM

#Lista
OpenBox($lang['ugr'],4);
echo '
<tr>
	<th>'.$lang['name'].'</th>
	<th>ID</th>
	<th>'.$lang['opened'].'?</th>
	<th>'.$lang['opt'].'</th>
</tr>';

$ile=0;
foreach($res as $group)
{
	echo '<tr>
  <td id="i'.$group[0].'">'.++$ile.'. <a href="?a=users&amp;gid='.$group[0].'">'.$group[1].'</a></td>
  <td align="center">'.$group[0].'</td>
  <td align="center">'.(($group[2]==1)?$lang['yes']:$lang['no']).'</td>
  <td align="center">
		<a href="?a=editgroup&amp;id='.$group[0].'">'.$lang['edit'].'</a>'.
		(($del)?' &middot; <a href="javascript:Del('.$group[0].')">'.$lang['del'].'</a>':'').'
	</td>
 </tr>';
}

$res=null;
CloseBox();
?>
