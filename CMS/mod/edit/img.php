<?php
if(EC!=1) exit;

#Action: save as new
if(isset($_POST['asNew'])) $id = 0;

#Page title
$content->title = $id ? $lang['edit3'] : $lang['add3'];

#Action: save
if($_POST)
{
	$img = array(
	'cat'   => (int)$_POST['cat'],
	'name'  => clean($_POST['name']),
	'author'=> clean($_POST['author']),
	'file'	=> clean($_POST['file']),
	'filem' => clean($_POST['fm']),
	'access'=> isset($_POST['access']),
	'priority'=> (int)$_POST['priority'],
	'type'	=> (int)$_POST['type'],
	'dsc' 	=> &$_POST['dsc'],
	'size'  => $_POST['s1'] ? $_POST['s1'].'|'.$_POST['s2'] : '');

	try
	{
		$e = new Saver($img, $id, 'imgs');

		#Prepare query
		if($id)
		{
			$img['ID'] = $id;
			$q = $db->prepare('UPDATE '.PRE.'imgs SET cat=:cat, access=:access,
				name=:name, author=:author, dsc=:dsc, file=:file, filem=:filem,
				priority=:priority, type=:type, size=:size WHERE ID=:ID');
		}
		else
		{
			$img['date'] = gmdate('Y-m-d H:i:s');
			$q = $db->prepare('INSERT INTO '.PRE.'imgs (cat,access,name,dsc,type,date,
				priority,author,file,filem,size) VALUES (:cat,:access,:name,:dsc,:type,
				:date,:priority,:author,:file,:filem,:size)');
		}
		$q->execute($img);

		#New ID
		if(!$id) $id = $db->lastInsertId();

		#Apply changes
		$e->apply();

		#Redirect to link
		if(isset($_GET['ref'])) header('Location: '.URL.url('img/'.$id));

		#Info + links
		$content->info($lang['saved'], array(
			url('img/'.$id)  => sprintf($lang['see'], $img['name']),
			url($img['cat']) => $lang['goCat'],
			url('edit/3')    => $lang['add3'],
			url('list/3')    => $lang['imgs'],
			url('list/3/'.$img['cat']) => $lang['doCat']));
		unset($e,$img);
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($e->getMessage());
	}
}

#Action: edit
else
{
	if($id)
	{
		$img = $db->query('SELECT * FROM '.PRE.'imgs WHERE ID='.$id)->fetch(2);
		if(!$img || !admit($img['cat'],'CAT',$img['author']))
		{
			return;
		}
	}
	else
	{
		$img = array(
			'cat' => $lastCat, 'name' => '', 'dsc' => '', 'priority' => 2, 'file'=> 'img/',
			'filem' => 'img/', 'size' => '', 'author' => $user['login'], 'access' => 1, 'type' => 1);
	}
}

#Edytor JS
if(isset($cfg['wysiwyg']) && is_dir('plugins/editor'))
{
	$content->script('plugins/editor/loader.js');
}
else
{
	$content->script(LANG_DIR.'edit.js');
	$content->script('lib/editor.js');
}

#Template
$content->add('edit_img', array(
	'img'  => &$img,
	'id'   => $id,
	'cats' => Slaves(3,$img['cat']),
	'size' => $img['size'] ? explode('|',$img['size']) : array('',''),
	'fileman' => admit('FM') ? true : false
));
