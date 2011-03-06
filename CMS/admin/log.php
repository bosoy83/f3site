<?php
if(iCMSa!=1 || !admit('L')) exit;

#Usun
if($_POST && $x = GetID(true))
{
	$db->exec('DELETE FROM '.PRE.'log WHERE ID IN ('.$x.')');
	event('ERASE');
}

#Strona
if(isset($_GET['page']) && $_GET['page']>1)
{
	$page = $_GET['page'];
	$st = ($page-1)*30;
}
else
{
	$page = 1;
	$st = 0;
}

#Suma
$total = dbCount('log');
$event = array();

#Pobierz
$res = $db->query('SELECT l.*,u.login FROM '.PRE.'log l LEFT JOIN '.PRE.'users u
	ON l.user=u.ID AND l.user!=0 LIMIT '.$st.',30');
$res->setFetchMode(3); //Assoc

require LANG_DIR.'events.php';

#Lista
foreach($res as $i)
{
	$event[] = array(
		'id'   => $i[0],
		'text' => isset($events[$i[1]]) ? $events[$i[1]] : $i[1],
		'date' => genDate($i[2], true),
		'login'=> $i[5],
		'ip'   => $i[3],
		'user' => $i[4] ? url('user/'.urlencode($i[5])) : false
	);
}
$content->data = array(
	'event' => &$event,
	'pages' => pages($page, $total, 30, url('log', '', 'admin'), 1),
	'url'   => url('log', 'page='.$page, 'admin')
);