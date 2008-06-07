<?php /* Wy¶wietlanie artyku³u */
if(iCMS!=1) exit;

#Pobierz dane
$res = $db->query('SELECT t.*,f.text,f.page,f.opt,c.opt as cat_opt FROM '.PRE.'arts t
	INNER JOIN '.PRE.'artstxt f ON t.ID=f.ID INNER JOIN '.PRE.'cats c ON t.cat=c.ID
	WHERE t.ID='.$id.' AND t.access=1 AND c.access!=3 AND f.page='.
	((isset($_GET['page'])) ? (int)$_GET['page'] : 1));

#Do tablicy
if(!$art = $res->fetch(2)) return;

#Tytu³ strony
$content->title = $art['name'];

#Emoty
if($art['opt']&2)
{
	$art['text'] = Emots($art['text']);
}
#BR
if($art['opt']&1)
{
	$art['text'] = nl2br($art['text']);
}

#Data,autor
$art['date'] = genDate($art['date']);
$art['author'] = Autor($art['author']);

#Ocena
$art['rate_url'] = '';

#Zwiêksz ilo¶æ wy¶wietleñ
if(isset($cfg['adisp']))
{
	register_shutdown_function(array($db,'exec'),'UPDATE '.PRE.'arts SET ent=ent+1 WHERE ID='.$id);
	++$art['ent'];
}

#Strony
$pages = $art['pages']>1 ? Pages($art['page'],$art['pages'],1,'?co=art&amp;id='.$id) : null;

#EditURL
$art['edit'] = Admit($art['cat'],'CAT') ? '?co=edit&amp;act=art&amp;id='.$id : false;

#Do szablonu
$content->data = array(
	'art'  => &$art,
	'pages'=> &$pages,
	'path' => CatPath($art['cat'])
);

#Komentarze
if(isset($cfg['acomm']) && $art['cat_opt']&2)
{
	define('CT','1');
	require './lib/comm.php';
}