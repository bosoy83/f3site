<?php
if(iCMS!=1) exit;

#Kategoria
if($dinfo['ID'])
{
	CatStr();

	#Tekst
	if(!empty($dinfo['text']))
	{
		cTable($dinfo['name'],1);
		echo '<tr><td class="txt">'.nl2br($dinfo['text']).'</td></tr>';
		eTable();
	}

	#Podkategorie
	db_read('ID,name,dsc,nums','cats','cat','tn',' WHERE (access=1 OR access="'.$nlang.'") AND sc='.$dinfo['ID'].' ORDER BY name');
	$ile=count($cat);
	if($ile>0)
	{
		define('D',1);
		include('mod/cats.php');
	}

	#Strona
	if($_GET['page'] && $_GET['page']!=1)
	{
		$page=$_GET['page'];
		$st=($page-1)*(($dinfo['type']==3)?$cfg['inp']:$cfg['np']);
	}
	else
	{
		$page=1;
		$st=0;
	}

	#Sort.
	if($dinfo['type']!=5)
	{
		switch($dinfo['sort'])
		{
			case 1: $hlsort='ID'; break;
			case 3: $hlsort='name'; break;
			default: $hlsort='ID DESC';
		}
	}

	#Wywo³anie
	require('inc/dlist'.$dinfo['type'].'.php');
}
else
{
 Info($lang['d_notex']);
}
?>
