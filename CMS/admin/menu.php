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

		foreach($_POST['seq'] as $id => $seq)
		{
			if(isset($_POST['x'][$id]))
			{
				$db->exec('DELETE FROM '.PRE.'menu WHERE ID='.$id);
				continue;
			}
			$q->execute(array( (int)$seq, Clean($_POST['vis'][$id]), (int)$_POST['page'][$id], $id));
		}
		$db->commit();
		unset($q,$seq,$_POST);

		#Odbuduj menu
		require './lib/mcache.php';
		RenderMenu();
	}
	catch(PDOException $e)
	{
		$content->info($lang['error'].$e->errorInfo[2]); return 1;
	}
}

#Pobierz bloki
$res = $db->query('SELECT ID,seq,text,disp,menu,type FROM '.PRE.'menu ORDER BY menu,seq');
$res->setFetchMode(3); //Num
$num = 0;
$blocks = array();

foreach($res as $m)
{
	$blocks[] = array(
		'id' => $m[0],
		'seq' => $m[1],
		'langs' => ListBox('lang',1,$m[3]),
		'disp'  => $m[3],
		'title' => $m[2],
		'page'  => $m[4]
	);
}

#Do szablonu
$content->data['blocks'] = $blocks;