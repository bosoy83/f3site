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

#Typ
$type = mt_rand(1,8);

#Istnieje?
if(file_exists('./cache/new'.$type.$GLOBALS['nlang'].'.php'))
{
	include './cache/new'.$type.$GLOBALS['nlang'].'.php';
}
else
{
	echo '<div style="text-align: center">'.$lang['lack'].'</div>';
}