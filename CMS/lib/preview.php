<?php /* Podgl±d tekstów */
if(REQUEST!=1) exit;

#Tabela
if(isset($_POST['table']))
{
	OpenBox($lang['preview']);
	echo '<tr><td class="txt">';
}

#Limit
$l=isset($_POST['limit'])?(int)$_POST['limit']:0;

#HTML
if(isset($_POST['HTML']))
{
	$text=$_POST['text'];
}
else
{
	$text=Clean($_POST['text'],$l);
}

#BBCode
if(isset($_POST['BBCODE']))
{
	if($cfg['bbc']==1) { include('./lib/bbcode.php'); $text=ParseBBC($text); }
}

#Emoty
if(isset($_POST['EMOTS'])) $text=Emots($text);

#Nowa linia i wy¶wietl
echo ((isset($_POST['NL']))?nl2br($text):$text);

#Tabela
if(isset($_POST['table']))
{
	echo '</td></tr>';
	CloseBox();
}
?>
