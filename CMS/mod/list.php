<?php /* Lista pozycji */
if(iCMS!=1 OR LEVEL<2) return;
require LANG_DIR.'content.php';
require './lib/categories.php';

#Akcja
$act = isset($_GET['act']) ? (int)$_GET['act'] : 5;

#Typ
switch($act)
{
	case 5:
		$type = $lang['news'];
		$table = 'news';
		$name = 'news';
		break;
	case 4:
		$type = $lang['links'];
		$name = 'link';
		$table = 'arts';
		break;
	case 3:
		$type = $lang['images'];
		$name = 'img';
		$table = 'imgs';
		break;
	case 2:
		$type = $lang['files'];
		$name = 'file';
		$table = 'files';
		break;
	case 1:
		$type = $lang['arts'];
		$name = 'art';
		$table = 'arts';
		break;
	default:
		if(!$data = parse_ini_file('./cfg/types.ini',1) OR !isset($data[$act])) return;
		$type = $data[$act][$nlang];
		$name = $data[$act]['name'];
		$table = $data[$act]['table'];
		unset($data);
}

#Masowe zmiany
if(isset($_POST['x']) && count($_POST['x'])>0)
{
	$q = Admit('GLOBAL') ? '' : ' AND cat IN (SELECT CatID FROM '.PRE.'acl WHERE UID='.UID;
	$ids = array();

	foreach($_POST['x'] as $x=>$n) $ids[] = (int)$x;
	$ids = join(',', $ids);

	if(isset($_POST['del']))
	{
		$db->exec('DELETE FROM '.PRE.$table.' WHERE ID IN ('.$ids.')'.$q);
	}
	else
	{
		$ch = array();
		if($_POST['cat'] != 'N') $ch[] = 'cat='.(int)$_POST['cat'];
		if($_POST['pub'] != 'N') $ch[] = 'access='.(int)$_POST['pub'];

		if($ch = join(',', $ch))
		$db->exec('UPDATE '.PRE.$table.' SET '.$ch.' WHERE ID IN ('.$ids.')'.$q);
	}
	CountItems();
	unset($q,$ids,$ch,$x);
}

#Parametry - ID kategorii lub typ
if($id)
{
	$param = array('cat='.$id);
}
else
{
	$param = array();
}

#Prawa
if(Admit('GLOBAL'))
{
	$join = '';
}
else
{
	$join = ' c LEFT JOIN '.PRE.'acl a ON c.cat=a.CatID';
	$param[] = 'a.UID='.UID;
}

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
{
	$page = $_GET['page'];
	$st = ($page-1)*30;
}
else
{
	$page = 1;
	$st = 0;
}

#Szukaj
$find = isset($_GET['find']) ? Clean($_GET['find'],30) : '';
if($find) $param[] = 'name LIKE '.$db->quote($find.'%');

#Parametry -> string
$param = $join . ($param ? ' WHERE '.join(' AND ',$param) : '');

#Ilo¶æ wszystkich
$total = db_count($table.$param);

#Brak?
if($total == 0)
{
	$content->info($lang['noc'], array('?co=edit&amp;act='.$act => $lang['add'.$act]));
	return;
}

#Czê¶æ URL
$url = '?co=list&amp;act='.$act.'&amp;id='.$id;

#Pobierz pozycje
$res = $db->query('SELECT ID,name,access FROM '.PRE.$table.$param.
	' ORDER BY ID DESC LIMIT '.$st.',25');

$res -> setFetchMode(3);
$items = array(); 

#Lista
foreach($res as $i)
{
	switch($i[2])
	{
		case 1: $a = $lang['yes']; break;
		case 2: $a = $lang['no']; break;
		default: $a = $i[2];
	}

	$items[] = array(
		'num'  => ++$st,
		'title'=> $i[1],
		'id'   => $i[0],
		'on'   => $a,
		'url'  => '?co='.$name.'&amp;id='.$i[0],
		'edit_url' => '?co=edit&amp;act='.$act.'&amp;id='.$i[0]
	);
}

#Do szablonu
$content->title = $type;
$content->data = array(
	'item'  => $items,
	'act'   => $act,
	'url'   => $url,
	'intro' => $lang['i'.$act],
	'type'  => $type,
	'cats'  => Slaves($act),
	'pages' => Pages($page,$total,25,$url.'&amp;find='.$find,1),
	'add_url' => '?co=edit&amp;act='.$act,
	'cats_url'=> Admit('C') ? 'adm.php?a=cats&amp;co='.$act : '',
);