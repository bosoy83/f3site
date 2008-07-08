<?php
if(EC!=1) exit;

#Zapis?
if($_POST)
{
	$img = array(
	'cat'   => (int)$_POST['cat'],
	'name'  => Clean($_POST['name']),
	'author'=> Clean($_POST['author']),
	'file'	=> Clean($_POST['file']),
	'filem' => Clean($_POST['fm']),
	'access'=> isset($_POST['access']) ? 1 : 0,
	'priority'=> (int)$_POST['priority'],
	'type'	=> (int)$_POST['type'],
	'dsc' 	=> &$_POST['dsc'],
	'size'  => $_POST['s1'] ? $_POST['s1'].'|'.$_POST['s2'] : '');

	try
	{
		$e = new Saver($img, $id, 'imgs');

		#Zapytanie
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'imgs SET cat=:cat, name=:name, author=:author,
				dsc=:dsc, file=:file, filem=:filem, access=:access, priority=:priority,
				type=:type, size=:size WHERE ID='.$id);
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'imgs (cat, name, dsc, type, date,
				priority, access, author, file, filem, size) VALUES (:cat,:name,:dsc,:type, "'.
				gmdate('Y-m-d').'", :priority, :access, :author, :file, :filem, :size)');
		}
		$q->execute($img);

		#Nowy ID
		if(!$id) $id = $db->lastInsertId();

		#OK?
		$e->apply();
		$content->info( $lang['saved'], array(
			'?co=edit&amp;act=img' => $lang['add3'],
			'?co=list&amp;act=3'   => $lang['imgs'],
			'?co=img&amp;id='.$id  => $img['name']));
		unset($e,$img);
		return 1;
	}
	catch(Exception $e)
	{
		$content->info($e->getMessage());
	}
}

#Odczyt
else
{
	if($id)
	{
		$img=$db->query('SELECT * FROM '.PRE.'imgs WHERE ID='.$id)->fetch(2);

		if(!$img || !Admit($img['cat'],'CAT',$img['author']))
		{
			return;
		}
	}
	else
	{
		$img = array('cat'=>$lastCat,'name'=>'','dsc'=>'','priority'=>2,'file'=>'img/',
			'filem'=>'img/','size'=>'','author'=>UID,'access'=>1,'type'=>1);
	}
}

#Edytor
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('lib/editor.js');

#Szablon i tytu³
$content->file = 'edit_img';
$content->title = $id ? $lang['edit3'] : $lang['add3'];

#Dane + rozmiar
$content->data = array(
	'img'  => &$img,
	'url'  => 'index.php?co=edit&amp;act=img&amp;id='.$id,
	'cats' => Slaves(3,$img['cat'],'I'),
	'size' => $img['size'] ? explode('|',$img['size']) : array('',''),
	'fileman' => Admit('FM') ? true : false
);
