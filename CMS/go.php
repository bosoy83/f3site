<?php /* Pobierz plik, przejd¼ do adresu... - ze zliczaniem */
define('iCMS',1);
require './kernel.php';
require './cfg/content.php';

$mod = key($_GET);
$id  = (int)$_GET[$mod];

#LINK
if($mod === 'link')
{
	$url = $db->query('SELECT l.adr FROM '.PRE.'links l LEFT JOIN '.PRE.'cats c ON l.cat=c.ID WHERE l.access=1 AND c.access!=3 AND l.ID='.$id) -> fetchColumn();

	if(!$url) $content->message($lang['noex']);

	#Zlicz wej¶cie
	if(isset($cfg['lcnt'])) $db->exec('UPDATE '.PRE.'links SET count=count+1 WHERE ID='.$id);

	#Przejd¼ do URL
	header('Location: '.str_replace('&amp;','&',$url));
	echo '<script type="text/javascript">location="'.$url.'"</script>';
}

#PLIK
elseif($mod === 'file')
{
	$file = $db->query('SELECT f.file FROM '.PRE.'files f LEFT JOIN '.PRE.'cats c ON f.cat=c.ID WHERE f.access=1 AND c.access!=3 AND f.ID='.$id) -> fetchColumn();

	if(!$file) $content->message($lang['noex']);

	#Zlicz pobranie
	if(isset($cfg['fgets'])) $db->exec('UPDATE '.PRE.'files SET dls=dls+1 WHERE ID='.$id);

	#Pobierz plik
	$file = str_replace('&amp;', '&', $file);
  header('Location: '.((strpos($file, ':')) ? $file : URL.$file));
}