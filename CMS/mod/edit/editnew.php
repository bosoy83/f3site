<?php
if(EC!=1) exit;

//Funkcja zapisu
if($_POST)
{
	//Nowe dane
	$news = array(
	'opt' => (($_POST['x_br']) ? 1:0) + (($_POST['x_emo']) ? 2:0) + (($_POST['x_fn']) ? 4:0),
	'name'=> Clean($_POST['x_n']),
	'img' => Clean($_POST['x_i']),
	'txt' => &$_POST['x_txt'],
	'cat'	=> (int)$_POST['x_c'],
	'access'=> isset($_POST['x_a']) ? 1:2);

	//Start
	$e = new Saver($news,$id,'news');

	//Ma prawa?
	if($e->hasRight('N'))
	{
		//Query
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'news SET cat=:cat, name=:name, txt=:txt,
				img=:img, access=:access, opt=:opt WHERE ID='.$id);
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'news (cat,name,txt,date,author,img,access,opt)
				VALUES (:cat,:name,:txt,'.DATETIME.','.UID.',:img,:access,:opt)');
		}
		$q->execute($news);

		//Nowe ID
		$nid = $id ? $id : $db->lastInsertId();

		//Pe³ny tekst
		$news['text'] =& $_POST['x_ftxt'];

		$q = $db->prepare('REPLACE INTO '.PRE.'fnews (id,cat,text) VALUES ('.$nid.',?,?)');
		$q-> bindValue(1,$news['cat'],1); //INT
		$q-> bindParam(2,$news['text']);
		$q-> execute();

		//OK?
		if($e->apply())
		{
			$content->info( $lang['saved'], array(
				'?co=edit&amp;act=news'=> $lang['add5'],
				'?co=edit&amp;act=5'	 => $lang['news'],
				'?co=news&amp;id='.$nid=> $lang['seeit']));
			unset($e,$news);
			return;
		}
	}

	//B³±d?
	$e->showError();
}

//Formularz
else
{
	//Odczyt
	if($id)
	{
		$news = $db->query('SELECT n.*,f.text FROM '.PRE.'news n LEFT JOIN '.
			PRE.'fnews f ON n.ID=f.ID WHERE n.ID='.$id) -> fetch(2); //ASSOC

		//Prawa
		if(!$news || !Admit('N') || !Admit($news['cat'],'CAT',$news['author']))
		{
			return;
		}
	}
	else
	{
		$news = array('cat'=>$lastCat,'name'=>'','txt'=>'','text'=>'','access'=>1,'img'=>'','opt'=>1);
	}
}

//Edytor JS
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('cache/emots.js');
$content->addScript('lib/editor.js');

//Szablon i tytu³
$content->title = $id ? $lang['edit5'] : $lang['add5'];
$content->file  = 'edit_news';

//Dane
$content->data = array(
	'news' => &$news,
	'cats' => Slaves(5,$news['cat'],'N'),
	'url'  => '?co=edit&amp;act=new&amp;id='.$id
);
