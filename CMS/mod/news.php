<?php
if(CONTENT!=1) exit;

#Pe³na treœæ
if($content['opt']&4)
{
	$res=$db->query('SELECT text FROM '.PRE.'fnews WHERE ID='.$id);
	$full=$res->fetchColumn();
	$res=null;
}
else
{
	$full='';
}

#Edytuj,autor
if(Admit('N'))
{
	CatPath($content['cat'],$lang['news'],'<a href="?co=edit&amp;act=new&amp;id='.$id.'"><img src="img/icon/edit.png" alt="E" /> '.$lang['edit'].'</a>');
	$wrote=$lang['wrote'].': '.Autor($content['author']).' | <a href="?co=edit&amp;act=new&amp;id='.$id.'">'.$lang['edit'].'</a>';
}
else
{
	CatPath($content['cat'],$lang['news']);
	$wrote=$lang['wrote'].': '.Autor($content['author']);
}

#Emblemat
$xnimg=($content['img']==0)?'':'<img src="'.$content['img'].'" alt="" class="newsimg" />';

#Emoty
if($content['opt']&2)
{
 $text=$xnimg.Emots($content['txt']).(($full)?'<br /><br />'.Emots($full):'');
}
else
{
 $text=$xnimg.$content['txt'].(($full)?'<br /><br />'.$full:'');
}

#Linie
if($content['opt']&1) $text=nl2br($text);

#Data
$date=genDate($content['date']);

#Styl
require($catst.'fullnews.php');

unset($xnimg,$date,$content,$full,$wrote);

#Komentarze
if($cfg['ncomm']==1 && $cat['opt']&2)
{
	define('CT','5');
	require('lib/comm.php');
}
?>