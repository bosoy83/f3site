<?php
if(iCMSa!=1 || !Admit('NM')) exit;
require LANG_DIR.'adm_o.php';

#Zapis bloków
if($_POST)
{
	$ile=count($_POST['m_s']);
	for($i=1;$i<=$ile;++$i)
	{
		$db->exec('UPDATE '.PRE.'menu SET seq='.(int)$_POST['m_s'][$i].',
			disp='.$db->quote($_POST['m_vis'][$i]).', menu='.(int)$_POST['m_page'][$i].'
			WHERE ID='.(int)$_POST['m_id'][$i]);
	}
	#Odbuduj menu
	require './admin/inc/mcache.php';
	RenderMenu();
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
		'title' => $m[2],
		'page'  => $m[4]
	);
}

#Do szablonu
$content->data['blocks'] =& $blocks;