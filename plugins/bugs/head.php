<?php
require_once('cfg/plug_bugs.php');
require_once('plugins/bugs/lang/'.$nlang.'.php');

if(!defined('ADVJS')) { echo '<script type="text/javascript" src="inc/adv.js"></script>'; define('ADVJS',1); }

$bugst='plugins/bugs/style/'.$cfg['bugs_s'].'/';

?>
<link href="<?=$bugst?>bugs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
<?php
echo '
var id='.(int)$_GET['id'].';
var lang1="'.$lang['giver'].' (1-5)";
var lang2="'.$lang['wait'].'";
var lang3="'.$lang['reqerr'].'";
var lang4='.((LOGD==1 || $cfg['bugs_v']==1)?1:'"'.$lang['logtov'].'"');
?>

function RateBug(x)
{
 if(lang4!=1) { alert(lang4); return false; }
 if(x==0) x=prompt(lang1);
 if(x>0 && x<6)
 {
  var rb=new Request();
	rb.url='request.php?co=bugs&t=rate&r='+x+'&id='+id;
	rb.Done=function(odp) { d('brate').innerHTML=odp }
	rb.Loading=function() { d('brate').innerHTML=lang2 }
	rb.run();
 }
}

<?php
if(ChPrv('BUGS'))
{
?>
//Status
function ZSt(s)
{
 var bug=new Request();
 bug.reset();
 bug.url='request.php?co=bugs&t=status&s='+s+'&id='+id;
 bug.Done=function(odp) { if(odp!='') { d('st').innerHTML=odp } else { alert(lang3) } }
 bug.Loading=function() { d('st').innerHTML=lang2 }
 bug.run();
}
//Usuñ
function DelBug()
{
 var a=confirm('');
 if(a)
 {
	var b=new Request();
	b.url='request.php?co=bugs&t=del&id='+id;
	b.Done=function(odp) { alert(odp) }
	b.Loading=function() { d('delbtn').disabled=1 }
	b.run();
 }
}
<?php } ?>
-->
</script>