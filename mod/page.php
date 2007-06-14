<?php
if(iCMS!=1 || $_REQUEST['infp']) exit;

if($infp['ID'])
{
	#W tabeli?
	if($infp['tab']==1)
	{
		cTable($infp['name'],1);
		echo '<tr><td class="txt">';
	}

	#Tekst
	if($infp['emo']==1)
	{
		$infp['text']=Emots($infp['text']);
	}

	#PHP?
	if($infp['php']==1)
	{
		eval('?>'.(($infp['br']==1)?nl2br($infp['text']):$infp['text']).'<?');
	}
	else
	{
		echo (($infp['br']==1)?nl2br($infp['text']):$infp['text']);
	}

	#Koniec tabeli?
	if($infp['tab']==1)
	{
		echo '</td></tr>';
		eTable();
	}

	#Komentarze
	if($infp['comm']==1)
	{
		define('CT','59');
		require('inc/comm.php');
	}
}
else
{
 Info($lang['noex']);
}
?>