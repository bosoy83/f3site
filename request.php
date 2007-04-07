<?php
require('kernel.php');
header('Content-type: text/html; charset=iso-8859-2');

switch($_GET['co'])
{
 //Najnowsze
 case 'new':
  $id=$_GET['id'];
  if($id>5 || $id<1) exit;
	$c=array();
	$list=array('','art','file','img','link','news');
	$langs=array('','arts','files','gallery','links','news');
	db_read('z.ID,z.name',$list[$id].(($id==5)?'':'s').' z INNER JOIN {pre}cats c ON z.cat=c.ID','c','tn',' WHERE z.access!=2 AND c.access!=3 ORDER BY ID DESC LIMIT 0,10');
	$ile=count($c);
	
	echo '<b>'.$lang[$langs[$id]].'</b>&del;';
	if($ile>0)
	{
		echo '<ul>';
		for($i=0;$i<$ile;$i++)
		{
			echo '<li><a href="?co='.$list[$rand].'&amp;id='.$c[$i][0].'">'.
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