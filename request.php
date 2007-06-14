<?php
define('REQUEST',1);
require('kernel.php');
header('Content-type: text/html; charset=iso-8859-2');

//Podgl±d tekstu
if($_GET['co']=='text')
{
  $l=(is_numeric($_POST['limit']))?$_POST['limit']:0;
	$o=$_POST['o'];
	if(strstr($o,'H'))
	{
	 $text=TestForm($_POST['text'],0,0,0,$l);
	}
	else
	{
	 $text=TestForm($_POST['text'],1,1,0,$l);
	}
  if(strstr($o,'B'))
	{
	 if($cfg['bbc']==1) { include_once('cfg/bbcode.php'); $text=ParseBBC($text); }
	}
	if(strstr($o,'E')) $text=Emots($text);
	echo ((strstr($o,'L'))?nl2br($text):$text);
}

//Najnowsze
elseif($_GET['co']=='new')
{
 include('inc/m/new.php');
}

//Jêzyk
elseif($_GET['ch_l'])
{
 include('inc/sets.php');
}

//Wtyczki
else
{
 include('plugins/'.$_GET['co'].'/http.php');
}
?>