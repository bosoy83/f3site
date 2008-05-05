<?php
if(CONTENT!=1) exit;

#Prawa
//CatPath(1, $lang['imgs'], ((Admit('I'))?'<img src="img/icon/edit.png" alt="E" />
//<a href="?co=edit&amp;act=img&amp;id='.$id.'">'.$lang['edit'].'</a>':''));

#Rozm.
$xs=explode('||',$content['size']);

#Ocena
$rate=''; //PӏNIEJ!!!!!!!

#Typ
if($content['type']==1)
{
	$img='<a href="'.$content['file'].'"><img src="'.$content['file'].'" alt="[IMAGE]"'.(($xs[0])?' style="width: '.$xs[0].'px; height: '.$xs[1].'px"':'').'" /></a>';
}
else
{
	include('./lib/movie.php');
}

$content['dsc']=nl2br($content['dsc']);
$date=genDate($content['date']);

require(VIEW_DIR.'img.php');

unset($content,$img,$rate,$date,$xs);

#Komentarze
if($cfg['icomm']==1 && $cat['opt']&2)
{
	define('CT','3');
	require('lib/comm.php');
}
?>
