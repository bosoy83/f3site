<?php
if(iCMS!=1) exit;
require($catl.'files.php');

CatPath(0,$lang['files']);
CatStart();

#Odczyt
$res=$db->query('SELECT ID,name,date,dsc,file,size FROM '.PRE.'files WHERE cat='.$d.'
	&& access=1 ORDER BY priority, '.CatSort($cat['sort']).' LIMIT '.$st.','.$cfg['np']);

OpenBox($cat['name'],2);
$res->setFetchMode(3);
$ile=0;
$lp=$st;

#Lista
foreach($res as $file)
{
	echo
	'<tr>
	<td align="center" style="width: 20px">
		<a href="'.(($cfg['fcdl']==1)?'?mode=dl&amp;id='.$file[0]:(($file[5]=='A')?'files/':'').$file[4]).'">
			<img src="'.IMGFILE.'" alt="'.$file[5].'" title="'.$lang['dl'].'" />
		</a>
	</td>
	<td>
		<b>'.++$lp.'. <a class="listlink" href="?co=file&amp;id='.$file[0].'">'.$file[1].'</a></b> ('.genDate($file[2]).')<br /><small>'.$file[3].'</small>
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
	echo '<tr><td align="center" colspan="2">'.Pages($page,$cat['num'],$cfg['np'],((defined('SEARCH'))?SCO:'?d='.$d),2).'</td></tr>';
}
CloseBox();

$res=null;
unset($ile,$lp,$file,$hlsort);
?>
