<?php
if(iCMS!='E123') exit;
#Modu³y
if($_GET['co'])
{
 switch($_GET['co'])
 {
  case 'pms': require('pms.php'); break;
  case 's': require('search.php'); break;
  case 'groups': require('groups.php'); break;
  case 'comm': require('inc/ecomm.php'); break;
  case 'news': require('news.php'); break;
  case 'user': require('user.php'); break;
  case 'page': require('page.php'); break;
  case 'psw': require('inc/psw.php'); break;
  case 'art': require('art.php'); break;
  case 'users': require('users.php'); break;
  case 'file': require('file.php'); break;
  case 'uedit': require('inc/uedit.php'); break;
  case 'img': require('img.php'); break;
  case 'cats': require('cats.php'); break;
  case 'arch': require('archive.php'); break;
  case 'poll': require('inc/pollres.php'); break;
  case 'parch': require('parch.php'); break;
  default: @include('plugins/'.$_GET['co'].'/co.php');
 }
}
else
{
 #Konf.
 require('cfg/c.php');
 #Kategoria
 if($_GET['d'] || $cfg['dfct']!=2)
 {
  #ID
  if($_GET['d']) { $d=$_GET['d']; } else { $d=$cfg['dfc'][$nlang]; }
	if(!is_numeric($d)) $d=1;
  $dinfo['ID']='';
  db_read('*','cats','dinfo','oa',' WHERE access!=3 AND ID='.$d);
  if($dinfo['ID']!='')
  {
   CatStr(0);
   #Tekst
   if(!empty($dinfo['text'])) { cTable($dinfo['name'],1); echo '<tr><td class="txt">'.nl2br($dinfo['text']).'</td></tr>'; eTable(); }
   #Subk.
   db_read('ID,name,dsc,nums','cats','cat','tn',' WHERE (access=1 OR access="'.$nlang.'") AND sc='.$dinfo['ID'].' ORDER BY name');
   $ile=count($cat);
   if($ile>0)
   {
    define('D',1);
    include('cats.php');
   }
   #Strona
   if($_GET['page'] && $_GET['page']!=1)
   {
    $page=$_GET['page'];
    $st=($page-1)*(($dinfo['type']==3)?$cfg['inp']:$cfg['np']);
   }
   else
   {
    $page=1;
    $st=0;
   }
   #Sort.
   if($dinfo['type']!=5)
   {
    switch($dinfo['sort'])
    {
     case 1: $hlsort='ID'; break;
     case 3: $hlsort='name'; break;
     default: $hlsort='ID DESC';
    }
   }
   #Wywo³anie
   require('inc/dlist'.$dinfo['type'].'.php');
  }
  else
  {
   Info($lang['d_notex']);
  }
 }
 #Strona inf.
 else
 {
  if(is_numeric($cfg['dfc'][$nlang])) { $_GET['id']=$cfg['dfc'][$nlang]; require('page.php'); }
 }
}
?>
