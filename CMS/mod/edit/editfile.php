<?php
if(EC!=1) exit;

#Zapis?
if($_POST)
{
	#Dane
	$file = array(
	'cat' =>(int)$_POST['x_c'],
	'dsc' =>Clean($_POST['x_d']),
	'name'=>Clean($_POST['x_n']),
	'file'=>Clean($_POST['x_f']),
	'size'=>Clean($_POST['x_s']),
	'fulld'=>$_POST['x_fd'],
	'author'=>Clean($_POST['x_au']),
	'access'=>((isset($_POST['x_a']))?1:2),
	'priority'=>(int)$_POST['x_p']);

	$e = new Saver($file,$id,'files');

	if($e->hasRight('F'))
	{
		#Zapytanie
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'files SET cat=:cat, name=:name, author=:author, dsc=:dsc,
				file=:file, access=:access, size=:size, priority=:priority, fulld=:fulld WHERE ID='.$id);
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'files (cat,name,author,date,dsc,file,access,size,
				priority,fulld) VALUES (:cat,:name,:author,'.DATETIME.',:dsc,:file,:access,:size,:priority,:fulld)');
		}
		$q->execute($file);
		$nid = $id ? $id : $db->lastInsertId();

		#OK?
		if($e->apply())
		{
			$content->info( $lang['saved'], array(
				'?co=edit&amp;act=file'=> $lang['add2'],
				'?co=edit&amp;act=2'	 => $lang['files'],
				'?co=file&amp;id='.$nid=> $lang['seeit']));
			unset($e,$file);
			return;
		}
	}

	#B³±d?
	$e->showError();
}

#Form
else
{
	#Odczyt
	if($id)
	{
		$file=$db->query('SELECT * FROM '.PRE.'files WHERE ID='.$id)->fetch(2);

		if(!$file || !Admit('F') || !Admit($file['cat'],'CAT',$file['author']))
		{
			return;
		}
	}
	else
	{
		$file=array('cat'=>$lastCat,'name'=>'','dsc'=>'','priority'=>2,'file'=>'files/',
			'size'=>'','author'=>UID,'fulld'=>'','access'=>1);
	}
}

#Skrypty JS
$content->addScript(LANG_DIR.'edit.js');
$content->addScript('lib/editor.js');

#Szablon i tytu³
$content->file  = 'edit_file';
$content->title = $id ? $lang['edit2'] : $lang['add2'];

#Dane
$content->data = array(
	'file' => &$file,
	'url'  => '?co=edit&amp;act=file&amp;id='.$id,
	'cats' => Slaves(2,$file['cat'],'F')
);
