<?php
if(iCMS!=1) exit;

#Pobierz
if(file_exists('./cache/poll_'.$GLOBALS['nlang'].'.php')):
	include('./cache/poll_'.$GLOBALS['nlang'].'.php');
else:
	echo '<div style="text-align: center">'.$lang['lack'].'</div>';
	return;
endif;

#G³osowa³ na...
$voted = isset($_COOKIE['voted']) ? explode('o',$_COOKIE['voted']) : array();

#Wyniki
if(in_array($poll['ID'],$voted) || $poll['ison']==2 || ($poll['ison']==3 && LOGD!=1))
{
	#Brak g³osów?
	if($poll['num']==0) { echo '<center>'.$lang['novotes'].'</center>'; return; }

	#Procenty
	$item = array();
	foreach($option as &$o)
	{
		$item[] = array(
			'num'  => $o[2],
			'label' => $o[1],
			'percent' => round($o[2] / $poll['num'] * 100 ,$cfg['pollRound'])
		);
	}

	#Styl
	include './mod/polls/little.php'; //Na razie domyœlny styl
	unset($poll,$item);
	return;
}

#Formularz do g³osowania
echo '<form action="vote.php" id="poll" method="post">
<div style="text-align: center">'.$poll['q'].'</div><div style="margin: 5px 0px">';

$i=0;
foreach($option as $o)
{
	echo '<label><input id="o_'.++$i.'" name="vote'.(($poll['type']==2)?'['.$o[0].']" type="checkbox" ':'" value="'.$o[0].'" type="radio"').' /> '.$o[1].'</label><br />';
}

echo '</div><div style="text-align: center">
	<input type="submit" value="OK" name="poll" />
	<input type="button" value="'.$lang['results'].'" onclick="location=\'?co=poll&amp;id='.$poll['ID'].'\'" />
</div>
</form>';

unset($poll,$option,$voted,$pollproc);