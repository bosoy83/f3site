<?php
if(EC!=1) exit;

#Zapis?
if($_POST)
{
	#Dane
	$file = array(
	'cat'  => (int)$_POST['cat'],
	'dsc'  => Clean($_POST['dsc']),
	'name' => Clean($_POST['name']),
	'file' => Clean($_POST['file']),
	'size' => Clean($_POST['size']),
	'fulld' => &$_POST['full'],
	'author' => Clean($_POST['author']),
	'access' => isset($_POST['access']) ? 1 : 2,
	'priority' => (int)$_POST['priority']);

	try
	{
		$e = new Saver($file, $id, 'files');

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
		if(!$id) $id = $db->lastInsertId();

		#OK?
		$e->apply();
		$content->info( $lang['saved'], array(
			'?co=edit&amp;act=file'=> $lang['add2'],
			'?co=list&amp;act=2'	 => $lang['files'],
			'?co=file&amp;id='.$id => $file['name']));
		unset($e,$file);
		return;
	}
	catch(Exception $e)
	{
		$content->info($e->getMessage());
	}
}

#Form
else
{
	#Odczyt
	if($id)
	{
		$file = $db->query('SELECT * FROM '.PRE.'files WHERE ID='.$id)->fetch(2);

		if(!$file || !Admit($file['cat'],'CAT',$file['author']))
		{
			return;
		}
	}
	else
	{
		$file = array('cat'=>$lastCat,'name'=>'','dsc'=>'','priority'=>2,'file'=>'files/',
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
	'cats' => Slaves(2,$file['cat'],'F'),
	'fileman' => Admit('FM') ? true : false
);
