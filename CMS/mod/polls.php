<?php
if(iCMS!=1) exit;

#Tytu³ strony
$content->title = $lang['archive'];

$res = $db->query('SELECT ID,name,num,date FROM '.PRE.'polls WHERE access="'.$nlang.'" ORDER BY ID DESC');
$res->setFetchMode(3);

#Tu zapisuj
$poll = array();
$lp = 0;

foreach($res as $p)
{
	$poll[] = array(
		'title' => $p[1],
		'url'   => '?co=poll&amp;id='.$p[0],
		'date'  => genDate($p[3]),
		'votes' => $p[2],
		'num'   => ++$lp
	);
}

#Dane LUB brak?
if($lp > 0)
	$content->data['poll'] =& $poll;
else
	$content->info($lang['noc']);

unset($res,$poll,$lp);
