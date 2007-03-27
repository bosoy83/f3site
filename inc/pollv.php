<?php
if(iCMS!='E123') exit;
$poll['ID']='';
db_read('ID,ison,type','polls','poll','oa',' WHERE access="'.$nlang.'" ORDER BY ID DESC LIMIT 1');
if($poll['ison']==2 || ($poll['ison']==3 && LOGD!=1)) exit;
#Brak?
if($poll['ID']=='')
{
 define('SPECIAL',13);
}
elseif(isset($_COOKIE[$cfg['c'].'poll'.$poll['ID']]))
{
 define('SPECIAL',6);
}
else
{
 #1 odp.
 if($poll['type']==1)
 {
  if(!is_numeric($_POST['u_vote'])) exit('$u_vote error!');
  db_q('UPDATE {pre}answers SET num=num+1 WHERE IDP="'.$poll['ID'].'" && seq='.$_POST['u_vote']);
 }
 #Wiele odp.
 else
 {
  $ile=count($_POST['u_vote']);
  if($ile>50 || $ile=='0') exit;
  $i=-1;
  foreach($_POST['u_vote'] as $key=>$val)
  {
   $i++;
   if(!is_numeric($key)) exit;
   $answs[$i]='seq='.$key;
  }
  db_q('UPDATE {pre}answers SET num=num+1 WHERE IDP='.$poll['ID'].' && ('.implode(' || ',$answs).')');
 }
 #Akt. i przekier.
 db_q('UPDATE {pre}polls SET num=num+1 WHERE ID='.$poll['ID']);
 setcookie($cfg['c'].'poll'.$poll['ID'],'NO',time()+4320000);
 define('SPECIAL',5);
 define('WHERE','?co=poll&amp;id='.$poll['ID']);
}
require('special.php');
exit;
?>
