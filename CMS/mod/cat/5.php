<?php
if(iCMS!=1) exit;
CatPath(0,$lang['news']);
CatStart();

#Z subkategorii te¿? - przenieœæ do d.php
if($cat['opt']&8)
{
	$q=' IN (SELECT ID FROM '.PRE.'cats WHERE lft BETWEEN '.$cat['lft'].' AND '.$cat['rgt'].')';
}
else
{
	$q='='.$d;
}

#Odczyt
$res=$db->query('SELECT n.*,login FROM '.PRE.'news n LEFT JOIN '.PRE.'users u
	ON n.author=u.ID WHERE n.cat'.$q.' AND (n.access=1 OR n.access="'.$nlang.'")
	ORDER BY n.ID DESC LIMIT '.$st.','.$cfg['numofn']);

#Prawa
if(Admit('NEWS')) { $yes=1; } else { $yes=0; }

#Styl
include($catst.'news.php');

$ile=0;
foreach($res as $news)
{
	#Data,autor,link
	$xdate=genDate($news['date']);
	$wrote=$lang['wrote'].': <a href="?co=user&amp;id='.$news['author'].'">'.$news['login'].'</a>';
	$xlink='?co=news&amp;id='.$news['ID'];

	#Pe³na treœæ
	$more=($news['opt']&4)?' <a href="'.$xlink.'">'.$lang['more'].'</a>':'';

	#Edytuj
	$edit=($yes==1)?'<a href="?co=edit&amp;act=new&amp;id='.$news['ID'].'"><img src="img/icon/edit.png" alt="E" /></a>':'';

	#Komentarze
	if($cfg['ncomm']==1 && $cat['opt']&2)
	{
		$c='<a href="'.$xlink.'">'.$lang['comms'].'</a> ('.$news['comm'].')';
	}
	else { $c=''; }

	#Emoty
	if($news['opt']&2)
	{
		$news['txt']=Emots($news['txt']);
	}

	#Zawijanie
	if($news['opt']&1)
	{
		$news['txt']=nl2br($news['txt']);
	}

	#Obraz
	if($news['img']) $news['txt']='<img src="'.$news['img'].'" alt="" class="newsimg" />'.$news['txt'];

	#Poka¿
	News(); ++$ile;
}

#Brak?
if($ile==0) Info($lang['nonews']);
$res=null;
unset($news,$xdate,$wrote,$more,$c,$xlink,$edit);
?>
