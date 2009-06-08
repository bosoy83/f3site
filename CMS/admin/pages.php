<?php
if(iCMSa!=1 || !Admit('P')) exit;
require LANG_DIR.'admAll.php';

#Usuñ / w³¹cz / wy³¹cz
if($_POST && $x = GetID(true))
{
	if(isset($_POST['del']))
	{
		$db->beginTransaction();
		$db->exec('DELETE FROM '.PRE.'pages WHERE ID IN('.$x.')');
		$db->exec('DELETE FROM '.PRE.'comms WHERE TYPE=59 AND CID IN('.$x.')');
		$db->commit();
	}
}

#Info
$content->info($lang['pageTip'], array('?a=editPage'=>$lang['addPage']));

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
		'url' => '.?co=page&amp;id='.$page[0],
		'title' => $page[1],
		'access'=> $page[2]!=2 ? $lang['on2'] : $lang['off2']
	);
}

$res = null;
$content->data['pages'] = $pages;
$content->title = $lang['ipages'];
