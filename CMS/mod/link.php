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

#Do szablonu
$content->data = array(
	'link' => &$link,
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