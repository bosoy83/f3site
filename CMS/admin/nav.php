<?php
if(iCMSa!=1 || !Admit('NM')) exit;
require(LANG_DIR.'adm_o.php');

#Mo¿e usuwaæ?
$del=Admit('DEL')?1:0;

#Zapis bloków
if($_POST)
{
	$ile=count($_POST['m_s']);
	for($i=1;$i<=$ile;++$i)
	{
		$db->exec('UPDATE '.PRE.'menu SET seq='.(int)$_POST['m_s'][$i].',
			disp='.$db->quote($_POST['m_vis'][$i]).', menu='.(int)$_POST['m_page'][$i].'
			WHERE ID='.(int)$_POST['m_id'][$i]);
	}
	#Odbuduj menu
	require('./admin/inc/mcache.php');
	RenderMenu();
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
	del.add('co','nav')
	del.run()
 }
}
-->
</script>

<form method="post" action="?a=nav">
<?php

OpenBox($lang['ap_navs'],5);
echo '<tr>
	<th>'.$lang['ap_txt'].'</th>
	<th style="width: 30px">'.$lang['ap_seq'].'</th>
	<th style="width: 100px">'.$lang['ap_mvis'].'</th>
	<th style="width: 100px">'.$lang['ap_page'].'</th>
	<th>'.$lang['opt'].'</th>
</tr>';

$res=$db->query('SELECT ID,seq,text,disp,menu,type FROM '.PRE.'menu ORDER BY menu,seq');
$res->setFetchMode(2); //Assoc

$ile=0;
foreach($res as $m)
{
 echo '<tr>
	<td id="i'.$m['ID'].'">'.++$ile.'. '.$m['text'].'</td>
	<td align="center">
		<input name="m_s['.$ile.']" onblur="if(value==\'\') value=0" size="1" value="'.$m['seq'].'" />
		<input type="hidden" name="m_id['.$ile.']" value="'.$m['ID'].'" />
	</td>
  <td align="center">
		<select name="m_vis['.$ile.']">
			<option value="1">'.$lang['ap_ison'].'</option>
			'.ListBox('lang',1,$m['disp']).'
			<option value="2"'.(($m['disp']==2)?' selected="selected"':'').'>'.$lang['ap_isoff'].'</option>
		</select>
	</td>
  <td align="center">
		&larr;<input type="radio" value="1" name="m_page['.$ile.']"'.(($m['menu']!=2)?' checked="checked"':'').' />
		&nbsp;<input type="radio" value="2" name="m_page['.$ile.']"'.(($m['menu']==2)?' checked="checked"':'').' /> &rarr;
	</td>
  <td align="center">
		<a href="adm.php?a=editnav&amp;id='.$m['ID'].'">'.$lang['edit'].'</a>
		'.(($del==1)?' &middot; <a href="javascript:Del('.$m['ID'].')">'.$lang['del'].'</a>':'').'
	</td>
 </tr>';
}
echo '<tr>
	<td colspan="5" class="eth">
		<input type="submit" value="'.$lang['save'].'" />
		<input type="button" value="'.$lang['ap_navnewm'].'" onclick="location=\'adm.php?a=editnav\'" />
	</td>
</tr>';
CloseBox();
?>
</form>
