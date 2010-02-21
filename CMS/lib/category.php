<?php

#Sorting
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

#Config
include './cfg/content.php';

#Get category ID from URL or load default
if(isset($URL[0]))
{
	$d = (int)$URL[0];
}
elseif(isset($_GET['d']))
{
	$d = (int)$_GET['d'];
}
elseif(isset($cfg['start'][LANG]))
{
	$d = $cfg['start'][LANG];
}
else
{
	require './mod/cats.php'; return 1;
}

#Load category to ASSOC $cat
if(!$cat = $db->query('SELECT * FROM '.PRE.'cats WHERE access!=3 AND ID='.$d)->fetch(2))
{
	return;
}

#Set title
$content->title = $cat['name'];

#Page
if(isset($URL[1]) && is_numeric($URL[1]) && $URL[1] > 1)
{
	$page = $URL[1];
	$st = ($page-1) * $cfg['np'];
}
else
{
	$page = 1;
	$st = 0;
}

#Option: items from all subcategories
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

#Subcategories
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

#Assign to template
$content->file = array('cat');
$content->data = array(
	'cat'  => &$cat,
	'edit' => admit('C') ? url('editCat/'.$d, 'ref', 'admin') : null,
	'subcats' => isset($sc) ? $sc : null,
	'options' => admit($d,'CAT'),
	'add_url' => url('edit/'.$cat['type'], 'catid='.$d),
	'list_url'=> url('list/'.$cat['type'].'/'.$d)
);

#Category path
if($cat['opt'] & 1 && isset($cfg['catStr']))
{
	$content->data['path'] = catPath($d,$cat);
}
else
{
	$content->data['path'] = null;
}

#Load item list generator
if($cat['num'])
{
	include './mod/cat/'.$cat['type'].'.php';
}
else
{
	$content->data['cat_type'] = $lang['cats'];
	$content->data['cats_url'] = url('cats');
}