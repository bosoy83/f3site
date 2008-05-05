<?php /* Pobierz plik, przejdü do adresu... - ze zliczaniem */
require('./kernel.php');
require('./cfg/content.php');

$mod = key($_GET);
$id  = (int)$_GET[$mod];

#LINK
if($mod === 'link')
{
	$link = $db->query('SELECT l.cat,l.adr FROM '.PRE.'links l LEFT JOIN '.PRE.'cats c ON l.cat=c.ID WHERE l.access=1 AND c.access!=3 AND l.ID='.$id) -> fetch(3); //NUM

	if(!$link) $content->message($lang['noex']);

	#Zlicz wejúcie
	if(isset($cfg['lcnt'])) $db->exec('UPDATE '.PRE.'links SET count=count+1 WHERE ID='.$id);

	#Przejdü do URL
	header('Location: '.str_replace('&amp;','&',$link[1]));
	echo '<script type="text/javascript">location="'.$link[1].'"</script>';
}
exit;