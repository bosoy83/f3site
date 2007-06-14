<?php
if(iCMS!=1) exit;
#Odczyt
$art=array();
$ile=db_read('ID,name,dsc,date,priority','arts','art','ta',' WHERE '.((defined('SEARCH'))?SEARCH:'access=1 && cat='.$dinfo['ID'].' ORDER BY priority, '.$hlsort).' LIMIT '.$st.','.$cfg['np']);

if($ile>0)
{
 cTable($dinfo['name'],2);
 #Lista
 for($i=0,$ii=1+$st;$i<$ile;++$i,++$ii)
 {
  $xart=&$art[$i];
  echo '
  <tr>
   <td align="center" style="width: 20px"><a href="javascript:nw(\'art\','.$xart['ID'].')"><img border="0" src="'.(($xart['priority']==1)?HARTIMG:ARTIMG).'" alt="ART" /></td>
   <td><b>'.(($cfg['num']==1)?$ii.'. ':'').'<a class="listlink" href="?co=art&amp;id='.$xart['ID'].'">'.$xart['name'].'</a></b> ('.genDate($xart['date']).')<div class="txtm">'.$xart['dsc'].'</div></td>
  </tr>
  ';
 }
 #Strony
 if($dinfo['num']>$ile)
 {
  echo '
  <tr>
   <td align="center" colspan="2">'.Pages($page,$dinfo['num'],$cfg['np'],((defined('SEARCH'))?SCO:'?d='.$d),2).'</td>
  </tr>
  ';
 }
 eTable();
 unset($ile,$art,$xhsort,$xart);
}
else
{
 Info($lang['noc']);
}
?>
