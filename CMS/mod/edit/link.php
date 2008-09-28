<?php
if(EC!=1) exit;

#Tytu³ i szablon
$content->title = $id ? $lang['edit4'] : $lang['add4'];
$content->file  = 'edit_link';

#Zapisz
if($_POST)
{
	#Dane
	$link = array(
	'cat' => (int)$_POST['cat'],
	'dsc' => Clean($_POST['dsc']),
	'adr' => Clean( str_replace(array('javascript:','vbscript:'),'',$_POST['adr']) ),
	'name'=> Clean($_POST['name']),
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

		#Zatwierd¼
		$e->apply();
		$content->info( $lang['saved'], array(
			'?co=edit&amp;act=link'	=> $lang['add4'],
			'?co=list&amp;act=4'		=> $lang['links'],
			$link['adr'] => $link['name'])
		);
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
		if(!$link || !Admit($link['cat'],'CAT'))
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
	'cats' => Slaves(4,$link['cat']),
	'url'  => '?co=edit&amp;act=4&amp;id='.$id
);