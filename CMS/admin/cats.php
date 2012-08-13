<?php
if(iCMSa!=1 || !admit('C')) exit;
require LANG_DIR.'admAll.php';

#Get categories
$res = $db->query('SELECT ID,name,access,type,num,lft,rgt FROM '.PRE.'cats'
	.(isset($URL[1]) ? ' WHERE type='.(int)$URL[1] : '').' ORDER BY lft');

#Initialize types and depth
$types = array('',$lang['arts'],$lang['files'],$lang['imgs'],$lang['links'],$lang['news']);
$depth = 0;
$last = 1;
$cats = array();

foreach($res as $cat)
{
	#Depth
	if($last > $cat['rgt'])
	{
		++$depth;
	}
	elseif($depth > 0 && $last+2 != $cat['rgt'] && $last+1 != $cat['lft'])
	{
		$depth -= ($cat['lft']-$last-1);
	}
	$last = $cat['rgt'];

	#Type
	switch($cat['access'])
	{
		case '1': $a = $lang['on2']; break;
		case '2': $a = $lang['hidden2']; break;
		case '3': $a = $lang['off2']; break;
		default: $a = $cat['access'];
	}

	$cats[] = array(
		'id'   => $cat['ID'],
		'name' => $cat['name'],
		'type' => $types[$cat['type']],
		'num'  => $cat['num'],
		'url'  => url('list/'.$cat['type'].'/'.$cat['ID']),
		'edit' => url('editCat/'.$cat['ID'], '', 'admin'),
		'depth'=> $depth,
		'disp' => $a,
	);
}

#Template
$content->add('cats', array(
	'cat' => $cats,
	'url' => url('editCats', '', 'admin')
));