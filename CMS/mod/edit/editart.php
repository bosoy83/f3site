<?php
if(EC!=1) exit;

if($_POST)
{
	#Ilo¶æ stron
	$ile=count($_POST['x_txt']);

	#Nowe dane
	$art=array(
	'pages' => $ile,
	'cat'   => (int)$_POST['x_c'],
	'dsc'   => Clean($_POST['x_d']),
	'name'  => Clean($_POST['x_n']),
	'author'=> Clean($_POST['x_au']),
	'access'  => ((isset($_POST['x_a']))?1:2),
	'priority'=> (int)$_POST['x_p']);

	#Klasa
	$e=new Saver($art,$id,'arts');

	if($e->hasRight('A'))
	{
		#Zapytanie
		if($id)
		{
			$q=$db->prepare('UPDATE '.PRE.'arts SET cat=:cat, name=:name, dsc=:dsc,
			author=:author, access=:access, priority=:priority, pages=:pages WHERE ID='.$id);
		}
		else
		{
			$q=$db->prepare('INSERT INTO '.PRE.'arts (cat,name,dsc,date,author,access,priority,pages)
			VALUES (:cat,:name,:dsc,'.NOW.',:author,:access,:priority,:pages)');
		}
		$q->execute($art);

		#Nowe ID
		$nid = $id ? $id : $db->lastInsertId();

		#Pe³na tre¶æ
		$q=$db->prepare('REPLACE INTO '.PRE.'artstxt (id,page,cat,text,opt)
			VALUES ('.$nid.',?,'.$art['cat'].',?,?)');

		#Tre¶æ
		for($i=1;$i<=$ile;++$i)
		{
			#Dane i opcje
			if($_POST['x_txt'][$i] != '') $fart[] = array($i, &$_POST['x_txt'][$i],
				((isset($_POST['x_br'][$i])) ? 1 : 0) +
				((isset($_POST['x_emo'][$i])) ? 2 : 0) +
				((isset($_POST['x_col'][$i])) ? 4 : 0));

			#Zapis
			$q->execute($fart[($i-1)]);
		}

		#Usuñ inne
		$db->exec('DELETE FROM '.PRE.'artstxt WHERE ID='.$nid.' AND page>'.$ile);

		if($e->apply())
		{
			$content->info( $lang['saved'], array(
			'?co=edit&amp;act=art'=>$lang['add1'],
			'?co=edit&amp;act=1'	=>$lang['arts'],
			'?co=art&amp;id='.$nid=>$lang['seeit']));
			unset($e,$art,$fart);
			return;
		}
	}
	$e->showError(); #B³±d?
}

#FORM - Odczyt
else
{
	if($id)
	{
		$res=$db->query('SELECT * FROM '.PRE.'arts WHERE ID='.$id);
		$art=$res->fetch(2); //ASSOC
		$res=null;

		#Prawa
		if(!$art || (!Admit('A') && !Admit($art['cat'],'CAT',$art['author'])))
		{
			return;
		}

		#Pobierz tre¶æ
		$res=$db->query('SELECT page,text,opt FROM '.PRE.'artstxt WHERE ID='.$id);
		$fart=$res->fetchAll(3); //NUM
		$res=null;
		$ile=count($fart);
	}
	else
	{
		$art=array('pages'=>1,'name'=>'','access'=>1,'priority'=>2,'dsc'=>'','author'=>UID,'cat'=>$lastCat);
		$fart=array(array(1,'',0));
		$ile=1;
	}
}

#Szablon
$content->file = 'edit_art';

#Skrypty JS
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('cache/emots.js');
$content->addScript('lib/editor.js');

#Tytu³
$content->title = $lang[ (($id)?'edit1':'add1') ];

#Dane + URL + kategorie
$content->data['art'] =& $art;
$content->data['url'] = '?co=edit&amp;act=art&amp;id='.$id;
$content->data['ile'] = $ile;
$content->data['fart'] =& $fart;
$content->data['cats'] = Slaves(1,$art['cat'],'A');
