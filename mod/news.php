<?php
if(iCMS!=1 || $_REQUEST['news']) exit;

#Pe³na treœæ
if($news['fn']==1)
{
 $full=db_read('text','fnews',1,'get',' WHERE ID='.$id);
}
else
{
 $full='';
}

if($news['ID']!='')
{
 db_read('name,type,access,sc,opt','cats','dinfo','oa',' WHERE ID='.$news['cat']);
 
 if($dinfo['access']!=3 && $news['access']!=2)
 {
  #Edytuj,autor
  if(ChPrv('N'))
	{
	 CatStr($news['cat'],'<a href="adm.php?a=enew&amp;id='.$id.'"><img src="img/icon/edit.png" alt="E" /> '.$lang['edit'].'</a>');
	 $wrote=$lang['author'].': '.Autor($news['author']).' | <a href="adm.php?a=enew&amp;id='.$id.'">'.$lang['edit'].'</a>';
	}
	else
	{
	 CatStr($news['cat']);
	 $wrote=$lang['author'].': '.Autor($news['author']);
	}
	
  #Emblemat
  $xnimg=($news['img']==0)?'':'<img src="'.$news['img'].'" alt="" class="newsimg" />';

  #Emoty
  if($news['emo']==1)
  {
   $text=$xnimg.Emots($news['txt']).(($news['fn']==1)?'<br /><br />'.Emots($full):'');
  }
  else
  {
   $text=$xnimg.$news['txt'].(($news['fn']==1)?'<br /><br />'.$full:'');
  }
	
  #Linie
  if($news['br']==1) $text=nl2br($text);
	
  #Data
  $xdate=genDate($news['date']);
	
  #Styl
  require($catst.'fnews.php');
	
  #Komentarze
  if($cfg['ncomm']==1 && !strstr($dinfo['opt'],'C'))
  {
   define('CT','5');
   require('inc/comm.php');
  }
  unset($text,$xnimg,$xdate,$news,$full,$wrote);
 }
 else {
  Info($lang['noex']);
 }
}
else {
 Info($lang['noex']);
}
?>

