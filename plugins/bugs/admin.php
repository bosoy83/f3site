<?php
if(iCMSa!='X159E' || !ChPrv('BUGADM')) exit;
require('plugins/bugs/lang/adm'.$nlang.'.php');
switch($_GET['act'])
{
 case 's': require('plugins/bugs/admsect.php'); break;
 case 'o': require('plugins/bugs/admcfg.php'); break;
 case 'e': require('plugins/bugs/admedit.php'); break;
 default: require('plugins/bugs/admcats.php'); break;
}
?>