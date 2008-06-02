<?php
if(iCMS!=1) exit;

#Pobierz
$page = $db->query('SELECT * FROM '.PRE.'pages WHERE ID='.$id.
	' AND (access=1'.((LOGD==1)?' OR access=3':'').')') -> fetch(2);

#Nie istnieje?
if(!$page) return;

#Emotikony
if($page['opt']&2)
{
	$page['text'] = Emots($page['text']);
}

#BR
if($page['opt']&1)
{
	$page['text'] = nl2br($page['text']);
}

#PHP?
if($page['opt']&16)
{
	ob_start();
	eval('?>'.$page['text'].'<?');
	$page['text'] = ob_get_clean();
}

#Komentarze
//if($page['opt']&8) { define('CT','59'); require('./lib/comm.php'); }

#Szablon
$content->title = $page['name'];
$content->data = array(
	'page' => &$page,
	'box'  => $page['opt'] & 4,
);