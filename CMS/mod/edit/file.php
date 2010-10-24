<?php
if(EC!=1) exit;

#Zapisz jako nowy
if(isset($_POST['asNew'])) $id = 0;

#Szablon i tytu³
$content->file  = 'edit_file';
$content->title = $id ? $lang['edit2'] : $lang['add2'];

#Zapis?
if($_POST)
{
	#Dane
	$file = array(
	'cat'  => (int)$_POST['cat'],
	'dsc'  => clean($_POST['dsc']),
	'name' => clean($_POST['name']),
	'file' => clean($_POST['file']),
	'size' => clean($_POST['size']),
	'fulld' => &$_POST['full'],
	'author' => clean($_POST['author']),
	'access' => isset($_POST['access']),
	'priority' => (int)$_POST['priority']);

	try
	{
		$e = new Saver($file, $id, 'files');

		#Zapytanie
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'files SET cat=:cat, name=:name, author=:author, dsc=:dsc,
				file=:file, access=:access, size=:size, priority=:priority, fulld=:fulld WHERE ID='.$id);
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'files (cat, name, author, date, dsc,
				file, access, size, priority, fulld) VALUES (:cat, :name, :author, "'.
				gmdate('Y-m-d H:i:s').'", :dsc, :file, :access, :size, :priority, :fulld)');
		}
		$q->execute($file);
		if(!$id) $id = $db->lastInsertId();

		#Zatwierd¼
		$e->apply();

		#Przekieruj do pliku
		if(isset($_GET['ref'])) header('Location: '.URL.url('file/'.$id));

		#Informaja + linki
		$content->info($lang['saved'], array(
			url('file/'.$id)  => sprintf($lang['see'], $file['name']),
			url($file['cat']) => $lang['goCat'],
			url('edit/2')     => $lang['add2'],
			url('list/2')     => $lang['files'],
			url('list/2/'.$file['cat']) => $lang['doCat']));
		unset($e,$file);
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($e->getMessage());
	}
}

#Form
else
{
	#Odczyt
	if($id)
	{
		$file = $db->query('SELECT * FROM '.PRE.'files WHERE ID='.$id)->fetch(2);

		if(!$file || !admit($file['cat'],'CAT',$file['author']))
		{
			return;
		}
	}
	else
	{
		$file = array(
			'cat' => $lastCat, 'name' => '', 'dsc' => '', 'priority' => 2,
			'file'=> 'files/', 'size' => '', 'author' => $user['login'], 'fulld' => '', 'access' => 1);
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

#Dane
$content->data = array(
	'file' => &$file,
	'id'   => $id,
	'cats' => Slaves(2,$file['cat']),
	'fileman' => admit('FM') ? true : false
);