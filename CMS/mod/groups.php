<?php /* Grupy u¿ytkowników */
if(iCMS!=1) exit;

#Tytu³ strony
$content->title = $lang['groups'];

#Pobierz
$res = $db->query('SELECT ID,name,dsc,opened FROM '.PRE.'groups WHERE access=1 OR access="'.LANG.'"');
$res -> setFetchMode(3);
$gro = array();
$may = array();

foreach($res as $x)
{
	$gro[] = array(
		'title' => $x[1],
		'desc'  => nl2br($x[2]),
		'url'   => url('group/'.$x[0])
	);
}

#Do szablonu
$content->data = array('groups' => &$gro);
