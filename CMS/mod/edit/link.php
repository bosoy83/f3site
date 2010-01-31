<?php
if(EC!=1) exit;

#Zapisz jako nowy
if(isset($_POST['asNew'])) $id = 0;

#Tytu³ i szablon
$content->title = $id ? $lang['edit4'] : $lang['add4'];
$content->file  = 'edit_link';

#Zapisz
if($_POST)
{
	#Dane
	$link = array(
	'cat' => (int)$_POST['cat'],
	'dsc' => clean($_POST['dsc']),
	'adr' => clean( str_replace(array('javascript:','vbscript:'),'',$_POST['adr']) ),
	'name'=> clean($_POST['name']),
	'nw'  => isset($_POST['nw']),
	'access'=> isset($_POST['access']),
	'priority'=> (int)$_POST['priority'] );

	#Start
	try
	{
		$e = new Saver($link, $id, 'links', 'cat,access');

		#Zapytanie
		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'links SET cat=:cat, name=:name, dsc=:dsc,
				access=:access, adr=:adr, priority=:priority, nw=:nw WHERE ID='.$id);
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'links (cat,name,dsc,access,adr,priority,nw)
				VALUES (:cat,:name,:dsc,:access,:adr,:priority,:nw)');
		}
		$q->execute($link);
		if(!$id) $id = $db->lastInsertId();

		#Zatwierd¼
		$e->apply();

		#Przekieruj do linku
		if(isset($_GET['ref']) && isset($cfg['linkFull']))
		{
			header('Location: '.URL.url('link/'.$id));
		}

		#URL do linku
		$url = isset($cfg['linkFull']) ? url('link/'.$id) : $link['adr'];

		#Info + linki
		$content->info($lang['saved'], array(
			$url => sprintf($lang['see'], $link['name']),
			url($link['cat']) => $lang['goCat'],
			url('edit/4') => $lang['add4'],
			url('list/4') => $lang['links'],
			url('list/4/'.$link['cat']) => $lang['doCat']));
		unset($e,$link);
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
		$link = $db->query('SELECT * FROM '.PRE.'links WHERE ID='.$id) -> fetch(2); //ASSOC

		#Prawa
		if(!$link || !admit($link['cat'],'CAT'))
		{
			return;
		}
	}
	else
	{
		$link = array('cat'=>$lastCat,'name'=>'','dsc'=>'','access'=>1,'nw'=>0,'priority'=>2,'adr'=>'http://');
	}
}

#Dane
$content->data = array(
	'link' => &$link,
	'id'   => $id,
	'cats' => Slaves(4,$link['cat'])
);