<?php /* Lista pozycji */
if(iCMS!=1 OR LEVEL<2) return;
require LANG_DIR.'content.php';
require './lib/categories.php';

#Akcja i ID kategorii
$act = isset($URL[1]) ? (int)$URL[1] : 5;
$id  = isset($URL[2]) ? (int)$URL[2] : 0;

#Typ
switch($act)
{
	case 5:
		$type = $lang['news'];
		$table = 'news';
		$table2 = 'newstxt';
		$href = url('news/');
		break;
	case 4:
		$type = $lang['links'];
		$href = url('link/');
		$table = 'links';
		$table2 = false;
		break;
	case 3:
		$type = $lang['images'];
		$href = url('img/');
		$table = 'imgs';
		$table2 = false;
		break;
	case 2:
		$type = $lang['files'];
		$href = url('file/');
		$table = 'files';
		$table2 = 'false';
		break;
	case 1:
		$type = $lang['arts'];
		$href = url('art/');
		$table = 'arts';
		$table2 = 'artstxt';
		break;
	default:
		if(!$data = parse_ini_file('./cfg/types.ini',1) OR !isset($data[$act])) return;
		$type = $data[$act][LANG];
		$table = $data[$act]['table'];
		$table2 = isset($data[$act]['table2']) ? $data[$act]['table2'] : false;
		$href = isset($data[$act]['name']) ? url($data[$act]['name'].'/') : '';
		unset($data);
}

#Tytu³
$content->title = $type;

#Masowe zmiany
if(isset($_POST['x']) && count($_POST['x'])>0)
{
	try
	{
		$q = admit('+') ? '' : ' AND cat IN (SELECT CatID FROM '.PRE.'acl WHERE type="CAT" AND UID='.UID.')';
		$ids = array();
		$db->beginTransaction();

		foreach($_POST['x'] as $x=>$n) $ids[] = (int)$x;
		$ids = join(',', $ids);

		if(isset($_POST['del']))
		{
			$db->exec('DELETE FROM '.PRE.$table.' WHERE ID IN ('.$ids.')'.$q);
			if($table2) $db->exec('DELETE FROM '.PRE.$table2.' WHERE ID IN ('.$ids.')'.$q);

			#Usuñ stare komentarze - TRIGGER
			$db->exec('DELETE FROM '.PRE.'comms WHERE TYPE='.$act.' AND CID NOT IN (
				SELECT ID FROM '.PRE.$table.')');
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
		Latest();
		$db->commit();
	}
	catch(PDOException $e)
	{
		$content->info($e->getMessage());
	}
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
if(admit('+'))
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
$find = isset($_GET['find']) ? clean($_GET['find'],30) : '';
if($find) $param[] = 'name LIKE '.$db->quote($find.'%');

#Parametry -> string
$param = $join . ($param ? ' WHERE '.join(' AND ',$param) : '');

#Ilo¶æ wszystkich
$total = dbCount($table.$param);

#Brak?
if($total == 0)
{
	header('Location: '.URL.url('edit/'.$act, $id ? 'catid='.$id : null));
	$content->info($lang['noc']);
	return 1;
}

#Czê¶æ URL
$url = url('list/'.$act.'/'.$id);

#Pobierz pozycje
$res = $db->query('SELECT ID,name,access FROM '.PRE.$table.$param.
	' ORDER BY ID DESC LIMIT '.$st.',30');

$res -> setFetchMode(3);
$items = array(); 

#Lista
foreach($res as $i)
{
	switch($i[2])
	{
		case 1: $a = $lang['yes']; break;
		default: $a = $lang['no'];
	}

	$items[] = array(
		'num'  => ++$st,
		'title'=> $i[1],
		'id'   => $i[0],
		'on'   => $a,
		'url'  => $href.$i[0],
		'editURL' => url('edit/'.$act.'/'.$i[0])
	);
}

#Do szablonu
$content->data = array(
	'item'  => $items,
	'act'   => $act,
	'url'   => $url,
	'intro' => $lang['i'.$act],
	'type'  => $type,
	'cats'  => Slaves($act),
	'pages' => pages($page,$total,30,$url.'&amp;find='.$find,1),
	'addURL' => url('edit/'.$act.($id ? '?catid='.$id : '')),
	'catsURL'=> admit('C') ? url('cats', 'co='.$act, 'admin') : false,
);