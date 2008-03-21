<?php /* Lista pozycji */
if(EC!=1) exit;
require LANG_DIR.'content.php';

#Akcja
$act=(int)$_GET['act'];

#Mo¿e usuwaæ i edytowaæ?
$del=Admit('DEL')?1:0;

#Typ
switch($act)
{
	case 5:
		$type=$lang['news'];
		$name='new';
		break;
	case 4:
		$type=$lang['links'];
		$name='link';
		break;
	case 3:
		$type=$lang['images'];
		$name='img';
		break;
	case 2:
		$type=$lang['files'];
		$name='file';
		break;
	case 1:
		$type=$lang['arts'];
		$name='art';
		break;
	default: echo 'Undefined type of items!'; return;
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

#Masowe zmiany?
/* DO NOT WORK YET
if($_POST && count($_POST['chk'])>0)
{
	$ids=GetIDs($_POST['chk']);
	if(isset($_POST['xu_d']))
	{
		if(Admit('DEL'))
		{
			$db->exec('DELETE FROM '.PRE.$xco.'s'.$join.' WHERE ID IN ('.join(',',$ids).') '.$param);
			$db->exec('DELETE FROM '.PRE.'comms WHERE type='.$co.' AND CID IN ('.join(',',$ids).')');
			if($co==1||$co==5) db_q('DELETE FROM '.PRE.''.(($co==1)?'artstxt':'fnews').' WHERE ID='.join(' || ID=',$ids));
		}
	}
	else
	{
		$_q=Array();
		if($_POST['xu_c']!='N') $_q[]='cat='.(int)$_POST['xu_c'];
		if($_POST['xu_a']!='N') $_q[]='access='.(int)$_POST['xu_a'];
		if(count($_q)>0) db_q('UPDATE '.PRE.$xco.'s SET '.join(', ',$_q).' WHERE ID IN ('.join(',',$ids).')');
	}
	unset($ids,$_q);
} */

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
if($find) $param.=' && name LIKE '.$db->quote($find.'%');

#Parametry -> string
$param = $join . ($param ? ' WHERE '.join(' && ',$param) : '');

#Iloœæ wszystkich
$total = db_count('ID',$name.'s'.$param);

#Brak?
if($total == 0)
{
	$content->info($lang['noc'], array('?co=edit&amp;act='.$name => $lang['add'.$act]));
	return;
}

#Czêœæ URL
$url='?co=edit&amp;act='.$name.'&amp;id='.$id;

#Pobierz pozycje
$res = $db->query('SELECT ID,name,access FROM '.PRE.$name.'s'.$param.
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
		'ID'   => $i[0],
		'on'   => $a,
		'url'  => '?co='.$name.'&amp;id='.$i[0],
		'edit_url' => '?co=edit&amp;act='.$name.'&amp;id='.$i[0],
		'del_url'  => 'javascript:Del('.$i[0].')'
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
	'pages' => Pages($page,$total,25,$url.'&amp;find='.$find,1),
	'add_url' => '?co=edit&amp;act='.$name,
	'cats_url'=> Admit('C') ? 'adm.php?a=cats&amp;co='.$act : '',
);

#Szablon
$content->file = 'edit_list';
?>
