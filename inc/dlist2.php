<?php
if(iCMS!=1) exit;
require($catl.'files.php');

#Odczyt
$ile=db_read('ID,name,date,dsc,file,size,priority','files','file','ta',' WHERE '.((defined('SEARCH'))?SEARCH:'access=1 && cat='.$dinfo['ID'].' ORDER BY priority, '.$hlsort).' LIMIT '.$st.','.$cfg['np']);

if($ile>0)
{
 cTable($dinfo['name'],2);
 #Lista
 for($i=0,$ii=1+$st;$i<$ile;++$i,++$ii)
 {
  $xfile=&$file[$i];
  echo '
  <tr>
   <td align="center" style="width: 20px"><a href="'.(($cfg['fcdl']==1)?'?mode=dl&amp;id='.$xfile['ID']:(($xfile['size']=='A')?'files/':'').$xfile['file']).'"><img src="'.(($xfile['priority']==1)?HIMGFILE:IMGFILE).'" alt="'.(($xfile['size']=='A')?'F':$xfile['size']).'" style="border: 0" /></a></td>
   <td style="padding: 3px">
    <b>'.(($cfg['num']==1)?$ii.'. ':'').'<a class="listlink" href="'.(($cfg['file_nw']==1)?'javascript:nw(\'file\','.$xfile['ID'].')':'?co=file&amp;id='.$xfile['ID']).'">'.$xfile['name'].'</a></b> ('.genDate($xfile['date']).')<br /><span class="txtm">'.$xfile['dsc'].'</span>
   </td>
  </tr>';
 }
 #Strony
 if($dinfo['num']>$ile)
 {
  echo '<tr><td align="center" colspan="2">'.Pages($page,$dinfo['num'],$cfg['np'],((defined('SEARCH'))?SCO:'?d='.$d),2).'</td></tr>';
 }
 eTable();
 unset($ile,$ii,$file,$xhsort,$xfile);
}
else {
 Info($lang['noc']);
}
?>
