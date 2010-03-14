<?php
if(iCMS!=1) exit;

#Get record
if(!$page = $db->query('SELECT * FROM '.PRE.'pages WHERE ID='.$id)->fetch(2)) return;

#Rights
$edit = admit('P');

#Unavailable (0) or for logged in (3)
if($page['access'] != 1)
{
	if(!$page['access'])
	{
		if(!$edit) return;
		$content->info(sprintf($lang['NVAL'], $page['name']), null, 'warning');
	}
	elseif(!UID) return;
}

#PHP - nale¿y wykonaæ go najpierw
if($page['opt'] & 16)
{
	ob_start();
	eval('?>'.$page['text']);
	$page['text'] = ob_get_clean();
}

#Emotikony
if($page['opt'] & 2)
{
	$page['text'] = emots($page['text']);
}

#BR
if($page['opt'] & 1)
{
	$page['text'] = nl2br($page['text']);
}

#Szablon
$content->title = $page['name'];
$content->data = array(
	'page' => &$page,
	'box'  => $page['opt'] & 4,
	'all'  => $edit ? url('pages','','admin') : false,
	'edit' => $edit ? url('editPage/'.$id, 'ref', 'admin') : false
);

#S³owa kluczowe
if(isset($cfg['tags']))
{
	include './lib/tags.php';
	tags($id, 59);
}

#Komentarze
if($page['opt'] & 8)
{
	require './lib/comm.php';
	comments($id, 59);
}