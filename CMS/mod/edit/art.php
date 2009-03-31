<?php
if(EC!=1) exit;

#Tytu³ i szablon
$content->title = $id ? $lang['edit1'] : $lang['add1'];
$content->file = 'edit_art';

if($_POST)
{
	#Ilo¶æ stron
	$num = count($_POST['txt']);
	$full = array();

	#Nowe dane
	$art = array(
	'pages' => $num,
	'cat'   => (int)$_POST['cat'],
	'dsc'   => Clean($_POST['dsc']),
	'name'  => Clean($_POST['name']),
	'author' => Clean($_POST['author']),
	'access'  => isset($_POST['access']),
	'priority'=> (int)$_POST['priority']);

	#Strony
	for($i=0; $i<$num; ++$i)
	{
		$full[] = array( $i+1, &$_POST['txt'][$i], (isset($_POST['br'][$i]) ? 1 : 0) +
			(isset($_POST['emo'][$i]) ? 2 : 0) + (isset($_POST['code'][$i]) ? 4 : 0) );
	}

	try
	{
		#Klasa
		$e = new Saver($art,$id,'arts');

		#Zapytanie
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'arts SET cat=:cat, name=:name, dsc=:dsc,
			author=:author, access=:access, priority=:priority, pages=:pages WHERE ID='.$id);
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'arts (cat,name,dsc,date,author,access,priority,pages)
			VALUES (:cat,:name,:dsc,"'.gmdate('Y-m-d H:i:s').'",:author,:access,:priority,:pages)');
		}
		$q->execute($art);

		#Nowe ID
		if(!$id) $id = $db->lastInsertId();

		#Pe³na tre¶æ
		$q = $db->prepare('REPLACE INTO '.PRE.'artstxt (id,page,cat,text,opt)
			VALUES ('.$id.',?,'.$art['cat'].',?,?)');

		#Tre¶æ
		for($i=0; $i<$num; ++$i) $q->execute($full[$i]);

		#Usuñ inne
		$db->exec('DELETE FROM '.PRE.'artstxt WHERE ID='.$id.' AND page>'.$num);

		$e->apply();

		$content->info( $lang['saved'], array(
			'?co=edit&amp;act=1' => $lang['add1'],
			'?co=list&amp;act=1' => $lang['arts'],
			'?co=art&amp;id='.$id  => $art['name']));
		unset($e,$q,$art,$full);
		return;
	}
	catch(Exception $e)
	{
		$content->info($e->getMessage());
	}
}

#FORM - Odczyt
else
{
	if($id)
	{
		$res = $db->query('SELECT * FROM '.PRE.'arts WHERE ID='.$id);
		$art = $res->fetch(2); //ASSOC
		$res = null;

		#Prawa
		if(!$art || !Admit($art['cat'],'CAT',$art['author'])) return;

		#Pobierz tre¶æ
		$res = $db->query('SELECT page,text,opt FROM '.PRE.'artstxt WHERE ID='.$id.' ORDER BY page');
		$full = $res->fetchAll(3); //NUM
		$res = null;
		if(!$full) $full = array(array(1,'',1));
	}
	else
	{
		$art = array(
			'pages' => 1, 'name' => '', 'access' => 1, 'priority' => 2, 'dsc' => '',
			'author'=> $user[UID]['login'], 'cat' => $lastCat);
		$full = array(array(1,'',1));
	}
}

#Pola checkbox
foreach($full as $key=>&$val)
{
	$full[$key] += array('br' => $val[2]&1, 'emo' => $val[2]&2, 'code' => $val[2]&4);
}

#Skrypty JS
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('cache/emots.js');
$content->addScript('lib/editor.js');

#Dane + URL + kategorie
$content->data = array(
	'art' => &$art,
	'full' => &$full,
	'cats' => Slaves(1,$art['cat'])
);