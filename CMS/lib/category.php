<?php /* Instrumentacja kategorii i tre¶ci */

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
if(isset($_GET['page']) && $_GET['page']>1)
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
if($cat['opt'] & 16)
{
	$cats = 'cat IN (SELECT ID FROM '.PRE.'cats WHERE lft BETWEEN '.$cat['lft'].' AND '.$cat['rgt'].')';
	$cat['num'] = $cat['nums'];
}
else
{
	$cats = 'cat='.$d;
}

#Podkategorie
if($cat['opt'] & 8)
{
	$res = $db->query('SELECT ID,name,nums FROM '.PRE.'cats WHERE sc='.$cat['ID'].
		' AND (access=1 OR access="'.$nlang.'") ORDER BY name');

	$res->setFetchMode(3); //NUM
	
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
$content->file = array('cat');
$content->data = array(
	'cat'  => &$cat,
	'subcats' => isset($sc) ? $sc : null,
	'options' => Admit($d,'CAT'),
	'cats_url'=> '?co=cats&id='.$cat['type'],
	'add_url' => '?co=edit&act='.$cat['type'].'&catid='.$d,
	'list_url'=> '?co=list&act='.$cat['type'].'&id='.$d
);

#Struktura kategorii
if($cat['opt'] & 1 && isset($cfg['catStr']))
{
	$content->data['path'] = CatPath($d,$cat);
}
else
{
	$content->data['path'] = null;
}

#Do³±cz generator listy pozycji
if($cat['num'])
{
	include './mod/cat/'.$cat['type'].'.php';
}
else
{
	$content->data['cat_type'] = $lang['cats'];
}