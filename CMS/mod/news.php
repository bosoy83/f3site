<?php
if(iCMS!=1) exit;
require './cfg/content.php';

#Get record
if(!$news = $db->query('SELECT n.*,c.opt as catOpt FROM '.PRE.'news n LEFT JOIN '.
PRE.'cats c ON n.cat=c.ID WHERE c.access!=3 AND n.ID='.$id) -> fetch(2)) return;

#Disabled
if(!$news['access'])
{
	if(!admit($news['cat'],'CAT')) return;
	$content->info(sprintf($lang['NVAL'], $news['name']), null, 'warning');
}

#Pe³na treœæ
if($news['opt']&4)
{
	$full = $db->query('SELECT text FROM '.PRE.'newstxt WHERE ID='.$id)->fetchColumn();
}
else
{
	$full = '';
}

#Tytu³ strony LUB brak newsa?
$content->title = $news['name'];

#Emoty
if($news['opt']&2)
{
	$news['txt'] = emots($news['txt']);
	if($full) $full = emots($full);
}

#Linie
if($news['opt']&1)
{
	$news['txt'] = nl2br($news['txt']);
	if($full) $full = nl2br($full);
}

#Data, autor
$news['date']  = genDate($news['date'], true);
$news['wrote'] = autor($news['author']);

#Do szablonu
$content->data = array(
	'news' => &$news,
	'full' => &$full,
	'path' => catPath($news['cat']),
	'edit' => admit($news['cat'],'CAT') ? url('edit/5/'.$id,'ref') : false,
	'root' => isset($cfg['allCat']) ? $lang['cats'] : $lang['news'],
	'cats' => url(isset($cfg['allCat']) ? 'cats' : 'cats/news')
);

#Tagi
if(isset($cfg['tags']))
{
	include './lib/tags.php';
	tags($id, 5);
}

#Komentarze
if(isset($cfg['ncomm']) && $news['catOpt']&2)
{
	require './lib/comm.php';
	comments($id, 5);
}