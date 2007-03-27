<?php
if(iCMS!='E123' || $_REQUEST['news']) exit;
if($_GET['id']) { $id=$_GET['id']; } else { $id=1; }
$news['ID']='';
$news['cat']='';
require('cfg/c.php');
#Odczyt
db_read('*','news','news','oa',' WHERE ID='.$id);
if($news['fn']==1)
{
 db_read('text','fnews','fnews','on',' WHERE ID='.$id);
}
else
{
 $fnews[0]='';
}

if($news['ID']!='')
{
 db_read('ID,name,access,type,sc,opt','cats','dinfo','oa',' WHERE ID='.$news['cat']);
 if(($dinfo['access']!=3 && $news['access']!=2)) {
  #Admin
  if(ChPrv('N')) $news['name'].=' <span style="font-weight: normal">(<a href="adm.php?a=enew&amp;id='.$news['ID'].'">'.$lang['edit'].'</a>)</span>';
  #Emblemat
  $xnimg=(($news['img']==null || empty($news['img']))?'':'<img src="'.$news['img'].'" alt="" class="newsimg" />');
  #Emoty
  if($news['emo']==1)
  {
   $text=$xnimg.Emots($news['txt']).(($news['fn']==1)?'<br /><br />'.Emots($fnews[0]):'');
  }
  else
  {
   $text=$xnimg.$news['txt'].(($news['fn']==1)?'<br /><br />'.$fnews[0]:'');
  }
  #Linie
  if($news['br']==1) $text=nl2br($text);
  #Data
  $xdate=genDate($news['date']);
  #Styl
  CatStr(1);
  require($catst.'fnews.php');
  #Komentarze
  if($cfg['ncomm']==1 && !strstr($dinfo['opt'],'C'))
  {
   define('CT','5');
   require('inc/comm.php');
  }
  unset($text,$xnimg,$xdate);
 }
 else {
  Info($lang['noaccess']);
 }
}
else {
 Info($lang['noex']);
}
?>

