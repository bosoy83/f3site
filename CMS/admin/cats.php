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

#Mo¿e usuwaæ?
$del = Admit('DEL') ? 1 : 0;

#Informacja
$content->info($lang['dinfo'], array(
	'adm.php?a=editCat'  => $lang['addcat'],
	'?a=cats&amp;act=rec'=> $lang['count']
) );

#Odczyt
$res = $db->query('SELECT ID,name,access,type,num,lft,rgt FROM '.PRE.'cats'
	.((isset($_GET['co']))?' WHERE type='.(int)$_GET['co']:'').' ORDER BY lft');

#Typy i kolory
$types = Array('',$lang['arts'],$lang['files'],$lang['imgs'],$lang['links'],$lang['news']);
$depth = 0;
$last = 1;

foreach($res as $cat)
{
	#Je¿eli drzewo kategorii ¼le wy¶wietla siê, usuñ # z nastêpnej linii:
	#echo '$last = '.$last.', $depth = '.$depth.', $cat[lft] = '.$cat['lft'].', $cat[rgt] = '.$cat['rgt'];

	#Poziom
	if($last>$cat['rgt'])
	{
		++$depth;
	}
	elseif($depth>0 && $last+2!=$cat['rgt'] && $last+1!=$cat['lft'])
	{
		$depth-=floor(($cat['rgt']-$last)/2);
	}
	$last=$cat['rgt'];

	#Je¿eli drzewo kategorii ¼le wy¶wietla siê, usuñ # z nastêpnej linii:
	#echo '&nbsp;&nbsp; Potem: $depth = '.$depth.'<br />';

	#Typ
	switch($cat['access'])
	{
		case 1: $type = $lang['on2']; break;
		case 2: $type = $lang['hidden2']; break;
		case 3: $type = $lang['off2']; break;
		default: $type = $cat['access'];
	}

	$cats[] = array(
		'id'   => $cat['ID'],
		'name' => $cat['name'],
		'type' => $type,
		'url'  => 'index.php?co=edit&amp;act='.$cat['type'].'&amp;id='.$cat['ID'],
		'num'  => $cat['num'],
		'depth'=> $depth,
		'del'  => $del
	);
}

$content->data['cat'] =& $cats;