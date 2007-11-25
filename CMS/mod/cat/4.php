<?php
if(iCMS!=1) exit;
CatPath(0,$lang['links']);
CatStart();

#Odczyt
$res=$db->query('SELECT ID,name,dsc,adr,count,nw FROM '.PRE.'links WHERE cat='.$d.
	' && access=1 ORDER BY priority, '.CatSort($cat['sort']).' LIMIT '.$st.','.$cfg['np']);

OpenBox($cat['name'],2);
$res->setFetchMode(3);
$lp=$st;

#Lista
foreach($res as $link)
{
	echo '<tr>
  <td align="center" style="width: 1px">
		<img src="'.LINKIMG.'" alt="LINK" />
	</td>
	<td>
		<b>'.++$lp.'. <a class="listlink" href="'.(($cfg['lcnt']==1)?'link.php?id='.$link[0]:$link[3]).'"'.(($link[5]==1)?'':' target="_blank"').'>'.$link[1].'</a></b>'.(($cfg['lcnt']==1)?' ('.$lang['disps'].': '.$link[4].')':'').'<br />
		<small>'.$link[2].'</small>
  </td>
</tr>';
}

#Brak?
if($lp==$st)
	echo '<tr><td colspan="2" align="center">'.$lang['noc'].'</td></tr>';

#Strony
elseif($cat['num']>$ile)
{
	echo '<tr><td colspan="2" align="center">'.Pages($page,$cat['num'],$cfg['np'],'index.php?d='.$d,2).'</td></tr>';
}
CloseBox();

$res=null;
unset($ile,$link,$lp);
?>
