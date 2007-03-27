<?php
if(iCMS!='E123' || $_REQUEST['poll'] || $_REQUEST['answ']) exit;
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
 if(isset($_COOKIE[$cfg['c'].'poll'.$poll['ID']]) || $poll['ison']==2 || ($poll['ison']==3 && LOGD!=1))
 {
  require('inc/pollres.php');
 }
 else
 {
  require('inc/pollq.php');
 }
}
unset($_mpoll,$poll);
?>
