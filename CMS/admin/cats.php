<?php
if(iCMSa!=1 || !Admit('C')) exit;
require LANG_DIR.'adm_o.php';

#Przelicz ilo¶æ
if(isset($_GET['act']))
{
	include './lib/categories.php';
	switch($_GET['act'])
	{
		case 'rec': CountItems(); break;
		case 'rep': RebuildTree(); break;
	}
}

#Informacja
$content->info($lang['dinfo'], array(
	'adm.php?a=editCat'  => $lang['addcat'],
	'?a=cats&amp;act=rec'=> $lang['count']
) );

#Odczyt
$res = $db->query('SELECT ID,name,access,type,num,lft,rgt FROM '.PRE.'cats'
	.((isset($_GET['co']))?' WHERE type='.(int)$_GET['co']:'').' ORDER BY lft');

#Typy i struktura
$types = Array('',$lang['arts'],$lang['files'],$lang['imgs'],$lang['links'],$lang['news']);
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
		$depth -= floor(($cat['lft']-$last)/2);
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
		'url'  => 'index.php?co=list&amp;act='.$cat['type'].'&amp;id='.$cat['ID'],
		'num'  => $cat['num'],
		'depth'=> $depth,
		'disp' => $a,
	);
}

$content->data['cat'] = $cats;