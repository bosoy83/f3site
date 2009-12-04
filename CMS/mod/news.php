<?php /* Pe³ny news */
if(iCMS!=1) exit;
require './cfg/content.php';

#Pobierz dane
if(isset($URL[1]) && is_numeric($URL[1]))
{
	$res = $db->query('SELECT n.*,c.opt as catOpt FROM '.PRE.'news n LEFT JOIN '.
	PRE.'cats c ON n.cat=c.ID WHERE n.access=1 AND c.access!=3 AND n.ID='.$URL[1]);

	#Do tablicy
	if(!$news = $res->fetch(2)) return;
}
else return;

#Pe³na treœæ
if($news['opt']&4)
{
	$full = $db->query('SELECT text FROM '.PRE.'newstxt WHERE ID='.$news['ID'])->fetchColumn();
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

#EditURL
$news['edit'] = admit($news['cat'],'CAT') ? url('edit/5/'.$news['ID']) : false;

#Do szablonu
$content->data = array(
	'news' => &$news,
	'full' => &$full,
	'path' => catPath($news['cat']),
	'cats' => url('cats/news')
);

#Komentarze
if(isset($cfg['ncomm']) && $news['catOpt']&2)
{
	require './lib/comm.php';
	comments($news['ID'], 5);
}