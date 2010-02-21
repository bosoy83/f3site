<?php
if(iCMS!=1) exit;
echo '<center>'.$poll['q'].'</center>';
?>
<table align="center" cellspacing="0" cellpadding="0" style="padding: 3px 1px; width: 100%">
<tbody>
<?php
#Generowanie
foreach($item as &$o)
{
	echo '<tr>
  <td>'.$o['label'].'</td>
	<td>&nbsp;<b>'.$o['num'].'</b></td>
</tr>
<tr>
  <td><div style="width: '.$o['percent'].'%" class="pollstrip"></div></td>
  <td style="width: 20px">&nbsp;'.$o['percent'].'%</td>
</tr>';
}
?>
</tbody>
</table>
<div align="center" style="padding: 2px">
	<a href="<?=url('poll/'.$poll['ID'])?>"><input type="button" value="<?=$lang['results']?>" onclick="location=this.parentNode.href; return false" /></a>
	<a href="<?=url('polls')?>"><input type="button" value="<?=$lang['archive']?>" onclick="location=this.parentNode.href; return false" /></a>
</div>