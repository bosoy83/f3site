<?php /* Wyœwietl szczegó³y o pliku */
if(iCMS!=1) return;
require './cfg/content.php';

#Pobierz dane
$res = $db->query('SELECT f.*,c.opt FROM '.PRE.'files f INNER JOIN '.PRE.'cats c
	ON f.cat=c.ID WHERE f.ID='.$id.' AND f.access=1 AND c.access!=3');

#Do tablicy
if(!$file = $res->fetch(2)) return;

#Tytu³ strony
$content->title = $file['name'];

#Zdalny
$remote = strpos($file['file'], ':');

#Rozmiar i URL
if($remote OR file_exists('./'.$file['file']))
{
	$file['url']  = isset($cfg['fgets']) ? 'go.php?file='.$id : $file['file'];
	if(!$file['size'] && !$remote)
	{
		$size = filesize($file['file']);
		if($file['size'] > 1048575)
		{
			$file['size'] = round($size/1048576) . ' MB';
		}
		else
		{
			$file['size'] = round($size/1024) . ' KB';
		}
	}
}
else
{
	$file['size'] = 'File not found';
	$file['url'] = '#';
}

#EditURL
$file['edit'] = Admit($file['cat'],'CAT') ? '?co=edit&amp;act=2&amp;id='.$id : false;

#Ocena
if(isset($cfg['frate']) AND $file['opt'] & 4)
{
	$content->addCSS(SKIN_DIR.'rate.css');
	$rate = 'vote.php?type=2&amp;id='.$id;
}
else
{
	$rate = 0;
}

#Data, autor
$file['date'] = genDate($file['date'], true);
$file['author'] = Autor($file['author']);

#Do szablonu
$content->data = array(
	'file' => &$file,
	'path' => CatPath($file['cat']),
	'rates' => $rate,
	'cats_url' => '?co=cats&amp;id=2'
);

#Komentarze
if(isset($cfg['fcomm']) && $file['opt']&2)
{
	define('CT','2');
	require './lib/comm.php';
}