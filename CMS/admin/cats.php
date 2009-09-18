<?php
if(iCMSa!=1 || !Admit('C')) exit;
require LANG_DIR.'admAll.php';

#Przelicz ilo¶æ
if(isset($_GET['count']))
{
	try
	{
		include './lib/categories.php';
		$db->beginTransaction(); CountItems(); $db->commit();
	}
	catch(PDOException $e)
	{
		$content->info($e->getMessage());
	}
}

#Informacja
$content->info($lang['dinfo'], array(
	'?a=editCat' => $lang['addCat'],
	'?a=cats&amp;count' => $lang['count']
) );

#Odczyt
$res = $db->query('SELECT ID,name,access,type,num,lft,rgt FROM '.PRE.'cats'
	.((isset($_GET['co']))?' WHERE type='.(int)$_GET['co']:'').' ORDER BY lft');

#Typy i struktura
$types = array('',$lang['arts'],$lang['files'],$lang['imgs'],$lang['links'],$lang['news']);
$depth = 0;
$last = 1;

foreach($res as $cat)
{
	#Poziom
	if($last > $cat['rgt'])
	{
		++$depth;
	}
	elseif($depth > 0 && $last+2 != $cat['rgt'] && $last+1 != $cat['lft'])
	{
		$depth -= ($cat['lft']-$last-1);
	}
	$last = $cat['rgt'];

	#Typ
	switch($cat['access'])
	{
		case 1: $a = $lang['on2']; break;
		case 2: $a = $lang['hidden2']; break;
		case 3: $a = $lang['off2']; break;
		default: $a = $cat['access'];
	}

	$cats[] = array(
		'id'   => $cat['ID'],
		'name' => $cat['name'],
		'type' => $types[$cat['type']],
		'url'  => '.?co=list&amp;act='.$cat['type'].'&amp;id='.$cat['ID'],
		'num'  => $cat['num'],
		'depth'=> $depth,
		'disp' => $a,
	);
}

$content->data['cat'] = $cats;