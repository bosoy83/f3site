<?php
if(iCMSa!=1 || !Admit('B')) exit;

#Zapis
if($_POST)
{
	$ad = array(
		'name' => Clean($_POST['name']),
		'code' => $_POST['code'],
		'ison' => (int)$_POST['ison'],
		'gen'  => (int)$_POST['gen'] ); //Dane

	#Edytuj
	if($id)
	{
		$q = $db->prepare('UPDATE '.PRE.'banners SET gen=:gen, name=:name,
			ison=:ison, code=:code WHERE ID='.$id);
	}
	#Nowy
	else
	{
		$q = $db->prepare('INSERT INTO '.PRE.'banners (gen,name,ison,code)
			VALUES (:gen,:name,:ison,:code)');
	}

	#Zapis
	try
	{
		$q->execute($ad); $content->info($lang['saved']); return 1;
	}
	catch(PDOException $e)
	{
		$content->info($lang['error'].$e->errorInfo[0]);
	}
}
else
{
	if($id)
	{
		$ad = $db->query('SELECT * FROM '.PRE.'banners WHERE ID='.$id)->fetch(2);
		if(!$ad) { $content->info($lang['noex']); return; }
	}
	else
	{
		$ad = array('gen'=>1,'name'=>'','ison'=>1,'code'=>'');
	}
}

#Jêzyk
require LANG_DIR.'adm_o.php';

#Edytor
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('lib/editor.js');

#Tytu³ i dane
$content->title = $id ? $lang['editbn'] : $lang['addbn'];
$content->data = array(
	'ad'  => &$ad,
	'url' => '?a=editad'.($id ? '&amp;id='.$id : ''),
	'title'
);