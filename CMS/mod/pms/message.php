<?php /* PM - wyœwietl wiadomoœæ */
if(iCMS!=1) exit;

#Odczyt
$res = $db->query('SELECT * FROM '.PRE.'pms WHERE (owner='.UID.' OR (usr='.UID.' AND st=1)) AND ID='.$id);
$pm = $res->fetch(2); //ASSOC
$res = null;

#Brak?
if(!$pm)
{
	$content->info($lang['pms_9']);
	return 1;
}

#BBCode
if($pm['bbc']==1 && $cfg['bbc']==1)
{
	require('./lib/bbcode.php');
	$pm['txt'] = ParseBBC($pm['txt']);
}

#Treœæ - emoty
$pm['txt'] = nl2br(Emots($pm['txt']));

#Przeczytana?
if($pm['st']==1 && $pm['owner']==UID)
{
	$db -> exec('UPDATE '.PRE.'pms SET st=2 WHERE ID='.$pm['ID']);
	$db -> exec('UPDATE '.PRE.'users SET pms=pms-1 WHERE ID='.$pm['owner']);
	-- $user[UID]['pms'];
	-- $_SESSION['userdata']['pms'];
	$pm['st'] = 2;
}

#Data, autor
$pm['date'] = genDate($pm['date']);
$pm['usr'] = Autor($pm['usr']);

#Tytu³ strony
$content->title = $pm['topic'];

#Do szablonu
$content->data += array(
	'pm'   => &$pm,
	'file' => 'pms_view.php',
	'id'   => $id,
	'edit' => ($pm['st']==2) ? $lang['pm_10'] : $lang['edit']
);
?>