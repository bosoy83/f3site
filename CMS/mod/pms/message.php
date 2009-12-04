<?php /* PM - wyœwietl wiadomoœæ */
if(iCMS!=1) exit;

#Odczyt
if(isset($URL[2]) && is_numeric($URL[2]))
{
	$q = $db->prepare('SELECT * FROM '.PRE.'pms WHERE (owner=? OR (usr=? AND st=1)) AND ID=?');
	$q->execute(array(UID, UID, $URL[2]));
	$pm = $q->fetch(2); //ASSOC
	$q = null;
}
else
{
	$pm = false;
}

#Brak?
if(!$pm)
{
	$content->info($lang['noex']);
	return 1;
}

#BBCode
if($pm['bbc']==1 && isset($cfg['bbcode']))
{
	require './lib/bbcode.php';
	$pm['txt'] = BBCode($pm['txt']);
}

#Treœæ - emoty
$pm['txt'] = nl2br(Emots($pm['txt']));

#Przeczytana?
if($pm['st']==1 && $pm['owner']==UID)
{
	$db->exec('UPDATE '.PRE.'pms SET st=2 WHERE ID='.$pm['ID']);
	$db->exec('UPDATE '.PRE.'users SET pms=pms-1 WHERE ID='.$pm['owner']);
	-- $user['pms'];
	$pm['st'] = 2;
}

#Data, autor
$pm['date'] = genDate($pm['date'], true);
$pm['usr'] = autor($pm['usr']);

#Tytu³ strony i plik
$content->title = $pm['topic'];
$content->file[] = 'pms_view';

#Do szablonu
$content->data += array(
	'pm'   => &$pm,
	'id'   => $pm['ID'],
	'edit' => $pm['st'] == 3 ? 'pms/edit/'.$pm['ID'] : null,
	'reply'=> $pm['st'] == 2 ? 'pms/edit/'.$pm['ID'] : null,
	'fwd'  => 'pms/edit/'.$pm['ID'].'?fwd'
);