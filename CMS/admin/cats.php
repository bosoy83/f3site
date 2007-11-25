<?php
if(iCMSa!=1 || !Admit('C')) exit;
require($catl.'adm_o.php');

#Przelicz ilo¶æ
if(isset($_GET['rec']))
{
	include('./lib/categories.php');
	CountItems();
}

#Mo¿e usuwaæ?
$del=Admit('DEL')?1:0;
if($del) {
?>
<script type="text/javascript">
<!--
function Del(id)
{
	if(confirm("<?=$lang['ap_delcat']?>"))
	{
		del=new Request("adm.php?x=del&id="+id,'i'+id);
		del.method='POST';
		del.add('co','cat')
		del.run()
	}
}
-->
</script>
<?php }

Info($lang['ap_dinfo'].'<br /><br /><center><a href="adm.php?a=editcat">'.$lang['ap_kaddc'].'</a> | <a href="?a=cats&amp;rec=1">'.$lang['ap_catrz'].'</a></center>');
OpenBox($lang['ap_dnagz'],5);

#Nag³ówek
echo '
<tr>
	<th>'.$lang['name'].'</th>
	<th style="width: 30px">ID</th>
	<th style="width: 50px">'.$lang['ap_disp'].'</th>
	<th>'.$lang['ap_type'].'</th>
	<th>'.$lang['opt'].'</th>
</tr>';

#Odczyt
$res=$db->query('SELECT ID,name,access,type,num,rgt FROM '.PRE.'cats'
	.((isset($_GET['co']))?' WHERE type='.(int)$_GET['co']:'').' ORDER BY lft');

#Typy i kolory
$types=Array(null,$lang['arts'],$lang['files'],$lang['imgs'],$lang['links'],$lang['news']);
$depth=0;
$last=1;

foreach($res as $cat)
{
	#Poziom
	if($last>$cat['rgt'])
	{
		++$depth;
	}
	elseif($depth>0 && $last+2!=$cat['rgt'])
	{
		$depth-=ceil(($cat['rgt']-$last)/2);
	}
	$last=$cat['rgt'];

	echo '
	<tr>
		<td id="i'.$cat['ID'].'">'.str_repeat('&raquo; &nbsp;',$depth).
			'<a href="index.php?co=edit&amp;act='.$cat['type'].'&amp;id='.$cat['ID'].'">'.$cat['name'].'</a> ('.$cat['num'].')
		</td>
		<td align="center">'.$cat['ID'].'</td>
		<td align="center">';

		#Typ
		switch($cat['access'])
		{
			case 1: echo $lang['ap_ison']; break;
			case 2: echo $lang['ap_ishid']; break;
			case 3: echo $lang['ap_isoff']; break;
			default: echo $cat['access'];
		}
		echo '</td>
		<td align="center">'.$types[$cat['type']].'</td>
		<td align="center">
			<a href="adm.php?a=editcat&amp;id='.$cat['ID'].'">'.$lang['edit'].'</a>'
			.(($del)?' &middot; <a href="javascript:Del('.$cat['ID'].')">'.$lang['del'].'</a>':'').'
		</td>
	</tr>';
}
CloseBox();
?>
