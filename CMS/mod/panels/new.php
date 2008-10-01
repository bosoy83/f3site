<?php
if(iCMS!=1) exit;

#Istnieje?
if(file_exists('./cache/new-'.$GLOBALS['nlang'].'.php'))
{
	include './cache/new-'.$GLOBALS['nlang'].'.php';
}
else
{
	echo '<div style="text-align: center">'.$lang['lack'].'</div>';
}