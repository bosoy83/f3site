<?php
if(iCMSa!=1 || !Admit('IP')) exit;
require LANG_DIR.'adm_o.php';

#Usuñ / w³¹cz / wy³¹cz
if($_POST && $x = GetID(true))
{
	if(isset($_POST['del']))
	{
		$db->exec('DELETE FROM '.PRE.'pages WHERE ID IN('.$x.')');
	}
}

#Info
$content->info($lang['pinfo'], array('adm.php?a=editPage'=>$lang['addp']));

#Odczyt
$res = $db->query('SELECT ID,name,access FROM '.PRE.'pages ORDER BY ID DESC');
$res->setFetchMode(3); //NUM

$total = 0;
$pages = array();

foreach($res as $page)
{
	$pages[] = array(
		'id'  => $page[0],
		'num' => ++$total,
		'url' => MOD_REWRITE ? '/page/'.$page[0] : 'index.php?co=page&amp;id='.$page[0],
		'title' => $page[1],
		'access'=> $page[2]!=2 ? $lang['on2'] : $lang['off2']
	);
}

$res = null;
$content->data['pages'] = $pages;
$content->title = $lang['ipages'];