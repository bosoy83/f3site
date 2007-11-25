<?php
if(CONTENT!=1) exit;

#Struktura kategorii
if(Admit('A'))
{
	CatPath($content['cat'],$lang['arts'],'<img src="img/icon/edit.png" alt="E" /> <a href="?co=edit&amp;act=art&amp;id='.$id.'">'.$lang['edit'].'</a>');
}
else
{
	CatPath($content['cat'],$lang['arts']);
}

#Emoty
if($content['opt']&2)
{
	$content['text']=Emots($content['text']);
}
#BR
if($content['opt']&1)
{
	$content['text']=nl2br($content['text']);
}

#Data,autor
$date=genDate($content['date']);
$wrote=Autor($content['author']);

#Ocena
$rate=''; //POPRAWIÆ TO!!!!!!!!!!!!!

#Wy¶w.
if($cfg['adisp']==1)
{
	$db->exec('UPDATE '.PRE.'arts SET ent=ent+1 WHERE ID='.$id);
	$disp='<b>'.++$content['ent'].'</b>';
}
else { $disp='-'; }

#Strony
if($content['pages']>1)
{
	$content['text'].='<br /><br /><center>'.Pages($content['page'],$content['pages'],1,'?co=art&amp;id='.$id,2).'</center>';
}
	
#Szablon
include($catst.'art.php');

unset($content,$disp,$date,$wrote,$rate);

#Komentarze
if($cfg['acomm']==1 && $cat['opt']&2)
{
	define('CT','1');
	require('./lib/comm.php');
}
unset($cat);
?>
