<?php
if(iCMS!='E123' || !ChPrv('CM') || !$_GET['id']) exit;
#Nowo¶æ?
db_read('th','comms','comm','on',' WHERE ID='.$_GET['id']);
$xtpc='';
if(!$comm[0]) { exit('Wrong ID!'); }
$xtpc=explode('_',$comm[0]);
if($xtpc[0]==5)
{
 db_q('UPDATE {pre}news SET comm=comm-1 WHERE ID='.$xtpc[1]);
}
#Usuñ
db_q('DELETE FROM {pre}comms WHERE ID='.$_GET['id']);
exit('OK.');
?>
