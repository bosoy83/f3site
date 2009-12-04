<?php
if(iCMS!=1) exit;

#Tytu³ strony
$content->title = $lang['archive'];

$res = $db->query('SELECT ID,name,num,date FROM '.PRE.'polls WHERE access="'.$nlang.'" ORDER BY ID DESC');
$res->setFetchMode(3);

#Tu zapisuj
$poll = array();
$num = 0;

foreach($res as $p)
{
	$poll[] = array(
		'title' => $p[1],
		'url'   => url('poll/'.$p[0]),
		'date'  => genDate($p[3]),
		'votes' => $p[2],
		'num'   => ++$num
	);
}

#Dane LUB brak?
if($num > 0)
	$content->data = array('poll' => &$poll);
else
	$content->info($lang['noc']);

unset($res,$poll,$lp);