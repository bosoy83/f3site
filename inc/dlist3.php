<?php
if(iCMS!='E123') exit;
#Odczyt
db_read('ID,name,date,filem','imgs','img','ta',' WHERE '.((defined('SEARCH'))?SEARCH:'access=1 && cat='.$dinfo['ID'].' ORDER BY priotity, '.$hlsort).' LIMIT '.$st.','.$cfg['inp']);
$ile=count($img);
if($ile>0)
{
 cTable($dinfo['name'],1);
 echo '<tr><td><table style="width: 100%" cellpadding="5"><tbody align="center">';
 $iy=0;
 #Lista
 for($i=0;$i<$ile;$i++)
 {
  $ii=$i+1;
  $ximg=&$img[$i];
  #<tr>
  if($iy==0)
  {
   echo '<tr>';
  }
  #Obrazek
  echo '
  <td align="center">
   <a href="'.(($cfg['img_nw']==1)?'javascript:nw(\'img\','.$ximg['ID'].')':'?co=img&amp;id='.$ximg['ID']).'"><img src="'.$ximg['filem'].'" style="border: 0; padding: 1px" alt="[click]" /></a>
   <br />
   <b>'.$ximg['name'].'</b>
   <br />
   ('.genDate($ximg['date']).')
  </td>';
  #</tr>
  $iy++;
  if($iy==$cfg['imgsrow'] || $ii==$ile)
  {
   $ximgs.='</tr>';
   $iy=0;
  }
 }
 echo '</tbody></table></td></tr>';
 if($dinfo['num']>$ile)
 {
  echo '<tr><td align="center" colspan="2">'.Pages($page,$dinfo['num'],$cfg['inp'],((defined('SEARCH'))?SCO:'?d='.$d),2).'</td></tr>';
 }
 unset($ile,$img,$ximg,$iy,$xhsort,$ii);
 eTable();
}
else
{
 Info($lang['noc']);
}
?>
