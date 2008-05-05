<?php
if(iCMS!=1) exit;

$res = $db->query('SELECT ID,name,num,date FROM '.PRE.'polls WHERE access="'.$nlang.'" ORDER BY ID DESC');
$res->setFetchMode(3);

#Tu zapisuj
$polls[] = array();
$lp = 0;

foreach($res as $p)
{
	$polls[] = array(
		'title' => $p[1],
		'url'   => '?co=poll&amp;id='.$p[1],
		'date'  => genDate($p[3]),
		'votes' => $p[2],
		'lp'    => ++$lp
	);
}

#Brak?
if($lp === 0) $content->info($lang['noc']);

unset($res,$poll,$lp);
?>
