<?php
if(iCMS!='E123') exit;
#Usuñ 1
if(($_GET['act2']==1 || $_GET['pmsav']) && $id!='')
{
 $pmd='ID='.$id;
}
#Z listy
elseif(is_array($_POST['pmdel']))
{
 $ile=count($_POST['pmdel']);
 if(isset($pmds)) unset($pmds);
 if(isset($pmd)) unset($pmd);
 foreach($_POST['pmdel'] as $key=>$val)
 {
  if(!is_numeric($key)) { exit('$PMDEL error!'); }
  $pmds[]='ID='.$key;
 }
 $pmd=join(' OR ',$pmds);
 unset($pmds,$key,$val);
}
#Wykonanie
if($_POST['pmsav'] || $_GET['pmsav'])
{
 db_q('UPDATE {pre}pms SET st=3 WHERE st=2 AND owner='.UID.' AND ('.$pmd.')');
 $id=3;
}
else
{
 #Ilo¶æ wiadomo¶ci
 if(isset($pmu)) unset($pmu);
 if(isset($pm)) unset($pm);
 db_read('owner','pms','pm','tn',' WHERE (usr='.UID.' OR owner='.UID.') AND st=1 AND ('.$pmd.')');
 $ile=count($pm);
 if($ile>0)
 {
  for($i=0;$i<$ile;$i++)
  {
   $pmu[$pm[$i][0]]++;
  }
  foreach($pmu as $key=>$val)
  {
   db_q('UPDATE {pre}users SET pms=pms-'.$val.' WHERE ID='.$key);
  }
  unset($key,$val,$pmu,$pm,$i);
 }
 db_q('DELETE FROM {pre}pms WHERE (owner='.UID.' OR (usr='.UID.' AND st=1)) AND ('.$pmd.')');
}
unset($pmd);
require('inc/pml.php');
?>
