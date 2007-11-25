<?php /* Instrumentacja kategorii i treœci */

/*Struktura kategorii
function CatPath($id=0,$type,$code='')
{
	global $cat,$nlang;
	if($GLOBALS['cfg']['cstr']!=1 || strpos($cat['opt'],'S')!==false) return false;
	$out='<div class="cs">';

	#Nadkategorie
	if($cat['sc']!=0)
	{
		$res=$GLOBALS['db']->query('SELECT ID,name FROM '.PRE.'cats WHERE lft<'.$cat['lft'].
		' && rgt>'.$cat['rgt'].' && (access=1 || access="'.$nlang.'") ORDER BY lft DESC');

		foreach($res as $c)
			$out.=' &raquo; <a href="?d='.$c['ID'].'">'.$c['name'].'</a>';
	}

	echo $out.'</div>';
}
*/



#Powitanie i podkategorie
function CatStart() 
{
	global $cat;
	if(strpos($cat['opt'],'A')!==false) return; //Wy³.?

	#Pobierz Podkategorie
	$res=$GLOBALS['db']->query('SELECT ID,name,nums FROM '.PRE.'cats WHERE
		sc='.$cat['ID'].' AND (access=1 OR access="'.$GLOBALS['nlang'].'") ORDER BY name');
	$i=0;
	$out=array('','');
	foreach($res as $c)
	{
		$out[$i].='<li><a href="?d='.$c['ID'].'">'.$c['name'].'</a> ('.$c['nums'].')</li>';
		if($i==1) $i=0; else ++$i;
	}

	#Powitanie
	if($cat['text'] || $out[0])
	{
		OpenBox($cat['name'],1);
		echo '<tr><td class="txt">'.nl2br($cat['text']).(($out[0])?
			'<div><ul style="float: left; background-image: url(img/icon/folder.png)" class="go">'.$out[0].
			'</ul><ul style="float: right; background-image: url(img/icon/folder.png); width: 40%" class="go">'.$out[1].'</ul></div>':'').
			'</td></tr>';
		CloseBox();
	}
}

#Sortowanie
function CatSort($sort)
{
	if(isset($_GET['sort'])) $sort=(int)$_GET['sort'];
	switch($sort)
	{
		case 1: return 'ID'; break;
		case 3: return 'name'; break;
		default: return 'ID DESC';
	}
}

#Kategoria?
if(isset($_GET['d']))
{
	$d=(int)$_GET['d'];
}
#Domyœlna
elseif($cfg['start'][$nlang])
{
	$d=(int)$cfg['start'][$nlang];
}
else
{
	$d=0;
}

#Odczyt
if($d)
{
	#Pobierz
	$res=$db->query('SELECT * FROM '.PRE.'cats WHERE ID='.$d.' AND access!=3');
	$cat=$res->Fetch(2); //ASSOC
	$res=null;

	#Tytu³
	if($cat)
	{
		$title=$cat['name'];
		define('MOD','./mod/cat/'.$cat['type'].'.php');
	}
	else define('MOD','./404.php');

	#Strona
	if(isset($_GET['page']) && $_GET['page']!=1)
	{
		$page=$_GET['page'];
		$st=($page-1)*(($cat['type']==3)?$cfg['inp']:$cfg['np']);
	}
	else
	{
		$page=1;
		$st=0;
	}
}
#404
else
{
	define('MOD','./404.php');
}
?>
