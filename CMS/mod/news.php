<?php /* Pe�ny news */
if(iCMS!=1) exit;
require './cfg/content.php';

#Pobierz dane
if($id)
{
	$res = $db->query('SELECT n.*,c.opt as catOpt FROM '.PRE.'news n LEFT JOIN '.
	PRE.'cats c ON n.cat=c.ID WHERE n.access=1 AND c.access!=3 AND n.ID='.$id);

	#Do tablicy
	if(!$news = $res->fetch(2)) return;
}
else return;

#Pe�na tre��
if($news['opt']&4)
{
	$full = $db->query('SELECT text FROM '.PRE.'newstxt WHERE ID='.$id)->fetchColumn();
}
else
{
	$full = '';
}

#Tytu� strony LUB brak newsa?
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
	'cats' => url('cats/news')
);

#Komentarze
if(isset($cfg['ncomm']) && $news['catOpt']&2)
{
	require './lib/comm.php';
	comments($id, 5);
}