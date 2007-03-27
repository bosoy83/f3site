<?php
if(iCMS!='E123' || $_REQUEST['file']) exit;
if($_GET['id']) { $id=$_GET['id']; } else { $id=1; }
require($catl.'files.php');
require('cfg/c.php');
#Odczyt
db_read('*','files','file','oa',' WHERE ID='.$id);
if($file['ID']!='') {
 db_read('ID,name,access,type,sc,opt','cats','dinfo','oa',' WHERE ID='.$file['cat']);
 if($dinfo['access']!=3 && $file['access']==1)
 {
  if($file['size']=='A')
  {
   if(file_exists('./'.$file['file']))
   {
    #Rozmiar
    $xsize=filesize('./'.$file['file']);
    if($xsize>=1048576) { $xfsiz=$xsize/1048576; $j=' MB'; } elseif($xsize>=1024) { $xfsiz=$xsize/1024; $j=' KB'; } else { $xfsiz=&$xsize; $j=' B'; }
    $file['size']=round($xfsiz,$cfg['fsrnd']).$j;
    $xfurl=($cfg['fcdl']==1)?'?mode=dl&amp;id='.$_GET['id']:$file['file'];
   }
   else
   {
    $file['size']=$lang['nof'];
    $xfurl='#';
   }
  }
  else
  {
   $xfurl=(($cfg['fcdl']==1)?'?mode=dl&amp;id='.$_GET['id']:$file['file']);
  }
  #Admin
  if(ChPrv('F')) $file['dsc'].='<br />&raquo; <a href="adm.php?a=efile&amp;id='.$file['ID'].'">'.$lang['edit'].'</a>';
  #Ocena
  $xrates=($cfg['frate']==1 && !strstr($dinfo['opt'],'O'))?Rating($file['rates'],1).' &middot; <a href="javascript:Okno(\'?mode=o&amp;co=file&amp;id='.$id.'\',400,250,200,200)">'.$lang['ratedo'].'</a>':'';
  #Data
  $adate=genDate($file['date']);
  #Styl
  CatStr(1);
  require($catst.'file.php');
  #Komentarze
  if($cfg['fcomm']==1 && !strstr($dinfo['opt'],'C'))
  {
   define('CT','2');
   require('inc/comm.php');
  }
 }
 else {
 Info($lang['noaccess']);
 }
}
else {
 Info($lang['noex']);
}
?>
