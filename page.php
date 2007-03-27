<?php
if(iCMS!='E123' || $_REQUEST['infp']) exit;
if($_GET['id']) { $id=$_GET['id']; } else { $id=1; }
$infp['ID']='';

db_read('*','pages','infp','oa',' WHERE (access=1'.((LOGD==1)?' OR access=3':'').') AND ID='.$id);
if($infp['ID']!='')
{
 #Tab.
 if($infp['tab']==1)
 {
  cTable($infp['name'].((ChPrv('IP'))?' <span style="font-weight: normal">(<a href="adm.php?a=editp&amp;id='.$id.'">'.$lang['edit'].'</a>)</span>':''),1);
  echo '<tr><td class="txt">';
 }
 #Tekst
 if($infp['emo']==1)
 {
  $infp['text']=Emots($infp['text']);
 }
 #PHP?
 if($infp['php']==1)
 {
  eval('?>'.(($infp['br']==1)?nl2br($infp['text']):$infp['text']).'<?');
 }
 else
 {
  echo (($infp['br']==1)?nl2br($infp['text']):$infp['text']);
 }
 #Tab.
 if($infp['tab']==1)
 {
  echo '</td></tr>';
  eTable();
 }
 if($infp['comm']==1)
 {
  define('CT','59');
  require('inc/comm.php');
 }
}
else
{
 Info($lang['noex']);
}
?>
