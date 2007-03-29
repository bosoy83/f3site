<?php
if(iCMS!='E123') exit;
$cat=array();
$sect='';
db_read('c.ID,c.name,c.dsc,c.report,c.num,c.last,s.title','bugcats c LEFT JOIN {pre}bugsect s ON c.sect=s.ID','cat','ta',' WHERE c.see=1 OR c.see="'.$nlang.'" ORDER BY s.seq,c.name');
$ile=count($cat);

if($cfg['bugs_i']!='') Info($cfg['bugs_i']);

if($ile>0)
{
 cTable($lang['cats'],2);
 for($i=0;$i<$ile;$i++)
 {
  #Sekcja
	if($cat[$i]['title']!=$sect)
	{
	 $sect=$cat[$i]['title'];
	 echo '<tr><th colspan="2">'.$sect.'</th></tr>';
	}
  #Ikona
  $class='cat';
	if(BugIsNew('',$cat[$i]['last'])) $class.='new';
	#Wyœwietl
  echo '
  <tr>
   <td class="'.$class.'"></td>
   <td><a class="listlink" href="?co=bugs&amp;act=l&amp;id='.$cat[$i]['ID'].'">'.$cat[$i]['name'].'</a> ('.$cat[$i]['num'].')<br /><small>'.$cat[$i]['dsc'].'</small></td>
  </tr>';
 }
 eTable();
}
else
{
 Info($lang['nocats']);
}
?>