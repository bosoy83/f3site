<?php
if(iCMSa!=1 || !admit('R')) exit;
require LANG_DIR.'admAll.php';
require './lib/categories.php';

#Tytu� strony i ID
$content->title = $id ? $lang['editRss'] : $lang['addRss'];

#Zapisz
if($_POST)
{
	$rss = array(
		'name' => clean($_POST['name']),
		'dsc'  => clean($_POST['dsc']),
		'url'  => clean($_POST['url']),
		'lang' => ctype_alnum($_POST['lang']) ? $_POST['lang'] : LANG,
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
		'lang' => LANG,
		'url'  => substr(URL, 0, -6),
	);
}

#Szablon
$content->data = array(
	'rss'   => $rss,
	'cats'  => Slaves(5, $rss['cat']),
	'langs' => listBox('lang', 1, $rss['lang'])
);
