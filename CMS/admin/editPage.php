<?php
if(iCMSa!=1 || !admit('P')) exit;
require LANG_DIR.'admAll.php';

#Tytu³ strony
$content->title = $id ? $lang['editPage'] : $lang['addPage'];

#Zapis
if($_POST)
{
	#Dziel linie
	$o = isset($_POST['o1']);

	#Emoty
	isset($_POST['o2']) && $o |= 2;

	#Na warstwie
	isset($_POST['o3']) && $o |= 4;

	#Komentarze
	isset($_POST['o4']) && $o |= 8;

	#PHP
	isset($_POST['o5']) && $o |= 16;

	#Dane
	$page = array(
	'text'	=> &$_POST['txt'],
	'access'=> clean($_POST['access']),
	'name'	=> clean($_POST['name']),
	'opt' 	=> $o
	);

	try
	{
		#Edycja
		if($id)
		{
			$q=$db->prepare('UPDATE '.PRE.'pages SET name=:name,access=:access,opt=:opt,text=:text WHERE ID=:id');
			$page['id'] = $id;
		}
		#Nowa strona
		else
		{
			$q=$db->prepare('INSERT INTO '.PRE.'pages (name,access,opt,text) VALUES (:name,:access,:opt,:text)');
		}
		$q->execute($page);

		#ID strony
		if(!$id) $id = $db->lastInsertId();

		#Powrót
		if(isset($_GET['ref'])) header('Location: '.URL.url('page/'.$id));

		#Info
		$content->info($lang['saved'], array(
			url('page/'.$id) => sprintf($lang['goto'], $page['name']),
			url('editPage/'.$id, '', 'admin') => $lang['edit'],
			url('editPage', '', 'admin') => $lang['addPage'] ));
		return 1;
	}
	catch(PDOException $e)
	{
		$content->info($e);
	}
}

#FORM
elseif($id)
{
	if(!$page = $db->query('SELECT * FROM '.PRE.'pages WHERE ID='.$id)->fetch(2))
	return;
}
else
{
	$page = array('name'=>'','access'=>1,'text'=>'','opt'=>13);
}

#Edytor JS
if(isset($cfg['editor']) && is_dir('plugins/editor'))
{
	$content->addScript('plugins/editor/loader.js');
}
else
{
	$content->addScript(LANG_DIR.'edit.js');
	$content->addScript('cache/emots.js');
	$content->addScript('lib/editor.js');
}

#Tytu³, dane
$content->data = array(
	'page' => &$page,
	'o1'   => $page['opt']&1,
	'o2'   => $page['opt']&2,
	'o3'   => $page['opt']&4,
	'o4'   => $page['opt']&8,
	'o5'   => $page['opt']&16
);