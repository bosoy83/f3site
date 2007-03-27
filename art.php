<?php
if(iCMS!='E123' || $_REQUEST['art']) exit;
if($_GET['id']) { $id=$_GET['id']; } else { $id=1; }
$art['cat']='';
$art['ID']='';
require('cfg/c.php');
#Odczyt
db_read('t.*,f.text,f.emo,f.br,f.php','arts t LEFT JOIN '.$db_pre.'artstxt f ON t.ID=f.ID','art','oa',' WHERE t.ID='.$id);
if($art['ID']!='') {
 db_read('ID,name,access,type,sc,opt','cats','dinfo','oa',' WHERE ID='.$art['cat']);
 if($dinfo['access']!=3 && $art['access']==1) {
  CatStr(1);
  #Admin
  if(ChPrv('A')) $art['name'].=' <span style="font-weight: normal">(<a href="adm.php?a=eart&amp;id='.$art['ID'].'">'.$lang['edit'].'</a>)</span>';
  #Ocena
  $xrates=(($cfg['arate']==1)?$lang['rate'].': '.Rating($art['rates'],0).' &middot; <a href="javascript:Okno(\'?mode=o&amp;co=art&amp;id='.$id.'\',400,250,200,200)">'.$lang['ratedo'].'</a>':'');
  #Wy¶w.
  if($cfg['adisp']==1)
  {
   db_q('UPDATE '.$db_pre.'arts SET ent=ent+1 WHERE ID='.$id);
   $disptxt=$lang['disps'].': <b>'.($art['ent']+1).'</b>';
  }
  else { $disptxt=''; }
  #Emoty
  if($art['emo']==1)
  {
   $art['text']=Emots($art['text']);
  }
  if($art['br']==1)
  {
   $art['text']=nl2br($art['text']);
  }
  function art()
  {
   global $art;
   if($art['php']==1) { eval('?>'.$art['text'].'<?'); } else { echo $art['text']; }
  }
  require($catst.'art.php');
  #Komentarze
  if($cfg['acomm']==1 && !strstr($dinfo['opt'],'C'))
  {
   define('CT','1');
   require('inc/comm.php');
  }
 }
 else {
  Info($lang['noaccess']);
 }
}
else
{
 Info($lang['noex']);
}
?>
