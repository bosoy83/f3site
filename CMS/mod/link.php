<?php /* Wy¶wietl szczegó³y o pliku */
if(iCMS!=1) exit;
require './cfg/content.php';

#Gdy pe³ny przegl±d wy³±czony
if(!isset($cfg['linkFull'])) return;

#Pobierz dane
if(!$link = $db->query('SELECT l.*,c.opt FROM '.PRE.'links l INNER JOIN '.PRE.'cats c
	ON l.cat=c.ID WHERE l.access=1 AND c.access!=3 AND l.ID='.$id)->fetch(2)) return;

#Tytu³ strony
$content->title = $link['name'];

#Ocena
if(isset($cfg['lrate']) AND $link['opt'] & 4)
{
	$content->addCSS(SKIN_DIR.'rate.css');
	$rate = 'vote.php?type=4&amp;id='.$id;
}
else
{
	$rate = 0;
}

#Do szablonu
$content->data = array(
	'link' => &$link,
	'rates'=> &$rate,
	'edit' => admit($link['cat'],'CAT') ? url('edit/4/'.$id,'ref') : false,
	'path' => catPath($link['cat']),
	'cats' => url('cats/links')
);

#Komentarze
if($link['opt'] & 2)
{
	require './lib/comm.php';
	comments($id, 4);
}