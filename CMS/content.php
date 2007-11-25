<?php
if(iCMS!=1) exit;
$id=isset($_GET['id'])?$_GET['id']:0;
require('./cfg/c.php');

#Sta³a bezpieczeñstwa
define('CONTENT',1);

#Odczyt danych i ustawienie tytu³u strony do $title
#Zmienna $co = $_GET['co'] utworzona tymczasowo w index.php

#Art
if($co=='art')
{
	$res=$db->query('SELECT t.*,f.text,f.page,f.opt FROM '.PRE.'arts t INNER JOIN '.PRE.'artstxt f ON t.ID=f.ID WHERE t.ID='.$id.' AND f.page='.((isset($_GET['page']))?$_GET['page']:1));
}

#News
elseif($co=='news')
{
	$res=$db->query('SELECT * FROM '.PRE.'news WHERE access=1 && ID='.$id);
}

#Plik
elseif($co=='file')
{
	$res=$db->query('SELECT * FROM '.PRE.'files WHERE access=1 && ID='.$id);
}

#Obraz
elseif($co=='img')
{
	$res=$db->query('SELECT * FROM '.PRE.'imgs WHERE access=1 && ID='.$id);
}

#Strona inf.
else
{
	if(!$co) $id=(int)substr($cfg['start'][$nlang],2); //Domyœlna

	$res=$db->query('SELECT * FROM '.PRE.'pages WHERE ID='.$id.' AND
		(access=1'.((LOGD==1)?' OR access=3':'').')');
}

#Do tablicy
$content=$res->fetch(2);
$res=null;

#KATEGORIA
if($content && $co!='page')
{
	$res=$db->query('SELECT name,sc,type,opt,lft,rgt FROM '.PRE.'cats WHERE ID='.
		$content['cat'].' && access!=3');
	$cat=$res->fetch(2);
	$res=null;
}

#Dostêp
if($content && ($co==='page' || $cat))
{
	$title=$content['name'];
	define('MOD','./mod/'.$co.'.php');
}
else
{
	define('MOD','./404.php');
}
?>