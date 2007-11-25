<?php
if(CONTENT!=1) exit;
require($catl.'files.php');

$data=array($content['name'],genDate($content['date']),nl2br($content['dsc']),
	Autor($content['author']),$content['dl'],$lang['dl'],$lang['author'],
	$lang['rate'],$lang['numofd'],$lang['size'],$lang['desc']);

#Rozmiar i URL
if($content['size']=='A')
{
	if(file_exists('./'.$content['file']))
	{
		$size=filesize('./'.$content['file']);
		if($size>=1048576) {
			$data[12]=round($size/1048576 ,2).' MB';
		}
		elseif($xsize>=1024) {
			$data[12]=round($size/1024 ,2).' KB';
		}
		else {
			$data[12]=$size.' B';
		}
		$data[13]=($cfg['fcdl']==1)?'getfile.php?id='.$id:$content['file'];
	}
	else
	{
		$data[12]=$lang['nof'];
		$data[13]='#';
	}
}
else
{
	$data[12]=$content['size'];
	$data[13]=($cfg['fcdl']==1)?'getfile.php?id='.$id:$content['file'];
}

#Prawa
CatPath(1, ((Admit('F'))?'<img src="img/icon/edit.png" alt="E" />
<a href="?co=edit&amp;act=file&amp;id='.$id.'">'.$lang['edit'].'</a>':''));

#Ocena
$data[14]=''; //POTEM!!!!!

#Szablon
$tpl=file_get_contents($catst.'file.php');
$tpl=str_replace(array('{title}','{date}','{desc}','{who}','{num}','{lang.dl}','{lang.who}','{lang.rate}','{lang.num}','{lang.size}','{lang.desc}','{size}','{url}','{rate}'),$data,$tpl);
$tpl=explode('{fulldesc}',$tpl);
echo $tpl[0].nl2br($content['fulld']).$tpl[1];

unset($data,$tpl,$content,$dinfo,$size);

#Komentarze
if($cfg['fcomm']==1 && $dinfo['opt']&2)
{
	define('CT','2');
	require('./lib/comm.php');
}
?>
