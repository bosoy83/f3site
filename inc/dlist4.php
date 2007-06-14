<?php
if(iCMS!=1) exit;
#Odczyt z SQL'a
if(!defined('SEARCH'))
{
 $link=array();
 $ile=db_read('ID,name,dsc,adr,priority,count,nw','links','link','ta',' WHERE access=1 && cat='.$dinfo['ID'].' ORDER BY priority, '.$hlsort.' LIMIT '.$st.','.$cfg['np']);
}

if($ile>0)
{
 cTable($dinfo['name'],2);
 
 #Lista
 for($i=0,$ii=1+$st;$i<$ile;++$i,++$ii)
 {
  $xlink=&$link[$i];
  echo '
  <tr>
  <td align="center" style="padding: 3px; width: 1px">
<img src="'.(($xlink['priority']==1)?HLINKIMG:LINKIMG).'" alt="LINK" />
	</td>
  <td>
<b>'.(($cfg['num']==1)?$ii.'. ':'').'<a class="listlink" href="'.(($cfg['lcnt']==1)?'link.php?id='.$xlink['ID']:$xlink['adr']).'"'.(($xlink['nw']==1)?'':' target="_blank"').'>'.$xlink['name'].'</a></b>'.(($cfg['lcnt']==1)?' ('.$lang['disps'].': '.$xlink['count'].')':'').'
<div class="txtm">'.$xlink['dsc'].'</div>
  </td>
 </tr>';
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
