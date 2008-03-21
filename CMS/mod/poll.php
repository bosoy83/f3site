<?php /* Wyniki sondy */
if(iCMS!=1) exit;

#Pobierz
$poll = $db->query('SELECT * FROM '.PRE.'polls WHERE ID='.$id) -> fetch(2);

#Brak?
if(!$poll)
{
	return;
}

#G³osowa³? -> wyniki

/* G³osowanie na inne ankiety - potem

#G³osowa³ na...
$voted=isset($_COOKIE['voted'] && !isset($_COOKIE['voted'][51]))?unserialize($_COOKIE['voted']):array();

if(in_array($id,$x))
{
	
} */

#Bez g³osów?
elseif($poll['num'] == 0)
{
	$content->info($lang['novotes']);
	return 1;
}

#Tytu³ strony
$content->title = $poll['name'];

#Odpowiedzi
$option=$db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$poll['ID'].' ORDER BY seq')->fetchAll(3);

#Ile?
$ile = count($option);

/*$max = $option[0][2];
for($i=1;$i<$ile;++$i)
{
	if($option[$i][2]>$max) $max=$option[$i][2];
}*/

for($i=0; $i<$ile; ++$i)
{
	$pollproc[$i] = round($option[$i][2] / $poll['ID'] * 100 ,$cfg['cproc']);
}

#Szablon
$content->file = './mod/polls/'.$cfg['pollr1'].'.php';

#Komentarze
if($cfg['pcomm']==1)
{
	define('CT','15');
	//include './lib/comm.php';
}
?>
