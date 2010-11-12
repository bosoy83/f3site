<?php
if(iCMSa!=1 || !admit('G')) exit;
require LANG_DIR.'admAll.php';

#Tytu³ strony i ID
$content->title = $id ? $lang['editGroup'] : $lang['addGroup'];

#Zapis
if($_POST)
{
	#Dane
	$group = array(
		'name' => clean($_POST['name']),
		'dsc'  => $_POST['dsc'],
		'access' => clean($_POST['access']),
		'opened' => isset($_POST['opened'])
	);

	#Edycja
	if($id)
	{
		$q = $db->prepare('UPDATE '.PRE.'groups SET name=:name, dsc=:dsc,
			access=:access, opened=:opened WHERE ID='.$id);
	}
	#Nowy
	else
	{
		$group['who'] = UID;
		$group['date'] = $_SERVER['REQUEST_TIME'];
		$q = $db->prepare('INSERT INTO '.PRE.'groups (name,dsc,access,opened,who,date)
			VALUES (:name,:dsc,:access,:opened,:who,:date)');
	}
	#OK?
	try
	{
		$q->execute($group);
		if(!$id) $id = $db->lastInsertId();

		#Przekieruj do grupy
		if(isset($_GET['ref'])) header('Location: '.URL.url('group/'.$id));

		$content->info($lang['saved'], array(
			url('group/'.$id) => $group['name'],
			url('editGroup/'.$id, '', 'admin') => $lang['editGroup'],
			url('editGroup', '', 'admin') => $lang['addGroup']));
		return 1;
	}
	catch(PDOException $e)
	{
		$content->info($lang['error'].$e);
	}
}

#Edytuj
elseif($id)
{
	if(!$group = $db->query('SELECT * FROM '.PRE.'groups WHERE ID='.$id)->fetch(2))
	return;
}
else
{
	$group = array('name'=>'','access'=>1,'opened'=>1,'dsc'=>'');
}

#Edytor JS
if(isset($cfg['wysiwyg']) && is_dir('plugins/editor'))
{
	$content->addScript('plugins/editor/loader.js');
}
else
{
	$content->addScript(LANG_DIR.'edit.js');
	$content->addScript('cache/emots.js');
	$content->addScript('lib/editor.js');
}

#Dane
$content->data = array(
	'group' => &$group,
	'langs' => listBox('lang', 1, $id ? $group['access'] : null)
);
