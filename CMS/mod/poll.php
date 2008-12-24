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

#Data utworzenia sondy
$poll['date'] = genDate($poll['date'], true);

#Procenty
$item = array();
foreach($option as &$o)
{
	$item[] = array(
		'num'  => $o[2],
		'label' => $o[1],
		'percent' => round($o[2] / $poll['num'] * 100 ,$cfg['pollRound'])
	);
}

#Szablon
$content->data = array(
	'poll' => &$poll,
	'item' => &$item
);

#Komentarze
if(isset($cfg['pollComm']))
{
	define('CT','15');
	include './lib/comm.php';
}