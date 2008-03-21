<?php /* Instrumentacja kategorii i tre¶ci */

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

/* Klasa - dobry pomys³, ale spróbujmy proceduralnie
class Cat
{
	public
		$name,
		$dsc,
		$type,
		$sc,
		$sort,
		$text,
		$num,
		$opt,
		$lft,
		$rgt,
		$slaves='';

	function __construct($id)
	{
		#Podkategorie
		if(!$cat['opt']&8)
		{
			$GLOBALS['db']->query('SELECT ID,name,nums FROM '.PRE.'cats WHERE
				sc='.$cat['ID'].' AND (access=1 OR access="'.$GLOBALS['nlang'].'") ORDER BY name');
		}
	}
} */

/* Powitanie i podkategorie - prawdopodobnie funkcja zbêdna, gdy u¿ywamy szablonów
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
}*/

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
	$d = (int)$_GET['d'];
}
#Domy¶lna
elseif($cfg['start'][$nlang])
{
	$d = (int)$cfg['start'][$nlang];
}
#Brak
else
{
	$d = 1;
}

#Pobierz
$cat = $db->query('SELECT * FROM '.PRE.'cats WHERE ID='.$d.' AND access!=3') -> fetch(2); //2 = ASSOC

#Brak?
if(!$cat) return;

#Tytu³ strony
$content->title = $cat['name'];

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
{
	$page = $_GET['page'];
	$st = ($page-1) * (($cat['type']==3) ? $cfg['inp'] : $cfg['np']);
}
else
{
	$page = 1;
	$st = 0;
}

#Opcja: lista pozycji z wszystkich podkategorii
#TODO: SELECT * FROM items JOIN cats ON items.cat_id = cats.id
if($cat['opt'] & 8)
{
	$cats = 'cat IN (SELECT ID FROM '.PRE.'cats WHERE lft BETWEEN '.$cat['lft'].' AND '.$cat['rgt'].')';
	$cat['num'] = $cat['nums'];
}
else
{
	$cats = 'cat='.$d;
}

#Podkategorie
if($cat['opt'] & 16 === false)
{
	$res = $db->query('SELECT ID,name,nums FROM '.PRE.'cats WHERE sc='.$cat['ID'].
		' AND (access=1 OR access="'.$nlang.'") ORDER BY name');

	$res ->setFetchMode(3); //NUM
	
	foreach($res as $c)
	{
		$sc[] = array(
			'url'  => '?d='.$c[0],
			'name' => $c[1],
			'num'  => $c[2]
		);
	}
}

#Do szablonu
$content->data = array(
	'cat'  => &$cat,
	'path' => file_get_contents('./cache/cat'.$d.'.php'),
	'subcats' => isset($sc) ? $sc : null,
	'options' => Admit($d,'CAT'),
	'cats_url'=> '?co=cats&id='.$cat['type'],
	'add_url' => '?co=edit&act=t'.$cat['type'].'&catid='.$d,
	'list_url'=> '?co=edit&act='.$cat['type'].'&id='.$d
);

#Do³±cz generator listy pozycji
if($cat['num'])
{
	include './mod/cat/'.$cat['type'].'.php';
}
else
{
	//$content->info($lang['noc']);
	$content->data['cat_type'] = $lang['cats'];
	$content->file = 'cat';
}
?>
