<?php
if(iCMS!='E123') exit;
echo '
<div align="center" class="pollq">'.$poll['q'].'</div>
<table align="center" cellspacing="0" cellpadding="0" style="padding: 3px; width: '.(($_mpoll==1)?'100%':'300px').'">
<tbody>
';
#Generowanie
for($i=0;$i<$ile;$i++)
{
 $ii=$i+1;
 echo '
 <tr>
  <td colspan="2">'.(($cfg['num']==1)?$ii.'. ':'').$answ[$i][1].'</td>
 </tr>
 <tr>
  <td style="width: 75%"><div style="height: 10px; width: '.$pollproc[$i].'%" class="pollstrip">&nbsp;</div></td>
  <td>&nbsp;'.$pollproc[$i].'% '.(($_mpoll==1)?'':' ('.$answ[$i][2].')').'</td>
 </tr>
 ';
}
echo '
</tbody>
</table>
'.(($_mpoll==1)?'':'<div align="center" style="padding: 5px">'.$lang['votes'].': <b>'.$poll['num'].'</b> | Start: <b>'.genDate($poll['date']).'</b> | <a href="?co=parch">'.$lang['arch'].'</a></div>').'
<div align="center" class="pollb">
 '.(($_mpoll==1)?'<input type="button" value="'.$lang['more'].'" style="width: 65px" onclick="location=\'?co=poll&amp;id='.$poll['ID'].'\'" />
 <input type="button" value="'.$lang['arch'].'" onclick="location=\'?co=parch\'" style="width: 70px" />':'').'
</div>';
?>
