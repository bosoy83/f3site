<?php
if(iCMSa!=1 || !Admit('G')) exit;
require LANG_DIR.'admAll.php';

#Tytu³ strony
$content->title = $id ? $lang['editGroup'] : $lang['addGroup'];

#Zapis
if($_POST)
{
	#Dane
	$group = array(
		'name' => Clean($_POST['name']),
		'dsc'  => $_POST['dsc'],
		'access' => Clean($_POST['access']),
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
		$q = $db->prepare('INSERT INTO '.PRE.'groups (name,dsc,access,opened)
			VALUES (:name,:dsc,:access,:opened)');
	}
	#OK?
	try
	{
		$q->execute($group); $content->info($lang['saved']); return 1;
	}
	catch(PDOException $e)
	{
		$content->info($lang['error'].$e);
	}
}

#Odczyt
else
{
	#Edycja
	if($id)
	{
		$group = $db->query('SELECT * FROM '.PRE.'groups WHERE ID='.$id)->fetch(2);
		if(!$group)
		{
			$content->info($lang['noex']); return;
		}
	}
	#Nowy
	else
	{
		$group = array('name'=>'','access'=>1,'opened'=>0,'dsc'=>'');
	}
}

#Edytor JS, tytu³
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('lib/editor.js');

#Dane
$content->data = array(
	'group' => &$group,
	'langs' => ListBox('lang', 1, ($id ? $group['access'] : null))
);