<?php
if(iCMSa!=1 || !Admit('LOG')) exit;

#Usuñ?
if($_POST && $x = GetID(true))
{
	$db->exec('DELETE FROM '.PRE.'log WHERE ID IN ('.$x.')');
}

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
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
$total = db_count('log');
$event = array();

#Pobierz
$res = $db->query('SELECT l.*,u.login FROM '.PRE.'log l LEFT JOIN '.PRE.'users u
	ON l.user=u.ID AND l.user!=0 LIMIT '.$st.',30');
$res->setFetchMode(3); //Assoc

#Lista
foreach($res as $i)
{
	$event[] = array(
		'id'   => $i[0],
		'text' => $i[1],
		'date' => genDate($i[2]),
		'login'=> $i[5],
		'ip'   => $i[3],
		'user' => $i[4] ? '.?co=user&amp;id='.$i[4] : false
	);
}
$content->data = array(
	'event' => &$event,
	'pages' => Pages($page, $total, 30, '?a=log', 1),
	'url'   => '?a=log&amp;page='.$page
);
