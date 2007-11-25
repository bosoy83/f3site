<?php
if(CONTENT!=1) exit;

#W tabeli?
if($content['opt']&4)
{
	OpenBox($content['name'],1);
	echo '<tr><td class="txt">';
}

#Emotikony
if($content['opt']&2)
{
	$content['text']=Emots($content['text']);
}

#PHP? + BR
if($content['opt']&16)
{
	eval('?>'.(($content['opt']&1)?nl2br($content['text']):$content['text']).'<?');
}
else
{
	echo (($content['opt']&1)?nl2br($content['text']):$content['text']);
}

#Koniec tabeli?
if($content['opt']&4)
{
	echo '</td></tr>';
	CloseBox();
}

#Komentarze
if($content['opt']&8)
{
	define('CT','59');
	require('./lib/comm.php');
}
unset($content);
?>
