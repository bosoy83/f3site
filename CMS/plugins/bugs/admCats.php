<?php
if(iCMSa!=1) exit;

#Zapisz masowe zmiany
if($_POST && $x = GetID(true))
{
	if(isset($_POST['del']))
	{
		$db->exec('DELETE FROM '.PRE.'bugcats WHERE ID IN('.$x.')');
	}
	else
	{
		$s = $b = array();
		if($_POST['sect'] != 'N')
		{
			$s[] = 'sect=?';
			$b[] = (int)$_POST['sect'];
		}
		if($_POST['acc'] != 'N')
		{
			$s[] = 'see=?';
			$b[] = clean($_POST['ch_a']);
		}
		if($s)
		{
			$db->exec('UPDATE '.PRE.'bugcats SET '.join(', ',$s).' WHERE ID IN('.$x.')')->execute($b);
		}
	}
	unset($_POST,$s,$b);
}

#Pobierz kategorie
$res = $db->query('SELECT c.ID,c.name,c.see,c.num,s.title FROM '.PRE.'bugcats c LEFT JOIN '.PRE.'bugsect s ON c.sect = s.ID ORDER BY s.seq,c.name');

$cat  = array();
$sect = '';
$show = 0;
$num  = 0;

foreach($res as $x)
{
	#Sekcja
	if($x['title'] != $sect)
	{
		$sect = $x['title'];
		$show = 1;
	}
	elseif($show == 1)
	{
		$show = 0;
	}

	#Dost�p
	switch($x['see'])
	{
		case 1: $a = $lang['yes']; break;
		case 2: $a = $lang['no']; break;
		default: $a = $x['see'];
	}

	$cat[] = array(
		'id'   => $x['ID'],
		'name' => $x['name'],
		'num'  => $x['num'],
		'url'  => url('bugs/edit/'.$x['ID'], '', 'admin'),
		'access' => $a,
		'section' => $show ? $sect : false
	);
	++$num;
}

#Sekcje i j�zyki
if($num > 0)
{
	$sect = $db->query('SELECT ID,title FROM '.PRE.'bugsect ORDER BY seq')->fetchAll(2);
	$lng  = listBox('lang',1,'');
}
else
{
	$sect = array();
	$lng  = '';
}

#Info + linki
$content->info($lang['bugsInfo'], array(
	url('bugs/edit','','admin')     => $lang['addCat'],
	url('bugs/sections','','admin') => $lang['manSect'],
	url('bugs/config','','admin')   => $lang['opt']
));

#Dane do szablonu
$content->file = 'adminCats';
$content->data = array(
	'cat' => &$cat,
	'langs' => &$lng,
	'section' => &$sect
);