<?php
if(iCMS!=1) exit;

#Istnieje?
if(file_exists('./cache/new-'.LANG.'.php'))
{
	include './cache/new-'.LANG.'.php';
}
else
{
	echo '<div style="text-align: center">'.$lang['lack'].'</div>';
}