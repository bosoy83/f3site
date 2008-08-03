<?php /* Podgl±d tekstów */
if(JS!=1) exit;

#Limit
$l = isset($_POST['limit']) ? (int)$_POST['limit'] : 0;

#HTML
if(isset($_POST['HTML']))
{
	$text = $_POST['text'];
}
else
{
	$text = Clean($_POST['text'], $l);
}

#BBCode
if(isset($_POST['BBCODE']) && $cfg['bbcode']==1)
{
	include './lib/bbcode.php';
	$text = BBCode($text);
}

#Emoty
if(isset($_POST['EMOTS'])) $text = Emots($text);

#Nowa linia i wy¶wietl
echo isset($_POST['NL']) ? nl2br($text) : $text;
exit;