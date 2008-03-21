<?php
if(iCMS!=1) exit;
//$id=$_GET['id'];

#Pobierz
$page=$db->query('SELECT * FROM '.PRE.'pages WHERE ID='.$id.
	' AND (access=1'.((LOGD==1)?' OR access=3':'').')')->fetch(2);

#Nie istnieje?
if(!$page) { include './404.php'; return; }

#Emotikony
if($page['opt']&2)
{
	$page['text']=Emots($page['text']);
}

#BR
if($page['opt']&1)
{
	$page['text']=nl2br($page['text']);
}

#Tabela
if($page['opt']&4) { OpenBox($page['name']); echo '<tr><td class="txt">'; }

#PHP?
if($page['opt']&16)
{
	eval('?>'.$page['text'].'<?');
}
else
{
	echo $page['text'];
}

#Tabela
if($page['opt']&4) { echo '</td></tr>'; CloseBox(); }

#Komentarze
if($page['opt']&8) { define('CT','59'); require('./lib/comm.php'); }
?>