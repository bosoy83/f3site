<?php
if(iCMSa!=1 || !admit('B')) exit;

#Zapis
if($_POST)
{
	$ad = array(
		'name' => clean($_POST['name']),
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
		if(!$ad = $db->query('SELECT * FROM '.PRE.'banners WHERE ID='.$id)->fetch(2))
		return;
	}
	else
	{
		$ad = array('gen'=>1,'name'=>'','ison'=>1,'code'=>'');
	}
}

#Jêzyk
require LANG_DIR.'admAll.php';

#Edytor
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('lib/editor.js');

#Tytu³ i dane
$content->title = $id ? $lang['editAd'] : $lang['addAd'];
$content->data = array('ad' => &$ad);