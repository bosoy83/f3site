<?php
if(EC!=1) exit;

#Zapisz jako nowy
if(isset($_POST['asNew'])) $id = 0;

#Tytul i szablon
$content->title = $id ? $lang['edit1'] : $lang['add1'];
$content->file = 'edit_art';

if($_POST)
{
	$num = count($_POST['txt']);
	$full = array();

	#Strony + encje do PRE i CODE
	for($i=0; $i<$num; ++$i)
	{
		if(empty($_POST['txt'][$i])) continue;
		if(isset($_POST['code'][$i]))
		{
			$_POST['txt'][$i] = preg_replace_callback(array(
			'#<(pre)([^>]*)>(.*?)</pre>#si',
			'#<(code)([^>]*)>(.*?)</code>#si'), create_function('$x',
			'return "<$x[1]$x[2]>".htmlspecialchars($x[3],0)."</$x[1]>";'), $_POST['txt'][$i]);
		}
		$full[] = array($i+1, &$_POST['txt'][$i], isset($_POST['br'][$i]) +
			(isset($_POST['emo'][$i]) ? 2 : 0) + (isset($_POST['code'][$i]) ? 4 : 0));
	}

	#Nowe dane
	$art = array(
	'pages' => count($full),
	'cat'   => (int)$_POST['cat'],
	'dsc'   => clean($_POST['dsc']),
	'name'  => clean($_POST['name']),
	'author' => clean($_POST['author']),
	'access'  => isset($_POST['access']),
	'priority'=> (int)$_POST['priority']);

	try
	{
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

		#Pelna tresc
		$q = $db->prepare('REPLACE INTO '.PRE.'artstxt (id,page,cat,text,opt)
			VALUES ('.$id.',?,'.$art['cat'].',?,?)');

		#Tresc
		foreach($full as &$x) $q->execute($x);

		#Usun inne
		$db->exec('DELETE FROM '.PRE.'artstxt WHERE ID='.$id.' AND page>'.count($full));

		#Zatwierdz
		$e->apply();

		#Przekieruj do artykulu
		if(isset($_GET['ref']) && is_numeric($_GET['ref']))
		{
			$page = $_GET['ref']>1 && isset($full[$_GET['ref']-1]) ? '/'.$_GET['ref'] : '';
			header('Location: '.URL.url('art/'.$id.$page));
		}

		#Info + linki
		$content->info($lang['saved'], array(
			url('art/'.$id)  => sprintf($lang['see'], $art['name']),
			url($art['cat']) => $lang['goCat'],
			url('edit/1')    => $lang['add1'],
			url('list/1')    => $lang['arts'],
			url('list/1/'.$art['cat']) => $lang['doCat']));
		unset($e,$q,$art,$full);
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($e->getMessage());
	}
}
else
{
	if($id)
	{
		$res = $db->query('SELECT * FROM '.PRE.'arts WHERE ID='.$id);
		$art = $res->fetch(2); //ASSOC
		$res = null;

		#Prawa
		if(!$art || !admit($art['cat'],'CAT',$art['author'])) return;

		#Pobierz tresc
		$res = $db->query('SELECT page,text,opt FROM '.PRE.'artstxt WHERE ID='.$id.' ORDER BY page');
		$full = $res->fetchAll(3);
		$res = null;
		if(!$full) $full = array(array(1,'',1));
	}
	else
	{
		$art = array(
			'pages' => 1, 'name' => '', 'access' => 1, 'priority' => 2, 'dsc' => '',
			'author'=> $user['login'], 'cat' => $lastCat);
		$full = array(array(1,'',1));
	}
}

#Pola checkbox
foreach($full as $key=>&$val)
{
	$full[$key] = array('page'=>$val[0], 'txt'=>$val[1], 'br'=>$val[2]&1, 'emo'=>$val[2]&2, 'code'=>$val[2]&4);
	if($full[$key]['code'])
	{
		$full[$key]['txt'] = preg_replace_callback(array(
			'#<(pre)([^>]*)>(.*?)</pre>#si',
			'#<(code)([^>]*)>(.*?)</code>#si'), create_function('$x',
			'return "<$x[1]$x[2]>".htmlspecialchars_decode($x[3],0)."</$x[1]>";'), $full[$key]['txt']);
	}
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

#Dane + URL + kategorie
$content->data = array(
	'art' => &$art,
	'id'  => $id,
	'full' => &$full,
	'cats' => Slaves(1,$art['cat'])
);