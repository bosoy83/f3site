<?php
if(iCMSa!=1 || !Admit('NM')) exit;
require LANG_DIR.'adm_o.php';

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
				$q->execute(array( (int)$seq, Clean($_POST['vis'][$id]), (int)$_POST['page'][$id], $id));
			}
		}

		#Usuñ menu i niepowi¹zane linki
		if($del)
		{
			$db->exec('DELETE FROM '.PRE.'menu WHERE ID IN ('.join(',', $del).')');
			$db->exec('DELETE FROM '.PRE.'mitems WHERE menu NOT IN (SELECT ID FROM '.PRE.'menu)');
		}
		$db->commit();
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
	if($m[3] != $prev)
	{
		$lng = $prev = ($m[3] === '2') ? $lang['off'] : $m[3];
	}
	else
	{
		$lng  = false;
	}
	$blocks[] = array(
		'id' => $m[0],
		'seq' => $m[1],
		'langs' => ListBox('lang',1,$m[3]),
		'disp'  => $m[3],
		'title' => $m[2],
		'page'  => $m[4],
		'lang'  => $lng
	);
}

#Do szablonu
$content->data['blocks'] = $blocks;