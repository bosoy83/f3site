<?php
if(iCMS!=1) exit;
?>
<script type="text/javascript">
<!--
var newm=new Request("","newmenu")

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
require_once('cfg/content.php');

$c=array();
$c2='';
$c1='';
$get='ID';

//Typ
switch(mt_rand(1,5))
{
 case 2:
	$name='files';
	$c1='?co=file&amp;id=';
	break;
 case 3:
	$name='imgs';
	$c1='?co=img&amp;id=';
	break;
 case 4:
	$name='links';
	$get='adr';
	break;
 case 5:
	$name='news';
	$c1='?co=file&amp;id=';
	break;
 default:
	$name='arts';
	$c1='?co=art&amp;id='; $c2='';
	break;
}

echo '<div id="selnew" class="menulist hint" style="width: 165px">
<ul>
 <li onclick="GetNew(1)">'.$lang['arts'].'</li>
 <li onclick="GetNew(2)">'.$lang['files'].'</li>
 <li onclick="GetNew(3)">'.$lang['imgs'].'</li>
 <li onclick="GetNew(4)">'.$lang['links'].'</li>
 <li onclick="GetNew(5)">'.$lang['news'].'</li>
</ul>
</div>
<div id="newmenu">
<div class="tab" onclick="hint(\'selnew\',0,0,1)"><b>'.$lang[$name].'</b></div>';

$res=$db->query('SELECT z.'.$get.',z.name FROM '.PRE.$name.' z
	INNER JOIN '.PRE.'cats c ON z.cat=c.ID WHERE z.access!=2 AND c.access!=3
	ORDER BY z.ID DESC LIMIT 0,10');
$res->setFetchMode(3);

echo '<ul>';
foreach($res as $c)
{
	echo '<li><a href="'.$c1.$c[0].$c2.'">'.
		((strlen($c[1])>18)?substr($c[1],0,18).'...':$c[1]).'</a></li>';
}
echo '</ul>';

$res=null;
unset($c,$get,$c1,$c2,$name);
?>
</div>
