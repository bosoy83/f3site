<?php
if(iCMSa!=1) exit;

#Zapis
if($_POST)
{
	$cat = array(
		'sect' => (int)$_POST['sect'],
		'name' => Clean($_POST['name']),
		'dsc'  => Clean($_POST['dsc']),
		'see'  => Clean($_POST['see']),
		'post' => Clean($_POST['post']),
		'rate' => (int)$_POST['rate'],
		'text' => &$_POST['text']
	);

	#Zmieñ rekord lub dodaj nowy
	try
	{
		if($id)
		{
			$cat['id'] = $id;
			$q = $db->prepare('UPDATE '.PRE.'bugcats SET sect=:sect, name=:name, dsc=:dsc, see=:see, post=:post, rate=:rate, text=:text WHERE ID=:id');
		}
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'bugcats (sect,name,dsc,see,post,rate,text) VALUES (:sect,:name,:dsc,:see,:post,:rate,:text)');
		}
		$q->execute($cat);

		#Przekieruj do listy kategorii
		Header('Location: '.URL.'adm.php?a=bugs');
		$content->message($lang['saved'], '?a=bugs');
	}
	catch(PDOException $e)
	{
		$content->info($e);
	}
}
elseif($id)
{
	if(!$cat = $db->query('SELECT * FROM '.PRE.'bugcats WHERE ID='.$id)->fetch(2)) return;
}
else
{
	$cat = array(
		'sect' => 1,
		'name' => '',
		'dsc'  => '',
		'see'  => $nlang,
		'post' => 'ALL',
		'rate' => 1,
		'text' => '',
	);
}

#Sekcje
$sect = array();
$res = $db->query('SELECT ID,title FROM '.PRE.'bugsect ORDER BY seq');
foreach($res as $x)
{
	$sect[] = array(
		'id'    => $x['ID'],
		'title' => $x['title'],
		'this'  => $x['ID'] == $cat['sect']
	);
}

#Szablon formularza
$content->file = 'adminEdit';
$content->data = array(
	'cat'   => &$cat,
	'sect'  => &$sect,
	'langs' => ListBox('lang', 1, $cat['see']),
	'title' => $id ? $lang['editCat'] : $lang['addCat']
);