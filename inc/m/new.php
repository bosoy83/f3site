<?php
if(iCMS!='E123') exit;
if(!defined('ADVJS'))
{
 echo '<script type="text/javascript" src="inc/adv.js"></script>';
 define('ADVJS',1); 
}
?>
<script type="text/javascript">
<!--
var newm=new Request();
newm.Loading=function() { d('newmenu').innerHTML='<p align="center" style="margin: 5px"><img src="img/icon/clock.png" alt="WAIT" /></p>' }
newm.Done=function(x) { var f=x.split('&del;'); d('sb').innerHTML=f[0]; d('newmenu').innerHTML=f[1] }

function GetNew(x)
{
 if(x<9 && x>0)
 {
	newm.url='request.php?co=new&id='+x;
	newm.run();
 }
}
-->
</script>

<?php
require_once('cfg/c.php');

global $c;
$c=array();
$list=array('art','file','img','link','news');
$langs=array('arts','files','gallery','links','news');
$rand=mt_rand(0,4);

echo '<div class="hintbut" id="sb" onclick="Hint(\'selnew\',0,0,1)"><b>'.$lang[$langs[$rand]].'</b></div>

<div id="selnew" class="menulist hint" style="width: 165px">
<ul>
 <li onclick="GetNew(1)">'.$lang['arts'].'</li>
 <li onclick="GetNew(2)">'.$lang['files'].'</li>
 <li onclick="GetNew(3)">'.$lang['gallery'].'</li>
 <li onclick="GetNew(4)">'.$lang['links'].'</li>
 <li onclick="GetNew(5)">'.$lang['news'].'</li>
</ul>
</div>';

db_read('z.ID,z.name',$list[$rand].(($rand==4)?'':'s').' z INNER JOIN {pre}cats c ON z.cat=c.ID','c','tn',' WHERE z.access!=2 AND c.access!=3 ORDER BY ID DESC LIMIT 0,10');
$ile=count($c);

echo '<div id="newmenu">';

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
unset($c,$list,$langs,$rand);
?>

</div>