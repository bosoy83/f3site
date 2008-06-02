<?php
if(iCMSa!=1 || !Admit('NM')) exit;
require LANG_DIR.'adm_o.php';

#Zapis bloków
if($_POST)
{
	try
	{
		$db->beginTransaction();
		$q = $db->prepare('UPDATE '.PRE.'menu SET seq=?, disp=?, menu=? WHERE ID=?');

		foreach($_POST['seq'] as $id => &$m)
		{
			$q->execute(array( (int)$m, Clean($_POST['vis'][$id]), (int)$_POST['page'][$id], $id));
		}
		$db->commit();
		unset($q,$m,$_POST);

		#Odbuduj menu
		require './admin/inc/mcache.php';
		RenderMenu();
	}
	catch(PDOExtension $e)
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
$content->data['blocks'] =& $blocks;