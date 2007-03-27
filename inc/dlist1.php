<?php
if(iCMS!='E123') exit;
#Odczyt
db_read('ID,name,dsc,date,priotity','arts','art','ta',' WHERE '.((defined('SEARCH'))?SEARCH:'access=1 && cat='.$dinfo['ID'].' ORDER BY priotity, '.$hlsort).' LIMIT '.$st.','.$cfg['np']);
$ile=count($art);
if($ile>0)
{
 cTable($dinfo['name'],2);
 #Lista
 for($i=0;$i<$ile;$i++)
 {
  $xart=&$art[$i];
  echo '
  <tr>
   <td align="center" style="padding: 3px; width: 20px"><a href="javascript:nw(\'art\','.$xart['ID'].')"><img border="0" src="'.(($xart['priotity']==1)?HARTIMG:ARTIMG).'" alt="ART" title="'.$lang['opennw'].'" /></td>
   <td><b>'.(($cfg['num']==1)?($i+1+$st).'. ':'').'<a class="listlink" href="?co=art&amp;id='.$xart['ID'].'">'.$xart['name'].'</a></b> ('.genDate($xart['date']).')'.((!empty($xart['dsc']))?'<div class="txtm">'.$xart['dsc'].'</div>':'').'</td>
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
