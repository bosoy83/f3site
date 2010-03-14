<?php
if(iCMSa!=1 || !admit('N')) exit;
require LANG_DIR.'admAll.php';

#Tytu³ strony
$content->title = $id ? $lang['editBox'] : $lang['addBox'];

#Zapis
if($_POST)
{
	#Dane
	$m = array(
		'text' => clean($_POST['text']),
		'disp' => clean($_POST['disp']),
		'img'  => clean($_POST['img']),
		'menu' => (int)$_POST['menu'],
		'type' => (int)$_POST['type'],
		'value'=> $_POST['value']
	);

	#Opcje menu
	$o = array();
	$ile = isset($_POST['adr']) ? count($_POST['adr']) : 0;
	for($i=0;$i<$ile;++$i)
	{
		$o[] = array(
			0 => $_POST['txt'][$i],
			1 => (int)$_POST['t'][$i],
			2 => clean($_POST['adr'][$i]),
			3 => isset($_POST['nw'][$i]),
			4 => $i,
			5 => $id
		);
	}

	#START
	try
	{
		$db->beginTransaction();

		#Edytuj
		if($id && !isset($_POST['savenew']))
		{
			$q = $db->prepare('UPDATE '.PRE.'menu SET text=:text, disp=:disp, menu=:menu,
				type=:type, img=:img, value=:value WHERE ID='.$id);
			$db->exec('DELETE FROM '.PRE.'mitems WHERE menu='.$id);
		}
		#Nowy
		else
		{
			$q = $db->prepare('INSERT INTO '.PRE.'menu (seq,text,disp,menu,type,img,value)
				VALUES ('.(dbCount('menu')+1).',:text,:disp,:menu,:type,:img,:value)');
		}
		$q->execute($m);

		#ID
		if(!$id OR isset($_POST['savenew'])) $id = $db->lastInsertId();

		#Linki
		if($m['type']==3)
		{
			#Dodaj pozycje menu
			$q = $db->prepare('INSERT INTO '.PRE.'mitems (text,type,url,nw,seq,menu) VALUES (?,?,?,?,?,?)');
			foreach($o as &$i)
			{
				$q->execute($i);
			}
		}
		$db->commit();

		#Generuj menu
		include './lib/mcache.php';
		RenderMenu();

		#Lista
		header('Location: '.URL.url('menu', '', 'admin'));
		$content->message($lang['saved'], url('menu', '', 'admin'));
	}
	catch(PDOException $e)
	{
		$content->info($e->getMessage());
	}
}

#Odczyt (ASSOC)
elseif($id)
{
	if(!$m = $db->query('SELECT * FROM '.PRE.'menu WHERE ID='.$id) -> fetch(2))
	return;

	if($m['type'] == 3)
	{
		$o = $db->query('SELECT text,type,url,nw FROM '.PRE.'mitems WHERE menu='.$id.' ORDER BY seq')->fetchAll(3);
	}
	else $o = array();
}
else
{
	$m = array('text'=>'', 'disp'=>'', 'img'=>'0', 'menu'=>1, 'type'=>3, 'value'=>'');
	$o = array(array('', 0, '', 0));
}

$content->addScript('lib/forms.js');
$content->data = array(
	'menu' => &$m,
	'item' => &$o,
	'fileman'  => admit('FM'),
	'langlist' => listBox('lang', 1, $m['disp'])
);