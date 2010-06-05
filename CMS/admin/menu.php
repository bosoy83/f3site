<?php
if(iCMSa!=1 || !admit('N')) exit;
require LANG_DIR.'admAll.php';

#Tytu³ strony
$content->title = $lang['nav'];

#Zapis i usuwanie bloków
if($_POST)
{
	try
	{
		$db->beginTransaction();
		$q = $db->prepare('UPDATE '.PRE.'menu SET seq=?, disp=?, menu=? WHERE ID=?');
		$del = array();

		foreach($_POST['seq'] as $id => $seq)
		{
			if(isset($_POST['x'][$id]))
			{
				$del[] = $id;
			}
			else
			{
				$q->execute(array( (int)$seq, clean($_POST['vis'][$id]), (int)$_POST['page'][$id], $id));
			}
		}

		#Usuñ menu i niepowi¹zane linki
		if($del)
		{
			$db->exec('DELETE FROM '.PRE.'menu WHERE ID IN ('.join(',', $del).')');
			$db->exec('DELETE FROM '.PRE.'mitems WHERE menu NOT IN (SELECT ID FROM '.PRE.'menu)');
		}
		$db->commit();
		$content->info($lang['saved']);
		unset($q,$seq,$_POST);

		#Odbuduj menu
		require './lib/mcache.php';
		RenderMenu();
	}
	catch(PDOException $e)
	{
		$content->info($lang['error'].$e); return 1;
	}
}

#Pobierz bloki
$res = $db->query('SELECT ID,seq,text,disp,menu,type FROM '.PRE.'menu ORDER BY disp,menu,seq');
$res->setFetchMode(3); //Num
$num = 0;
$lng = '1';
$prev = '1';
$blocks = array();

foreach($res as $m)
{
	if($m[3] != $prev && $m[3] != '3' && $m[3] != '2')
	{
		$lng = $prev = $m[3];
	}
	else
	{
		$lng  = false;
	}
	$blocks[] = array(
		'id' => $m[0],
		'seq' => $m[1],
		'url'  => url('editMenu/'.$m[0], '', 'admin'),
		'langs' => listBox('lang',1,$m[3]),
		'disp'  => $m[3],
		'title' => $m[2],
		'page'  => $m[4],
		'group' => $lng
	);
}

#Do szablonu
$content->data = array(
	'blocks' => $blocks,
	'newURL' => url('editMenu', '', 'admin')
);