<?php
if(iCMS!=1) exit;
echo '<center>'.$poll['q'].'</center>';
?>
<table align="center" cellspacing="0" cellpadding="0" style="padding: 3px 1px; width: 100%">
<tbody>

<?php
#Generowanie
for($i=0;$i<$ile;$i++)
{
 echo '
 <tr>
  <td>'.$option[$i][1].'</td>
	<td>&nbsp;<b>'.$option[$i][2].'</b></td>
 </tr>
 <tr>
  <td><div style="height: 10px; width: '.$pollproc[$i].'%" class="pollstrip"></div></td>
  <td style="width: 20px">&nbsp;'.$pollproc[$i].'%</td>
 </tr>';
}
?>
</tbody>
</table>
<div align="center" style="padding: 2px">
<input type="button" value="<?=$lang['results']?>" onclick="location='?co=poll&amp;id=<?=$poll['ID']?>'" />
 <input type="button" value="<?=$lang['arch']?>" onclick="location='?co=parch'" />
 
</div>
