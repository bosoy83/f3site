<?php /* Wy�wietl szczeg�y o pliku */
if(iCMS!=1) return;
require './cfg/c.php';

#Pobierz dane
$res = $db->query('SELECT f.*,c.opt FROM '.PRE.'files f INNER JOIN '.PRE.'cats c
	ON f.cat=c.ID WHERE f.ID='.$id.' AND f.access=1 AND c.access!=3');

#Do tablicy
if(!$file = $res->fetch(2)) return;

#Rozmiar i URL
if(file_exists('./'.$file['file']))
{
	$url = $cfg['fcdl']==1 ? 'go.php?file='.$id : $file['file'];
}
else
{
	$file['size'] = $lang['nof'];
	$url = '#';
}

#EditURL
$file['edit'] = Admit($file['cat'],'CAT') ? '?co=edit&amp;act=file&amp;id='.$id : false;

#Ocena
$file['rate']=''; //POTEM!!!!!

#Data, autor
$file['date'] = genDate($file['date']);
$file['author'] = Autor($file['author']);

#Komentarze
if($cfg['fcomm']==1 && $file['opt']&2)
{
	define('CT','2');
	//require('./lib/comm.php');
}

#Do szablonu
$content->data = array(
	'file' => &$file,
	'url'  => $url,
	'path' => CatPath($file['cat']),
	'cats_url' => MOD_REWRITE ? '/cats/2' : '?co=cats&amp;id=2'
);
?>
