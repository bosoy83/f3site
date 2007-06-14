<?php
if(iCMS!=1 || $_REQUEST['art']) exit;

if($art['ID'])
{
 db_read('name,access,type,sc,opt','cats','dinfo','oa',' WHERE ID='.$art['cat']);
 if($dinfo['access']!=3 && $art['access']==1)
 {
	#Struktura kategorii
	if(ChPrv('A'))
	{
	 CatStr($art['cat'],'<img src="img/icon/edit.png" alt="E" /> <a href="adm.php?a=eart&amp;id='.$id.'">'.$lang['edit'].'</a>');
	}
	else
	{
	 CatStr($art['cat']);
	}
	
	#Emoty
  if(strpos($art['opt'],'E')!==false)
  {
   $art['text']=Emots($art['text']);
  }
	#BR
	if(strpos($art['opt'],'L')!==false)
	{
	 $art['text']=nl2br($art['text']);
	}
	
	#Data,autor
	$date=genDate($art['date']);
	$wrote=Autor($art['author']);
	
	#Ocena
	if($cfg['arate']==1 && strpos($dinfo['opt'],'O')===false)
	{
	 $rate=Rating($art['rate'],0).((LOGD==1 || $cfg['grate']==1)?' <a href="vote.php?co=art&amp;id='.$id.'">'.$lang['rateit'].'</a>':'');
	}
	else { $rate=$lang['lack']; }
	
	#Wy¶w.
  if($cfg['adisp']==1)
  {
   db_q('UPDATE {pre}arts SET ent=ent+1 WHERE ID='.$id);
   $disp='<b>'.++$art['ent'].'</b>';
  }
  else { $disp='-'; }

	#Strony
	if($art['pages']>1)
	{
	 $art['text'].='<br /><br /><center>'.Pages($art['page'],$art['pages'],1,'?co=art&amp;id='.$id,2).'</center>';
	}
	
	#Szablon
	include($catst.'art.php');
	
  #Komentarze
  if($cfg['acomm']==1 && !strstr($dinfo['opt'],'C'))
  {
   define('CT','1');
   require('./inc/comm.php');
  }
 }
 else
 {
  Info($lang['noaccess']);
 }
}
else
{
 Info($lang['noex']);
}
unset($art,$disp,$date,$wrote,$rate,$dinfo);
?>
