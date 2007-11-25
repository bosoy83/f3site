<?php
if(iCMS!=1) exit;
CatPath(0,$lang['arts']);
CatStart();

#Odczyt
$res=$db->query('SELECT ID,name,dsc,date FROM '.PRE.'arts WHERE cat='.$d.' &&
	access=1 ORDER BY priority, '.CatSort($cat['sort']).' LIMIT '.$st.','.$cfg['np']);

OpenBox($cat['name'],2);
$res->setFetchMode(3);
$ile=0;
$lp=0+$st;

#Lista
foreach($res as $art)
{
	echo '<tr>
	<td align="center" style="width: 20px"><img src="'.ARTIMG.'" alt="ART" /></td>
	<td>
		<b>'.++$lp.'. <a class="listlink" href="?co=art&amp;id='.$art[0].'">'.$art[1].'</a></b> ('.genDate($art[3]).')<br />
		<small>'.$art[2].'</small>
	</td>
	</tr>';
	++$ile;
}

#Brak?
if($ile==0)
{
	echo '<tr><td colspan="2" align="center">'.$lang['noc'].'</td></tr>';
}

#Strony
elseif($cat['num']>$ile)
{
	echo '
  <tr>
	<td align="center" colspan="2">'.Pages($page,$cat['num'],$cfg['np'],'?d='.$d,2).'</td>
	</tr>';
}
CloseBox();

$res=null;
unset($ile,$lp,$art);
?>
