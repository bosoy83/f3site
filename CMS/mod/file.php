<?php /* Wyœwietl szczegó³y o pliku */
if(iCMS!=1) exit;
require './cfg/content.php';

#Pobierz dane
if($id)
{
	$res = $db->query('SELECT f.*,c.opt FROM '.PRE.'files f INNER JOIN '.PRE.'cats c
	ON f.cat=c.ID WHERE f.access=1 AND c.access!=3 AND f.ID='.$id);

	#Do tablicy
	if(!$file = $res->fetch(2)) return;
}
else return;

#Tytu³ strony
$content->title = $file['name'];

#Zdalny
$remote = strpos($file['file'], ':');

#Rozmiar i URL
if($remote OR file_exists('./'.$file['file']))
{
	$file['url']  = isset($cfg['fgets']) ? 'go.php?file='.$file['ID'] : $file['file'];
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
$file['edit'] = admit($file['cat'],'CAT') ? url('edit/2/'.$file['ID']) : false;

#Ocena
if(isset($cfg['frate']) AND $file['opt'] & 4)
{
	$content->addCSS(SKIN_DIR.'rate.css');
	$rate = 'vote.php?type=2&amp;id='.$file['ID'];
}
else
{
	$rate = 0;
}

#Data, autor
$file['date'] = genDate($file['date'], true);
$file['author'] = autor($file['author']);

#Do szablonu
$content->data = array(
	'file' => &$file,
	'path' => catPath($file['cat']),
	'rates'=> $rate,
	'cats' => url('cats/files')
);

#Komentarze
if(isset($cfg['fcomm']) && $file['opt']&2)
{
	require './lib/comm.php';
	comments($file['ID'], 2);
}