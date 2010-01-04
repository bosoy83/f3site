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
if(isset($URL[0]))
{
	$d = (int)$URL[0];
}
elseif(isset($_GET['d']))
{
	$d = (int)$_GET['d'];
}
#Domy¶lna
elseif(isset($cfg['start'][LANG]))
{
	$d = $cfg['start'][LANG];
}
#Brak
else
{
	require './mod/cats.php'; return 1;
}

#Pobierz, 2 = ASSOC
$cat = $db->query('SELECT * FROM '.PRE.'cats WHERE ID='.$d.' AND access!=3') -> fetch(2);

#Brak?
if(!$cat) return;

#Tytu³ strony
$content->title = $cat['name'];

#Strona
if(isset($_GET['page']) && $_GET['page']>1)
{
	$page = $_GET['page'];
	$st = ($page-1) * $cfg['np'];
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

#Podkategorie, 3 = NUM
if($cat['opt'] & 8)
{
	$res = $db->query('SELECT ID,name,nums FROM '.PRE.'cats WHERE sc='.$cat['ID'].
		' AND (access=1 OR access="'.LANG.'") ORDER BY name');
	$res->setFetchMode(3);

	foreach($res as $c)
	{
		$sc[] = array(
			'url'  => url($c[0]),
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
	'options' => admit($d,'CAT'),
	'add_url' => url('edit/'.$cat['type'], 'catid='.$d),
	'list_url'=> url('list/'.$cat['type'].'/'.$d)
);

#Struktura kategorii
if($cat['opt'] & 1 && isset($cfg['catStr']))
{
	$content->data['path'] = catPath($d,$cat);
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
	$content->data['cats_url'] = url('cats');
}
