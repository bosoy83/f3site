<?php
if(EC!=1) exit;

#Zapis?
if($_POST)
{
	$img=array(
	'cat' => (int)$_POST['x_c'],
	'name' => Clean($_POST['x_n']),
	'author'=> Clean($_POST['x_au']),
	'dsc' 	=> Clean($_POST['x_d']),
	'file'	=> Clean($_POST['x_f']),
	'filem' => Clean($_POST['x_fm']),
	'access'=> ((isset($_POST['x_a'])) ? 1 : 0),
	'priority'=> (int)$_POST['x_p'],
	'type'	=> (int)$_POST['x_t'],
	'size'  => (($_POST['x_s1']) ? $_POST['x_s1'].'|'.$_POST['x_s2'] : '') );

	$e=new Saver($img,$id,'imgs');

	if($e->hasRight('I'))
	{
		#Zapytanie
		if($id)
		{
			$q=$db->prepare('UPDATE '.PRE.'imgs SET cat=:cat, name=:name, author=:author,
				dsc=:dsc, file=:file, filem=:filem, access=:access, priority=:priority,
				type=:type, size=:size WHERE ID='.$id);
		}
		else
		{
			$q=$db->prepare('INSERT INTO '.PRE.'imgs (cat,name,dsc,type,date,priority,
				access,author,file,filem,size) VALUES (:cat,:name,:dsc,:type,'.DATETIME.',
				:priority,:access,:author,:file,:filem,:size)');
		}
		$q->execute($img);

		#Nowy ID
		$nid = $id ? $id : $db->lastInsertId();

		#OK?
		if($e->apply())
		{
			$content->info( $lang['saved'], array(
				'?co=edit&amp;act=img' => $lang['add3'],
				'?co=edit&amp;act=3'   => $lang['imgs'],
				'?co=img&amp;id='.$nid => $lang['seeit']));
			unset($e,$img);
			return;
		}
	}
	#B³±d?
	$e->showError();
}

#Odczyt
else
{
	if($id)
	{
		$img=$db->query('SELECT * FROM '.PRE.'imgs WHERE ID='.$id)->fetch(2);

		if(!$img || !Admit('I') || !Admit($img['cat'],'CAT',$img['author']))
		{
			return;
		}
	}
	else
	{
		$img=array('cat'=>$lastCat,'name'=>'','dsc'=>'','priority'=>2,'file'=>'img/',
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
	'size' => $img['size'] ? explode('|',$img['size']) : array('','')
);
