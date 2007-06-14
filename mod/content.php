<?php
if(iCMS!=1) exit;
if($_GET['id']) { $id=$_GET['id']; } else { $id=0; }
require('cfg/c.php');

#Odczyt danych i ustawienie czêœci tytu³u strony do $title

#Art
if($co=='art')
{
	$art=array();
	db_read('t.*,f.text,f.page,f.opt','arts t INNER JOIN '.PRE.'artstxt f ON t.ID=f.ID','art','oa',' WHERE t.ID='.$id.' AND f.page='.(($_GET['page'])?$_GET['page']:1));
	if($art['ID'])
	{
		define('MOD','mod/art.php');
		if($art['access']==1) $title=$art['name'];
	}
	else
	{
		define('MOD','404.php');
	}
}

#News
elseif($co=='news')
{
	$news=array();
	db_read('*','news','news','oa',' WHERE ID='.$id);
	if($news['ID'])
	{
		define('MOD','mod/news.php');
		if($news['access']==1) $title=$news['name'];
	}
	else
	{
		define('MOD','404.php');
	}
}

#Plik
elseif($co=='file')
{
	$file=array();
	db_read('*','files','file','oa',' WHERE ID='.$id);
	if($file['ID'])
	{
		define('MOD','mod/file.php');
		if($file['access']==1) $title=$file['name'];
	}
	else
	{
		define('MOD','404.php');
	}
}

#Strona inf.
elseif($co=='page')
{
	$infp=array();
	db_read('*','pages','infp','oa',' WHERE ID='.$id.' AND (access=1'.((LOGD==1)?' OR access=3':'').')');
	if($infp['ID'])
	{
		define('MOD','mod/page.php');
		$title=$infp['name'];
	}
	else
	{
		define('MOD','404.php');
	}
} ?>