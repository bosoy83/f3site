<?php
if(EC!=1) exit;

#Szablon i tytu³
$content->title = $id ? $lang['edit5'] : $lang['add5'];
$content->file  = 'edit_news';

#Funkcja zapisu
if($_POST)
{
	#Nowe dane
	$news = array(
	'opt'  => isset($_POST['br']) + (isset($_POST['emo']) ? 2:0) + (isset($_POST['fn']) ? 4:0),
	'name' => clean($_POST['name']),
	'img'  => clean($_POST['img']),
	'txt'  => &$_POST['txt'],
	'cat'	 => (int)$_POST['cat'],
	'access' => isset($_POST['access']));

	#Pe³na treœæ
	$full = &$_POST['text'];

	#Start
	try
	{
		$e = new Saver($news,$id,'news');

		#Query
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'news SET cat=:cat, name=:name, txt=:txt,
				img=:img, access=:access, opt=:opt WHERE ID='.$id);
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'news (cat,name,txt,date,author,img,access,opt)
				VALUES (:cat,:name,:txt,"'.gmdate('Y-m-d H:i:s').'",'.UID.',:img,:access,:opt)');
		}
		$q->execute($news);

		#Nowe ID
		if(!$id) $id = $db->lastInsertId();

		$q = $db->prepare('REPLACE INTO '.PRE.'newstxt (id,cat,text) VALUES ('.$id.',?,?)');
		$q->bindValue(1, $news['cat'], 1); //INT
		$q->bindParam(2, $full);
		$q->execute();

		#Aktualizuj RSS
		RSS();

		#OK
		$e->apply();
		$content->info( $lang['saved'], array(
			url('edit/5') => $lang['add5'],
			url('list/5') => $lang['news'],
			url('news/'.$id) => $lang['seeit']));
		unset($e,$news);
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($e->getMessage());
	}
}

#Formularz
else
{
	#Odczyt
	if($id)
	{
		$news = $db->query('SELECT n.*,f.text FROM '.PRE.'news n LEFT JOIN '.
			PRE.'newstxt f ON n.ID=f.ID WHERE n.ID='.$id) -> fetch(2);
		$full = &$news['text'];

		#Prawa
		if(!$news || !admit($news['cat'],'CAT',$news['author'])) return;
	}
	else
	{
		$news = array('cat'=>$lastCat,'name'=>'','txt'=>'','access'=>1,'img'=>'','opt'=>1);
		$full = '';
	}
}

#Pola checkbox
$news['br']  = $news['opt'] & 1;
$news['emo'] = $news['opt'] & 2;
$news['fn']  = $news['opt'] & 4;

#Edytor JS
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('cache/emots.js');
$content->addScript('lib/editor.js');

#Dane
$content->data = array(
	'news' => &$news,
	'full' => &$full,
	'cats' => Slaves(5,$news['cat']),
	'fileman' => admit('FM')
);