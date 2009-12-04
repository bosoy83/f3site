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
	$text = clean($_POST['text'], $l);
}

#BBCode
if(isset($_POST['BBCODE']) && isset($cfg['bbcode']))
{
	include './lib/bbcode.php';
	$text = BBCode($text);
}

#Emoty
if(isset($_POST['EMOTS'])) $text = emots($text);

#Nowa linia i wy¶wietl
echo isset($_POST['NL']) ? nl2br($text) : $text;
exit;