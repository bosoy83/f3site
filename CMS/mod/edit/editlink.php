<?php
if(EC!=1) exit;

# Zapisz
if($_POST)
{
	# Dane
	$link = array(
	'cat' => (int)$_POST['x_c'],
	'dsc' => Clean($_POST['x_d']),
	'adr' => Clean( str_replace(array('javascript:','vbscript:'),'',$_POST['x_adr']) ),
	'name'=> Clean($_POST['x_n']),
	'nw'  => (isset($_POST['x_nw']) ? 1 : 0),
	'access'=> (isset($_POST['x_a']) ? 1 : 2),
	'priority'=> (int)$_POST['x_p'] );

	# Start
	$e = new Saver($link, $id, 'links', 'cat,access');

	# Ma prawa?
	if($e -> hasRight('L'))
	{
		# Zapytanie
		$db
			-> prepare('REPLACE INTO '.PRE.'links (ID,cat,name,dsc,access,adr,priority,nw)
				VALUES ('.(($id)?$id:'null').',:cat,:name,:dsc,:access,:adr,:priority,:nw)')
			-> execute($link);

		# OK?
		if($e -> apply())
		{
			$content->info( $lang['saved'], array(
				'?co=edit&amp;act=link'	=> $lang['add4'],
				'?co=edit&amp;act=4'		=> $lang['links'],
				$link['adr'] => $lang['seeit']));
			unset($e,$link);
			return;
		}
	}

	# B³¹d
	$e->showError();
}

# Odczyt
else
{
	if($id)
	{
		$link = $db->query('SELECT * FROM '.PRE.'links WHERE ID='.$id) -> fetch(2); //ASSOC

		#Prawa
		if(!$link || (!Admit('L') && !Admit($link['cat'],'CAT')))
		{
			return;
		}
	}
	else
	{
		$link = array('cat'=>$lastCat,'name'=>'','dsc'=>'','access'=>1,'nw'=>0,'priority'=>2,'adr'=>'http://');
	}
}

#Tytu³ i szablon
$content->title = $id ? $lang['edit4'] : $lang['add4'];
$content->file  = 'edit_link';

#Dane
$content->data = array(
	'link' => &$link,
	'cats' => Slaves(4,$link['cat'],'L'),
	'url'  => '?co=edit&amp;act=link&amp;id='.$id
);