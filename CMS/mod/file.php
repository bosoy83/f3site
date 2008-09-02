<?php /* Wyœwietl szczegó³y o pliku */
if(iCMS!=1) return;
require './cfg/content.php';

#Pobierz dane
$res = $db->query('SELECT f.*,c.opt FROM '.PRE.'files f INNER JOIN '.PRE.'cats c
	ON f.cat=c.ID WHERE f.ID='.$id.' AND f.access=1 AND c.access!=3');

#Do tablicy
if(!$file = $res->fetch(2)) return;

#Rozmiar i URL
if(file_exists('./'.$file['file']))
{
	$file['url'] = $cfg['fgets']==1 ? 'go.php?file='.$id : $file['file'];
}
else
{
	$file['size'] = $lang['nof'];
	$file['url'] = '#';
}

#EditURL
$file['edit'] = Admit($file['cat'],'CAT') ? '?co=edit&amp;act=file&amp;id='.$id : false;

#Ocena
$file['rate']=''; //POTEM!!!!!
$content->addCSS(SKIN_DIR.'rate.css');

#Data, autor
$file['date'] = genDate($file['date'], true);
$file['author'] = Autor($file['author']);

#Do szablonu
$content->data = array(
	'file' => &$file,
	'path' => CatPath($file['cat']),
	'cats_url' => MOD_REWRITE ? '/cats/2' : '?co=cats&amp;id=2'
);

#Komentarze
if($cfg['fcomm']==1 && $file['opt']&2)
{
	define('CT','2');
	require './lib/comm.php';
}