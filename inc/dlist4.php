<?php
if(iCMS!='E123') { exit; }
#Odczyt z SQL'a
if(!defined('SEARCH'))
{
 db_read('ID,name,dsc,adr,priotity,count,nw','links','link','ta',' WHERE access=1 && cat='.$dinfo['ID'].' ORDER BY priotity, '.$hlsort.' LIMIT '.$st.','.$cfg['np']);
 $ile=count($link);
}
if($ile>0)
{
 cTable($dinfo['name'],2);
 #Lista
 for($i=0;$i<$ile;$i++)
 {
  $xlink=&$link[$i];
  $ii=$i+1+$st;
  echo '
  <tr>
  <td align="center" style="padding: 3px; width: 1px"><img src="'.(($xlink['priotity']==1)?HLINKIMG:LINKIMG).'" alt="LINK" /></td>
  <td style="padding: 3px">
   <b>'.(($cfg['num']==1)?$ii.'. ':'').'<a class="listlink" href="'.(($cfg['lcnt']==1)?'?mode=link&id='.$xlink['ID']:$xlink['adr']).'"'.(($xlink['nw']==1)?'':' target="_blank"').'>'.$xlink['name'].'</a></b>'.(($cfg['lcnt']==1)?' ('.strtolower($lang['disps']).': '.$xlink['count'].')':'').'
   <br />
   <span class="txtm">'.$xlink['dsc'].'</span>
  </td>
 </tr>
  ';
 }
 #Strony
 if($dinfo['num']>$ile)
 {
  echo '<tr><td colspan="2" align="center">'.Pages($page,$dinfo['num'],$cfg['np'],'index.php?d='.$d,2).'</td></tr>';
 }
 eTable();
 unset($ile,$link,$ii,$hlsort,$xlink);
}
else
{
 Info($lang['noc']);
}
?>
