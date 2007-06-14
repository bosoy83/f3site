<?php
if(iCMS!=1) exit;
global $nlang,$lang,$poll,$answ,$cfg;
$_mpoll=1;
#S± zmienne?
$poll['ID']='';
db_read('*','polls','poll','oa',' WHERE access="'.$nlang.'" ORDER BY ID DESC LIMIT 1');
#Brak?
if($poll['ID']=='')
{
 echo '<center>'.$lang['lack'].'</center>';
}
else
{
 require('mod/poll.php');
}
unset($_mpoll,$poll);
?>
