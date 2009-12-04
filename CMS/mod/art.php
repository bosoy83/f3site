<?php /* Wy¶wietlanie artyku³u */
if(iCMS!=1) exit;
include './cfg/content.php';

#Pobierz dane
if(isset($URL[1]) && is_numeric($URL[1]))
{
	$res = $db->query('SELECT t.*,f.text,f.page,f.opt,c.opt as catOpt FROM '.PRE.'arts t
	INNER JOIN '.PRE.'artstxt f ON t.ID=f.ID INNER JOIN '.PRE.'cats c ON t.cat=c.ID
	WHERE t.ID='.$URL[1].' AND t.access=1 AND c.access!=3 AND f.page='.
	((isset($_GET['page'])) ? (int)$_GET['page'] : 1));

	#Do tablicy
	if(!$art = $res->fetch(2)) return;
}
else return;

#Tytu³ strony
$content->title = $art['name'];

#Emoty
if($art['opt']&2)
{
	$art['text'] = emots($art['text']);
}
#BR
if($art['opt']&1)
{
	$art['text'] = nl2br($art['text']);
}

#Data,autor
$art['date'] = genDate($art['date'], true);
$art['author'] = autor($art['author']);

#Ocena
if(isset($cfg['arate']) AND $art['catOpt'] & 4)
{
	$content->addCSS(SKIN_DIR.'rate.css');
	$rates = 'vote.php?type=1&amp;id='.$art['ID'];
}
else
{
	$rates = 0;
}

#Zwiêksz ilo¶æ wy¶wietleñ
if(isset($cfg['adisp']))
{
	register_shutdown_function(array($db,'exec'),'UPDATE '.PRE.'arts SET ent=ent+1 WHERE ID='.$art['ID']);
	++$art['ent'];
}
else
{
	$art['ent'] = 0;
}

#Strony
if($art['pages'] > 1)
{
	$pages = pages($art['page'],$art['pages'],1,url('art/'.$art['ID']));
}
else
{
	$pages = false;
}

#EditURL
$art['edit'] = admit($art['cat'],'CAT') ? url('edit/1/'.$art['ID']) : false;

#Do szablonu
$content->data = array(
	'art'  => &$art,
	'pages'=> &$pages,
	'path' => catPath($art['cat']),
	'cats'  => url('cats/articles'),
	'rates'=> $rates
);

#Komentarze
if(isset($cfg['acomm']) && $art['catOpt']&2)
{
	require './lib/comm.php';
	comments($art['ID'], 1);
}