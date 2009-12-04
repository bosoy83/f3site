<?php
if(iCMSa!=1 || !admit('R')) exit;
require LANG_DIR.'admAll.php';

#Aktualizuj lub usuń kanały
if($_POST && $x = GetID(true))
{
	if(isset($_POST['del']))
	{
		$db->exec('DELETE FROM '.PRE.'rss WHERE ID IN ('.$x.')');
	}
}

#Info i linki
$content->info($lang['infoRss'], array(url('editRss','','admin') => $lang['addRss']));

#Pobierz kanały RSS
$res = $db->query('SELECT ID,auto,name FROM '.PRE.'rss ORDER BY name');
$all = array();

foreach($res as $x)
{
	$all[] = array(
		'id'    => $x['ID'],
		'title' => $x['name'],
		'auto'  => $x['auto'] ? $lang['yes'] : $lang['no'],
		'edit'  => url('editRSS/'.$x['ID'], '', 'admin'),
		'file'  => file_exists('rss/'.$x['ID'].'.xml') ? 'rss/'.$x['ID'].'.xml' : null,
	);
}

#Szablon
$content->data = array('channel' => &$all);