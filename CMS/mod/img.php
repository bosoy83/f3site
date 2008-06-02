<?php
if(iCMS!=1) exit;
require './cfg/content.php';

#Pobierz dane
$res = $db->query('SELECT i.*,c.opt as catOpt FROM '.PRE.'imgs i LEFT JOIN '.
	PRE.'cats c ON i.cat=c.ID WHERE i.access=1 AND c.access!=3 AND i.ID='.$id);

#Do tablicy
if(!$img = $res->fetch(2)) return;  $res = null;

#Rozm.
$size = strpos($img['size'], '||') ? explode('||', $img['size']) : null;

#Animacja?
if($img['type'] !== 1)
{
	include './lib/movie.php';
	if($img['type'] == 2)
	{
		$movie = Flash($img['file'], $size[0], $size[1]);
	}
	else
	{
		$movie = Movie($img['file'], $size[0], $size[1]);
	}
}

#Opis, data, autor
$img['dsc'] = nl2br($img['dsc']);
$img['date'] = genDate($img['date']);
$img['author'] = Autor($img['author']);

#Mo¿e edytowaæ?
$img['edit'] = Admit($img['cat'],'CAT') ? '?co=edit&amp;act=img&amp;id='.$id : false;

#Komentarze
if($cfg['icomm']==1 && $img['catOpt']&2)
{
	define('CT','3');
	//require('lib/comm.php');
}

#Do szablonu
$content->data = array(
	'img'  => &$img,
	'size' => &$size,
	'movie'=> $img['type'] === 1 ? true : false,
	'path' => CatPath($img['cat']),
);