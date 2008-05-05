<?php
if(iCMS!=1) exit;

#Pobierz
if(file_exists('./cache/poll_'.$GLOBALS['nlang'].'.php')):
	include('./cache/poll_'.$GLOBALS['nlang'].'.php');
else:
	echo '<center>'.$lang['lack'].'</center>';
	return;
endif;

#G³osowa³ na...
$voted=isset($_COOKIE[PRE.'voted'])?unserialize($_COOKIE[PRE.'voted']):array();

#Wyniki
if(in_array($poll['ID'],$voted) || $poll['ison']==2 || ($poll['ison']==3 && LOGD!=1))
{
	#Brak g³osów?
	if($poll['num']==0) { echo '<center>'.$lang['novotes'].'</center>'; return; }

	#Procenty
	for($i=0;$i<$ile;++$i)
	{
		$pollproc[$i]=round($option[$i][2] / $poll['num'] * 100 ,$cfg['pollRound']);
	}

	#Styl
	include './mod/polls/'.$cfg['pollr2'].'.php';
	return;
}

#Formularz do g³osowania
echo '<form action="vote.php" name="sendvote" method="post">
<center>'.$poll['q'].'</center><div style="margin: 5px 0px">';

$i=0;
foreach($option as $o)
{
	echo '<input id="o_'.++$i.'" name="vote'.(($poll['type']==2)?'['.$o[0].']" type="checkbox" ':'" value="'.$o[0].'" type="radio"').' /> <label for="o_'.$i.'">'.$o[1].'</label><br />';
}

echo '</div><center>
	<input type="submit" value="OK" name="poll" />
	<input type="button" value="'.$lang['results'].'" onclick="location=\'?co=poll&amp;id='.$poll['ID'].'\'" />
</center>
</form>';

unset($poll,$option,$voted,$pollproc);
?>
