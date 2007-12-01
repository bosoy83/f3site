<?php
if(iCMSa!=1 || !Admit('CFG')) return;
require($catl.'adm_conf.php');

#Zapis
if($_POST)
{
	$ile=count($_POST['x_1']);
	$list1=Array();
	$list2=Array();

	for($i=0;$i<$ile;++$i)
	{
		if(!isset($_POST['x_del'][$i]) && !empty($_POST['x_1'][$i]))
		{
			$list1[]=htmlspecialchars(substr($_POST['x_1'][$i],0,50));
			$list2[]=substr($_POST['x_2'][$i],0,50);
		}
	}

	#Klasa zapisu do pliku PHP
	require('./lib/config.php');
	$f=new Config('words');
	$f->add('words1',$list1);
	$f->add('words2',$list2);

	if($f->save()) Info($lang['saved']);
}

#Form
include('./cfg/words.php');
$ile=count($words1);
Info($lang['aw_i'].(($cfg['wordc']==1)?'':'<br />'.$lang['aw_f']));
?>
<script type="text/javascript">
//<![CDATA[
var ile=<?= $ile ?>;
function Dodaj()
{
	document.getElementById("itm"+ile).innerHTML='<div style="margin: 5px"><input maxlength="50" name="x_1['+ile+']" /> <?= $lang['aw_c'] ?> <input maxlength="50" name="x_2['+ile+']" /> <input type="checkbox" name="x_del['+ile+']" /> <?= $lang['del'] ?></div><div id="itm'+(ile+1)+'"></div>';
	++ile;
}
//]]>
</script>
<form action="?a=wordrep" method="post">
<?php
OpenBox($lang['aw_t'],1);
echo '<tr><td align="center">';

#Lista
for($i=0;$i<$ile;$i++)
{
	echo '<div style="margin: 5px">
	<input maxlength="50" value="'.$words1[$i].'" name="x_1['.$i.']" /> '.$lang['aw_c'].'
	<input maxlength="50" value="'.htmlspecialchars($words2[$i]).'" name="x_2['.$i.']" />
	<input type="checkbox" name="x_del['.$i.']" /> '.$lang['del'].'
</div>';
}
echo '<div id="itm'.$ile.'" align="center"></div>
	<div style="padding: 5px">
		<a href="javascript:Dodaj()"><b>'.$lang['add'].'</b></a>
	</div>
	</td>
</tr>
<tr>
	<td class="eth"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
CloseBox();
?>
</form>