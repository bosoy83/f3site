<?php /* Wyniki sondy */
if(iCMS!=1) exit;

#Pobierz
if(isset($URL[1]) && is_numeric($URL[1]))
{
	if(!$poll = $db->query('SELECT * FROM '.PRE.'polls WHERE ID='.$URL[1]) -> fetch(2)) return;
}
elseif(file_exists('./cache/poll_'.LANG))
{
	require('./cache/poll_'.LANG);
}
else return;

#Tytu³ strony
$content->title = $poll['name'];
$id = $poll['ID'];

#Bez g³osów?
if($poll['num'] == 0)
{
	$content->info($lang['novotes']);
	return 1;
}

#Odpowiedzi
if($id)
{
	$option = $db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$id.' ORDER BY seq')->fetchAll(3);
}

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
	'item' => &$item,
	'archive' => url('polls')
);

#Komentarze
if(isset($cfg['pollComm']))
{
	include './lib/comm.php';
	comments($id, 15);
}
