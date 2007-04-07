<?php
require('kernel.php');
header('Content-type: text/html; charset=iso-8859-2');

switch($_GET['co'])
{
 //Najnowsze
 case 'new':
	require_once('cfg/c.php');
  $id=$_GET['id'];
  if($id>5 || $id<1) exit;
	
	$c2='';
	$c1='';
	$get='ID';
	$c=array();
	
	//Typ
	switch($id)
	{
	 case 2:
		$name='files';
		if($cfg['file_nw']==1) { $c1='javascript:nw(\'art\','; $c2=')'; } else { $c1='?co=file&amp;id='; }
		break;
	 case 3:
		$name='imgs';
		if($cfg['img_nw']==1) { $c1='javascript:nw(\'img\','; $c2=')'; } else { $c1='?co=img&amp;id='; }
		break;
	 case 4:
		$name='links';
		if($cfg['lcnt']==1) { $c1='?mode=link&amp;id='; } else { $get='adr'; }
		break;
	 case 5:
		$name='news';
		if($cfg['news_nw']==1) { $c1='javascript:nw(\'art\','; $c2=')'; } else { $c1='?co=file&amp;id='; }
		break;
	 default:
		$name='arts';
		$c1='?co=art&amp;id='; $c2='';
		break;
	}
	
	db_read('z.'.$get.',z.name',$name.' z INNER JOIN {pre}cats c ON z.cat=c.ID','c','tn',' WHERE z.access!=2 AND c.access!=3 ORDER BY z.ID DESC LIMIT 0,10');
	$ile=count($c);
	
	echo '<b>'.$lang[$name].'</b>&del;';
	if($ile>0)
	{
		echo '<ul>';
		for($i=0;$i<$ile;$i++)
		{
			echo '<li><a href="'.$c1.$c[$i][0].$c2.'">'.
			((strlen($c[$i][1])>18)?substr($c[$i][1],0,18).'...':$c[$i][1]).'</a></li>';
		}
		echo '</ul>';
	}
	else
	{
	 echo '<center>'.$lang['lack'].'</center>';
	}
 break;
 
 //Wtyczki
 default: @include('plugins/'.$_GET['co'].'/http.php');
}
?>
