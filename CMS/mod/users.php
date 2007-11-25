<?php if(iCMS!=1) exit;
require($catl.'profile.php'); #Plik jêzyka

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

#Szukanie
$url='';
$param=Array();
if($cfg['ufind']==1)
{
	if(!empty($_GET['sl']))
	{
		$sl=Clean($_GET['sl'],20); echo $sl;
		$param[]='login LIKE "%'.$sl.'%"'; $url.='&amp;sl='.$sl;
	}
	if(!empty($_GET['pl']))
	{
		$pl=Clean($_GET['pl'],30);
		$param[]='city LIKE "%'.$pl.'%"'; $url.='&amp;pl='.$pl;
	}
	if(!empty($_GET['www']))
	{
		$www=Clean($_GET['www'],80);
		$param[]='www LIKE "%'.$www.'%"'; $url.='&amp;www='.$www;
	}
}
#ID Grupy
if(isset($_GET['id']))
{
	$id=$_GET['id']; $param[]='gid='.$id; $url.='&amp;id='.$id;
}

#Licz
$total=db_count('ID','users',(($url=='')?'':' WHERE '.join(' && ',$param)));

if($total>0)
{
	#Odczyt
	$res=$db->query('SELECT ID,login,lv,regt FROM '.PRE.'users'.(($url)?' WHERE '.join(' && ',$param):'').' ORDER BY '.((isset($_GET['sort']))?'login':'ID DESC').' LIMIT '.$st.',30');

	$res->setFetchMode(3);
	unset($param);

	OpenBox($lang['users'].' ('.$total.')',4);
	echo '
	<tr>
		<th><a href="?co=users&amp;sort=1'.$url.'">'.$lang['login'].'</a></th>
		<th><a href="?co=users'.$url.'">'.$lang['joined'].'</a></th>
		<th>'.$lang['rights'].'</th>
	</tr>';

	foreach($res as $u)
	{
		echo '<tr>
		<td>'.++$st.'. <a href="?co=user&amp;id='.$u[0].'">'.$u[1].'</a></td>
		<td align="center">'.genDate($u[3]).'</td>
		<td align="center">';
		switch($u[2]) {
			case 0: echo $lang['locker']; break;
			case 1: echo $lang['user']; break;
			case 2: echo $lang['editor']; break;
			case 3: echo $lang['admin']; break;
			case 4: echo $lang['owner']; break;
			default: echo 'ERR!';
		}
		echo '</td>
		</tr>';
	}
	$res=null;

	echo '
	<tr class="eth">
		<td style="font-weight: normal">'.(($cfg['ufind']==1)?'<a href="javascript:Show(\'sf\')">'.$lang['search'].' &raquo;</a>':'').'</td>
		<td colspan="2">'.$lang['page'].': '.Pages($page,$total,30,'?co=users'.$url.((isset($_GET['sort']))?'&amp;sort=1':''),1).'</td>
	</tr>';

	unset($u,$url);
	CloseBox();
}

#Brak
else
{
	Info($lang['nousers']);
}

#Szukaj
if($cfg['ufind']==1 && $total>0)
{
	echo '<form method="get" id="sf" action="index.php" style="display: none">';
	OpenBox($lang['search'],1);
	echo '<tr>
	<td>
	<table style="width: 100%" align="center"><tbody>
	<tr>
		<td>'.$lang['login'].$lang['cont'].':<br />
			<input name="sl" maxlength="40" value="'.((!empty($_GET['sl']))?$sl:'').'" />
		</td>
		<td>'.$lang['ufrom'].'<br />
			<input name="pl" maxlength="50" value="'.((!empty($_GET['pl']))?$pl:'').'" />
		</td>
	</tr>
	<tr>
		<td>'.$lang['wwwp'].':<br />
			<input name="l" maxlength="40" value="'.((!empty($_GET['l']))?$www:'').'" />
		</td>
		<td><br /><input type="submit" value="OK" /></td>
	</tr>
	</tbody></table></td>
	</tr>';
	CloseBox();
	echo ((isset($id))?'<input type="hidden" name="id" value="'.$id.'" />':'').'<input type="hidden" name="co" value="users" /></form>';
}
unset($total,$www,$pl,$sl);
