<?php
if(EC!=1) exit;

#Zapisz jako nowy
if(isset($_POST['asNew'])) $id = 0;

#Szablon i tytu³
$content->file = 'edit_img';
$content->title = $id ? $lang['edit3'] : $lang['add3'];

#Zapis?
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

		#Zapytanie
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'imgs SET cat=:cat, name=:name, author=:author,
				dsc=:dsc, file=:file, filem=:filem, access=:access, priority=:priority,
				type=:type, size=:size WHERE ID='.$id);
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'imgs (cat, name, dsc, type, date,
				priority, access, author, file, filem, size) VALUES (:cat,:name,:dsc,:type, "'.
				gmdate('Y-m-d H:i:s').'", :priority, :access, :author, :file, :filem, :size)');
		}
		$q->execute($img);

		#Nowy ID
		if(!$id) $id = $db->lastInsertId();

		#Zatwierd¼
		$e->apply();

		#Przekieruj do obrazu
		if(isset($_GET['ref'])) header('Location: '.URL.url('img/'.$id));

		#Info + linki
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

#Odczyt
else
{
	if($id)
	{
		$img=$db->query('SELECT * FROM '.PRE.'imgs WHERE ID='.$id)->fetch(2);

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
if(isset($cfg['editor']) && is_dir('plugins/editor'))
{
	$content->addScript('plugins/editor/loader.js');
}
else
{
	$content->addScript(LANG_DIR.'edit.js');
	$content->addScript('lib/editor.js');
}

#Dane + rozmiar
$content->data = array(
	'img'  => &$img,
	'id'   => $id,
	'cats' => Slaves(3,$img['cat']),
	'size' => $img['size'] ? explode('|',$img['size']) : array('',''),
	'fileman' => admit('FM') ? true : false
);