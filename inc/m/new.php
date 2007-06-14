<?php
if(iCMS!=1) exit;
if(REQUEST!=1)
{
 if(ADVJS=='ADVJS')
 {
	echo '<script type="text/javascript" src="inc/adv.js"></script>';
	define('ADVJS',1); 
 }
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
}
global $c,$cfg,$lang;
require_once('cfg/c.php');

$c=array();
$c2='';
$c1='';
$get='ID';

//Modu³
switch($_GET['co'])
{
 case 'art': $type=1; break;
 case 'file': $type=2; break;
 case 'img': $type=3; break;
 case 'link': $type=4; break;
 case 'news': $type=5; break;
 default: $type=($_GET['id'])?$_GET['id']:mt_rand(1,5);
}

//Typ
switch($type)
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
	if($cfg['lcnt']==1) { $c1='link.php?id='; } else { $get='adr'; }
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
<div class="tab" onclick="Hint(\'selnew\',0,0,1)"><b>'.$lang[$name].'</b></div>';

db_read('z.'.$get.',z.name',$name.' z INNER JOIN '.PRE.'cats c ON z.cat=c.ID','c','tn',' WHERE z.access!=2 AND c.access!=3 ORDER BY z.ID DESC LIMIT 0,10');
$ile=count($c);

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
unset($c,$get,$c1,$c2,$name); ?>
</div>
