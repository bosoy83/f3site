<?php /* Pe³ny news */
if(iCMS!=1) exit;
require './cfg/content.php';

#Pobierz dane
$res = $db->query('SELECT n.*,c.opt as catOpt FROM '.PRE.'news n LEFT JOIN '.
	PRE.'cats c ON n.cat=c.ID WHERE n.access=1 AND c.access!=3 AND n.ID='.$id);

#Do tablicy
if(!$news = $res->fetch(2)) return;  $res = null;

#Pe³na treœæ
if($news['opt']&4)
{
	$full = $db->query('SELECT text FROM '.PRE.'fnews WHERE ID='.$id) -> fetchColumn();
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
	$news['txt'] = Emots($news['txt']);
	if($full) $full = Emots($full);
}

#Linie
if($news['opt']&1)
{
	$news['txt'] = nl2br($news['txt']);
	if($full) $full = nl2br($full);
}

#Data, autor
$news['date']  = genDate($news['date'], true);
$news['wrote'] = Autor($news['author']);

#EditURL
$news['edit'] = Admit($news['cat'],'CAT') ? '?co=edit&amp;act=5&amp;id='.$id : false;

#Do szablonu
$content->data = array(
	'news' => &$news,
	'full' => &$full,
	'path' => CatPath($news['cat'])
);

#Komentarze
if(isset($cfg['ncomm']) && $news['catOpt']&2)
{
	define('CT','5');
	require './lib/comm.php';
}