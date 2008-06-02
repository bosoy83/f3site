<?php /* Wyniki sondy */
if(iCMS!=1) exit;

#Pobierz
if($id):
	$poll = $db->query('SELECT * FROM '.PRE.'polls WHERE ID='.$id) -> fetch(2);
else:
	require('./cache/poll_'.$nlang);
	$id = $poll['ID'];
endif;

#Brak?
if(!$poll) return;

#Tytu³ strony
$content->title = $poll['name'];

#Bez g³osów?
if($poll['num'] == 0)
{
	$content->info($lang['novotes']);
	return;
}

#Odpowiedzi
if($id) $option=$db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$id)->fetchAll(3);

#Ile?
$num = count($option);

/*$max = $option[0][2];
for($i=1;$i<$ile;++$i)
{
	if($option[$i][2]>$max) $max=$option[$i][2];
}*/

#Procenty
$item = array();
foreach($option as &$o)
{
	$item[] = array(
		'num'  => $o[2],
		'label' => $o[1],
		'percent' => round($o[2] / $poll['ID'] * 100 ,$cfg['pollRound'])
	);
}

#Komentarze
if($cfg['pollComm']==1)
{
	define('CT','15');
	//include './lib/comm.php';
}

#Szablon
$content->data = array(
	'poll' => &$poll,
	'item' => &$item,
);
?>
