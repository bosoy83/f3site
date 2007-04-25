<?php
if(iCMS!='E123') exit;
echo '
<center>'.$poll['q'].'</center>
<table align="center" cellspacing="0" cellpadding="0" style="padding: 3px; width: 300px">
<tbody>';

#Generowanie
for($i=0;$i<$ile;$i++)
{
 echo '
 <tr>
  <td>'.$answ[$i][1].'</td>
	<td><b>'.$answ[$i][2].'</b></td>
 </tr>
 <tr>
  <td><div style="height: 10px; width: '.$pollproc[$i].'%" class="pollstrip">&nbsp;</div></td>
  <td style="width: 20px">'.$pollproc[$i].'%</td>
 </tr>
 ';
}
echo '
</tbody>
</table>
<div align="center" style="padding: 5px">'.$lang['votes'].': <b>'.$poll['num'].'</b> | Start: <b>'.genDate($poll['date']).'</b> | <a href="?co=parch">'.$lang['arch'].'</a></div>';
?>
