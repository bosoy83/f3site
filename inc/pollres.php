<?php
if(iCMS!='E123') exit;
if($_mpoll!=1)
{
 $poll['ID']='';
 if(!$_GET['id']) { exit('ID problem!'); } else { $id=$_GET['id']; }
 db_read('*','polls','poll','oa',' WHERE access="'.$nlang.'" && ID='.$id);
}
#Brak?
if($poll['ID']=='')
{
 Info($lang['nopoll']);
}
elseif($poll['num']==0)
{
 Info('<center>'.$lang['novotes'].'</center>');
}
else
{
 #Odp.
 db_read('seq,a,num','answers','answ','tn',' WHERE IDP='.$poll['ID'].' ORDER BY seq');
 #%
 $pallg=0;
 $ile=count($answ);
 for($i=0;$i<$ile;$i++)
 {
  $pollproc[$i]=round($answ[$i][2] / $poll['num'] * 100 ,$cfg['cproc']);
 }
 #Wyniki
 if($_mpoll==1)
 {
  require('inc/pollres/'.$cfg['pollr1'].'.php');
 }
 else
 {
  cTable($poll['name'],1);
  echo '<tr><td>';
  require('inc/pollres/'.$cfg['pollr2'].'.php');
  echo '</td></tr>';
  eTable();
  #Kom.
  if($cfg['pcomm']==1)
  {
   define('CT','15');
   require('inc/comm.php');
  }
 }
}
unset($poll,$answ);
?>
