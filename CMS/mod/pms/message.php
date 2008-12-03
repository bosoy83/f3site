<?php /* PM - wyœwietl wiadomoœæ */
if(iCMS!=1) exit;

#Odczyt
$res = $db->query('SELECT * FROM '.PRE.'pms WHERE (owner='.UID.' OR (usr='.UID.' AND st=1)) AND ID='.$id);
$pm = $res->fetch(2); //ASSOC
$res = null;

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
	$db->exec('UPDATE '.PRE.'pms SET st=2 WHERE ID='.$id);
	$db->exec('UPDATE '.PRE.'users SET pms=pms-1 WHERE ID='.$pm['owner']);
	-- $user[UID]['pms'];
	$pm['st'] = 2;
}

#Data, autor
$pm['date'] = genDate($pm['date'], true);
$pm['usr'] = Autor($pm['usr']);

#Tytu³ strony i plik
$content->title = $pm['topic'];
$content->file[] = 'pms_view';

#Do szablonu
$content->data += array(
	'pm'   => &$pm,
	'id'   => $id,
	'edit' => $pm['st'] == 3 ? '?co=pms&amp;act=e&amp;id='.$id : null,
	'reply'=> $pm['st'] == 2 ? '?co=pms&amp;act=e&amp;id='.$id : null,
	'fwd'  => '?co=pms&amp;act=e&amp;fwd&amp;id='.$id
);