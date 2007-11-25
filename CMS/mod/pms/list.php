<?php
if(iCMS!=1) exit;

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
{
	$page=$_GET['page'];
	$st=($page-1)*30;
}
else
{
	$page=1;
	$st=0;
}

#Warunek
switch($id)
{
	case 1:
		$_q='WHERE p.st=4 AND p.owner='.UID; #Wys³ane
		$_txt=&$lang['pm_6'];
		break;
	case 2:
		$_q='WHERE p.st=1 AND p.usr='.UID; #Do wys³ania
		$_txt=&$lang['pm_8'];
		Info($lang['pm_3i']);
		break;
	case 3:
		$_q='WHERE p.st=3 AND p.owner='.UID; #Zapisane
		$_txt=&$lang['pm_7'];
		break;
	default:
		$id=4;
		$_q='WHERE (p.st=1 OR p.st=2) AND p.owner='.UID; #Odebrane
		$_txt=&$lang['pm_5']; 
}

#Licz
$ilepm=db_count('p.ID','pms p',' '.$_q);

if($ilepm>0)
{
	#Pobierz
	$res=$db->query('SELECT p.ID,p.topic,p.usr,p.owner,'.(($id==4)?'p.st,':'')
		.'u.ID as uid, u.login FROM '.PRE.'pms p LEFT JOIN '.PRE.'users u ON p.'
		.(($id==2)?'owner':'usr').'=u.ID '.$_q.' ORDER BY ID DESC LIMIT '.$st.',20');
 
	$res->setFetchMode(2);
	echo '<form action="?co=pms&amp;act=m&amp;id='.$id.'" method="post">';
	
	OpenBox($_txt.' ('.$ilepm.')',3);
	unset($_txt,$_q);
	
	echo '
<tr>
	<th>'.$lang['title'].'</th>
	<th>'.(($id==4 || $id==3)?$lang['pm_12']:$lang['pm_13']).'</th>
	<th style="width: 7%"><input type="checkbox" onclick="for(i=0;x=document.getElementsByTagName(\'input\')[i];i++) { if(x.name.indexOf(\'pmdel\')==0) { if(this.checked) { x.checked=true } else { x.checked=false } } }" /></th>
</tr>';

	$i=0;

	#Lista
	foreach($res as $pm)
	{
		echo '
<tr>
	<td>'.++$i.'. <a style="font-weight: '.(($id==4 && $pm['st']==1)?'bold':'normal').
		'" href="?co=pms&amp;act=v&amp;id='.$pm['ID'].'">'.$pm['topic'].'</a></td>
	<td align="center"><a href="?co=user&amp;id='.$pm['uid'].'">'.$pm['login'].'</a></td>
	<td align="center"><input type="checkbox" name="pmdel['.$pm['ID'].']" /></td>
</tr>';
	}

	$res=null; //Usuñ $res

	echo '
<tr class="eth">
	<td>
		<input type="button" onclick="javascript: if(confirm(\''.$lang['pm_26'].'\')) this.form.submit()" value="'.$lang['delch'].'" />'.
		(($id==4)?'<input type="submit" name="pmsav" value="'.$lang['pm_25'].'" />':'').'
	</td>
	<td colspan="2">
		'.$lang['page'].' '.Pages($page,$ilepm,20,'?co=pms&amp;act=l&amp;id='.$id,1).'
	</td>
</tr>';

	CloseBox();
	echo '</form>';
}
else
{
	Info('<center>'.$_txt.'<br /><br />'.$lang['pm_11'].'</center>');
}
?>
