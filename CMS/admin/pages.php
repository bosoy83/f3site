<?php
if(iCMSa!=1 || !Admit('IP')) exit;
require LANG_DIR.'adm_o.php';

#Info
$content->info($lang['pinfo'], array('adm.php?a=editPage'=>$lang['addp']));

#Odczyt
$res=$db->query('SELECT ID,name,access FROM '.PRE.'pages ORDER BY ID DESC');
$res->setFetchMode(3); //NUM

$total = 0;
$pages = array();

foreach($res as $page)
{
	$pages[] = array(
		'id'  => $page[0],
		'num' => ++$total,
		'title' => $page[1],
		'access'=> $page[2]!=2 ? $lang['on2'] : $lang['off2']
	);
}

$res = null;
$content->data['pages'] =& $pages;
$content->title = $lang['ipages'];