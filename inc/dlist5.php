<?php
if(iCMS!=1) exit;
#Odczyt
if(!defined('SEARCH'))
{
 $news=array();
 $ile=db_read('n.*,login','news n LEFT JOIN '.PRE.'users u ON n.author=u.ID','news','ta',' WHERE (n.access=1 OR n.access="'.db_esc($nlang).'") AND n.cat='.$dinfo['ID'].' ORDER BY n.ID DESC LIMIT 0,'.$cfg['numofn']);
}

if($ile>0)
{
 #Prawa
 if(ChPrv('NEWS')) { $yes=1; } else { $yes=0; }

 #Styl
 include($catst.'news.php');
 unset($xdate,$xlink,$edit,$wrote,$more,$c);
 
 #Lista
 for($i=0;$i<$ile;++$i)
 {
  $xnews=&$news[$i];
	
	#Data,autor,link
	$xdate=genDate($xnews['date']);
	$wrote=$lang['wrote'].': <a href="?co=user&amp;id='.$xnews['author'].'">'.$xnews['login'].'</a>';
	$xlink=($cfg['news_nw']==1)?'javascript:nw(\'news\','.$xnews['ID'].')':'?co=news&amp;id='.$xnews['ID'];

	#Pe³na treœæ
	$more=($xnews['fn']==1)?' <a href="'.$xlink.'">'.$lang['more'].'</a>':'';
	
	#Edytuj
	$edit=($yes==1)?'<a href="adm.php?a=enew&amp;id='.$xnews['ID'].'"><img src="img/icon/edit.png" alt="E" /></a>':'';
	
	#Komentarze
	if($cfg['ncomm']==1 && strpos($dinfo['opt'],'C')===false)
	{
	 $c='<a href="'.$xlink.'">'.$lang['comms'].'</a> ('.$xnews['comm'].')';
	}
	else { $c=''; }
	
  #Emoty
  if($xnews['emo']==1)
  {
   $xnews['txt']=Emots($xnews['txt']);
  }

  #Zawijanie
  if($xnews['br']==1)
  {
   $xnews['txt']=nl2br($xnews['txt']);
  }
	
	#Obraz
	if($xnews['img']) $xnews['txt']='<img src="'.$xnews['img'].'" alt="" class="newsimg" />'.$xnews['txt'];
	
	#Poka¿
	News();
 }

 unset($news,$xnews,$xdate,$wrote,$more,$c,$xlink,$edit);
}
else {
 Info($lang['nonews']);
}
?>
