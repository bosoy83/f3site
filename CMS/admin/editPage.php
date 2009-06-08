<?php
if(iCMSa!=1 || !Admit('P')) exit;
require LANG_DIR.'admAll.php';

#Zapis
if($_POST)
{
	#Dane, OPCJE: 1 - BR, 2 - emoty, 4 - w tabeli, 8 - komentarze, 16 - PHP
	$page = array(
		'text'	=> &$_POST['txt'],
		'access'=> Clean($_POST['access']),
		'name'	=> Clean($_POST['name']),
		'opt' 	=> (isset($_POST['o1'])?1:0) + (isset($_POST['o2'])?2:0) + (isset($_POST['o3'])?4:0) +
		(isset($_POST['o4'])?8:0) + (isset($_POST['o5'])?16:0) );

	#Edycja
	if($id)
	{
		$q=$db->prepare('UPDATE '.PRE.'pages SET name=:name,access=:access,opt=:opt,text=:text WHERE ID='.$id);
	}
	#Nowa strona
	else
	{
		$q=$db->prepare('INSERT INTO '.PRE.'pages (name,access,opt,text) VALUES (:name,:access,:opt,:text)');
	}

	#OK
	try
	{
		$q->execute($page);  if(!$id) $id = $db->lastInsertId();
		$content->info($lang['saved'], array(
			'.?co=page&amp;id='.$id => $lang['goto'],
			'?a=editPage' => $lang['addp'] ));
		return 1;
	}
	catch(PDOException $e)
	{
		$content->info($lang['error'].$e->errorInfo[0]);
	}
}

#FORM
else
{
	if($id)
	{
		$page = $db->query('SELECT * FROM '.PRE.'pages WHERE ID='.$id)->fetch(2);
		if(!$page)
		{
			$content->info($lang['error']); return;
		}
	}
	else
	{
		$page = array('name'=>'','access'=>1,'text'=>'','opt'=>13);
	}
}

#Biblioteki JS, tytu³, dane
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('lib/editor.js');
$content->addScript('cache/emots.js');
$content->title = $id ? $lang['editPage'] : $lang['addPage'];
$content->data = array(
	'page' => &$page,
	'o1'   => $page['opt']&1,
	'o2'   => $page['opt']&2,
	'o3'   => $page['opt']&4,
	'o4'   => $page['opt']&8,
	'o5'   => $page['opt']&16
);
