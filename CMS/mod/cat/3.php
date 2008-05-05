<?php
if(iCMS!=1) exit;
CatPath(0,$lang['imgs']);
CatStart();

#Odczyt
$res=$db->query('SELECT ID,name,date,filem FROM '.PRE.'imgs WHERE cat='.$d.' &&
	access=1 ORDER BY priority, '.CatSort($cat['sort']).' LIMIT '.$st.','.$cfg['inp']);

OpenBox($cat['name'],1);
$ile=0;

#Lista
echo '<tr><td><table style="width: 100%" cellpadding="5"><tbody align="center">';
$if=1;

foreach($res as $img)
{
	#<tr>
	if($if==1)
	{
		echo '<tr>';
	}

	#Obrazek
	echo '<td>
		<a href="?co=img&amp;id='.$img['ID'].'"><img src="'.$img['filem'].'" alt="[click]" /></a>
		<br />
		<b>'.$img['name'].'</b>
		<br />
		('.genDate($img['date']).')
	</td>';
	
	#</tr>
  if($if==$cfg['imgsRow'] || $if==$cfg['inp'])
  {
		echo '</tr>';
		$if=1;
  }
	else { ++$if; }

	++$ile;
}

#Brak?
if($ile==0)
	echo '<tr><td align="center">'.$lang['noc'].'</td></tr>';

echo '</tbody></table></td></tr>';

#Strony
if($cat['num']>$ile)
{
	echo '<tr><td align="center" colspan="2">'.Pages($page,$cat['num'],$cfg['inp'],'?d='.$d,2).'</td></tr>';
}
CloseBox();

$res=null;
unset($ile,$img,$if);
?>
