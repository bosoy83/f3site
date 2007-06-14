<?php
if(iCMS!=1 || $_REQUEST['img']) exit;
if($_GET['id']) { $id=$_GET['id']; } else { $id=1; }
$img['ID']='';
$img['cat']='';
require('cfg/c.php');
#Odczyt
db_read('*','imgs','img','oa',' WHERE ID='.$id);
if($img['ID']!='') {
 db_read('ID,name,access,type,sc,opt','cats','dinfo','oa',' WHERE ID='.$img['cat']);
 if($dinfo['access']!=3 && $img['access']==1)
 {
  #Admin
  if(ChPrv('I')) $img['name'].=' <span style="font-weight: normal">(<a href="adm.php?a=eimg&amp;id='.$img['ID'].'">'.$lang['edit'].'</a>)</span>';
  #Rozm.
  $xs=explode('||',$img['size']);
  #Ocena
  $xrates=($cfg['irate']==1 && !strstr($dinfo['opt'],'O'))?Rating($img['rates'],1).' &middot; <a href="javascript:Okno(\'?mode=o&amp;co=img&amp;id='.$id.'\',400,250,200,200)">'.$lang['ratedo'].'</a>':'';
  #Typ
  if($img['type']==1)
  {
   $ximg='<a href="'.$img['file'].'"><img src="'.$img['file'].'" alt="[IMG]" style="border: 0'.(($xs[0])?'; width: '.$xs[0].'px; height: '.$xs[1].'px':'').'" /></a>';
  }
  else
  {
   include('inc/movie.php');
  }
  $img['dsc']=nl2br($img['dsc']);
  $xdate=genDate($img['date']);
  CatStr(1);
  require($catst.'img.php');
 }
 else {
  Info($lang['noaccess']);
 }
 #Komentarze
 if($cfg['icomm']==1 && !strstr($dinfo['opt'],'C'))
 {
  define('CT','3');
  require('inc/comm.php');
 }
}
else
{
 Info($lang['noex']);
}
?>
