<?php
if(iCMS!=1) exit;
require './cfg/content.php';

#Pobierz dane
$res = $db->query('SELECT i.*,c.opt FROM '.PRE.'imgs i LEFT JOIN '.PRE.
	'cats c ON i.cat=c.ID WHERE i.access=1 AND c.access!=3 AND i.ID='.$id);

#Do tablicy
if(!$img = $res->fetch(2)) return;  $res = null;

#Rozm.
$size = strpos($img['size'], '|') ? explode('|', $img['size']) : null;

#Opis, data, autor
$img['dsc'] = nl2br($img['dsc']);
$img['date'] = genDate($img['date'], true);
$img['author'] = Autor($img['author']);

#Ocena
if(isset($cfg['irate']) AND $img['opt'] & 4)
{
	$content->addCSS(SKIN_DIR.'rate.css');
	$rates = 'vote.php?type=3&amp;id='.$id;
}
else
{
	$rates = 0;
}

#Mo¿e edytowaæ?
$img['edit'] = Admit($img['cat'],'CAT') ? '?co=edit&amp;act=3&amp;id='.$id : false;

#Tytu³
$content->title = $img['name'];

#Do szablonu
$content->data = array(
	'img'  => &$img,
	'size' => &$size,
	'image'=> $img['type'] === '1' ? true : false,
	'flash'=> $img['type'] === '2' ? true : false,
	'movie'=> $img['type'] === '3' ? true : false,
	'video'=> $img['type'] === '4' ? true : false,
	'path' => CatPath($img['cat']),
	'rates'=> $rates
);

#Komentarze
if(isset($cfg['icomm']) && $img['opt']&2)
{
	define('CT','3');
	require 'lib/comm.php';
}