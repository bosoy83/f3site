<?php
if(iCMSa!=1 || !Admit('R')) exit;
require LANG_DIR.'admAll.php';
require './lib/categories.php';

#Tytu³ strony
$content->title = $id ? $lang['editRss'] : $lang['addRss'];

#Zapisz
if($_POST)
{
	$rss = array(
		'name' => Clean($_POST['name']),
		'dsc'  => Clean($_POST['dsc']),
		'url'  => Clean($_POST['url']),
		'lang' => ctype_alnum($_POST['lang']) ? $_POST['lang'] : $nlang,
		'auto' => isset($_POST['auto']),
		'cat'  => (int)$_POST['cat'],
		'num'  => (int)$_POST['num']
	);

	try
	{
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'rss SET auto=:auto, name=:name, dsc=:dsc,
			url=:url, lang=:lang, cat=:cat, num=:num WHERE ID='.$id);
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'rss (auto,name,dsc,url,lang,cat,num)
			VALUES (:auto,:name,:dsc,:url,:lang,:cat,:num)');
		}
		$q->execute($rss);
		if(!$id) $id = $db->lastInsertId();

		#Odbuduj RSS
		RSS($id);
		$content->info($lang['saved'], array('rss/'.$id.'.xml' => $rss['name']));
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($e);
	}
}
elseif($id)
{
	if(!$rss = $db->query('SELECT * FROM '.PRE.'rss WHERE ID='.$id)->fetch(2))
	{
		return;
	}
}
else
{
	$rss = array(
		'name' => '',
		'dsc'  => '',
		'auto' => 1,
		'num'  => 20,
		'cat'  => 0,
		'lang' => $nlang,
		'url'  => URL,
	);
}

#Szablon
$content->data = array(
	'rss'   => $rss,
	'cats'  => Slaves(5, $rss['cat']),
	'langs' => ListBox('lang', 1, $rss['lang'])
);