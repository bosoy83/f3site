<?php
if(iCMS!=1) exit;
require './cfg/content.php';

#Pobierz dane
if($id)
{
	$res = $db->query('SELECT i.*,c.opt FROM '.PRE.'imgs i LEFT JOIN '.PRE.
	'cats c ON i.cat=c.ID WHERE i.access=1 AND c.access!=3 AND i.ID='.$id);

	#Do tablicy
	if(!$img = $res->fetch(2)) return;  $res = null;
}
else return;

#Rozm.
$size = strpos($img['size'], '|') ? explode('|', $img['size']) : null;

#Opis, data, autor
$img['dsc'] = nl2br($img['dsc']);
$img['date'] = genDate($img['date'], true);
$img['author'] = autor($img['author']);

#Ocena
if(isset($cfg['irate']) AND $img['opt'] & 4)
{
	$content->addCSS(SKIN_DIR.'rate.css');
	$rates = 'vote.php?type=3&amp;id='.$img['ID'];
}
else
{
	$rates = 0;
}

#Mo¿e edytowaæ?
$img['edit'] = admit($img['cat'],'CAT') ? url('edit/3/'.$img['ID']) : false;

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
	'path' => catPath($img['cat']),
	'cats' => url('cats/images'),
	'rates'=> $rates
);

#Komentarze
if(isset($cfg['icomm']) && $img['opt']&2)
{
	require 'lib/comm.php';
	comments($img['ID'], 3);
}