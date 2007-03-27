<?php
if(iCMS!='E123') exit;
#Odczyt z SQL'a
if(!defined('SEARCH')) db_read('n.*,login','news n LEFT JOIN {pre}users u ON n.author=u.ID','news','ta',' WHERE (n.access=1 OR n.access="'.db_esc($nlang).'") AND n.cat='.$dinfo['ID'].' ORDER BY n.ID DESC LIMIT 0,'.$cfg['numofn']);
$ile=count($news);
#Autorzy
if($ile>0) {
 require_once($catst.'news.php'); #Styl
 StNews();
 #Generowanie
 for($i=0;$i<$ile;$i++) {
  $xnews=&$news[$i];
  #Emblemat
  if(empty($xnews['img']))
  {
   $xnimg='';
  }
  else
  {
   $xnimg='<img src="'.$xnews['img'].'" alt="" class="newsimg" />';
  }
  #Emoty
  if($xnews['emo']==1)
  {
   $xntext=$xnimg.Emots($xnews['txt']);
  }
  else
  {
   $xntext=$xnimg.$xnews['txt'];
  }
  #Autor
  $xauth=$lang['wrote'].': <a href="?co=user&amp;id='.$xnews['author'].'">'.$xnews['login'].'</a>'.((ChPrv('N'))?' (<a href="adm.php?a=enew&amp;id='.$xnews['ID'].'">e</a>)':'');
  #Zawijanie
  if($xnews['br']==1)
  {
   $xntext=nl2br($xntext);
  }
  #Link
  $xlink=(($cfg['news_nw']==1)?'javascript:nw(\'news\','.$xnews['ID'].')':'?co=news&amp;id='.$xnews['ID']);
  $xnlink=(($xnews['fn']==1)?'<a href="'.$xlink.'">'.$lang['more'].'</a>':'').(($xnews['fn']==1 && $cfg['ncomm']==1)?' &middot; ':'').(($cfg['ncomm']==1)?'<a href="'.$xlink.'">'.$lang['comms'].'</a> ('.$xnews['comm'].') ':'');
  #Data
  $xdate=genDate($xnews['date']);
  NewNews();
 }
 EndNews();
 if(ChPrv('N')) echo '<center style="margin-bottom: 5px"><a href="adm.php?a=list&amp;co=5">'.$lang['news'].' - '.$lang['edit'].'</a></center>';
 unset($ile,$news,$ii,$xnlink,$xntext,$xdate,$xnimg);
}
else {
 Info($lang['nonews']);
}
?>
