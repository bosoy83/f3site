<?php
if(iCMS!=1) exit;
require './cfg/content.php';

#Get data
if(!$img = $db->query('SELECT i.*,c.opt FROM '.PRE.'imgs i INNER JOIN '.
PRE.'cats c ON i.cat=c.ID WHERE c.access!=3 AND i.ID='.$id)->fetch(2)) return;

#Disabled
if(!$img['access'])
{
	if(!admit($img['cat'],'CAT')) return;
	$content->info(sprintf($lang['NVAL'], $img['name']), null, 'warning');
}

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
	$rates = 'vote.php?type=3&amp;id='.$id;
}
else
{
	$rates = 0;
}

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
	'edit' => admit($img['cat'],'CAT') ? url('edit/3/'.$id,'ref') : false,
	'cats' => url('cats/images'),
	'rates'=> $rates
);

#Tagi
if(isset($cfg['tags']))
{
	include './lib/tags.php';
	tags($id, 3);
}

#Komentarze
if(isset($cfg['icomm']) && $img['opt']&2)
{
	require 'lib/comm.php';
	comments($id, 3);
}