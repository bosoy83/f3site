<?php
if(iCMS!=1) exit;

#Pobierz
if(file_exists('./cache/poll_'.LANG.'.php')):
	include('./cache/poll_'.LANG.'.php');
else:
	echo '<div style="text-align: center">'.$lang['lack'].'</div>';
	return;
endif;

#G�osowa� na...
$voted = isset($_COOKIE['voted']) ? explode('o',$_COOKIE['voted']) : array();

#Wyniki
if(in_array($poll['ID'],$voted) || $poll['ison']==2 || ($poll['ison']==3 && !UID))
{
	#Brak g�os�w?
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
	include './mod/polls/little.php'; //Na razie domy�lny styl
	unset($poll,$item);
	return;
}

#Formularz do g�osowania
echo '<form action="vote.php" id="poll" method="post">
<div style="text-align: center">'.$poll['q'].'</div><div style="margin: 5px 0px">';

$i=0;
foreach($option as $o)
{
	echo '<label><input id="o_'.++$i.'" name="vote'.(($poll['type']==2)?'['.$o[0].']" type="checkbox" ':'" value="'.$o[0].'" type="radio"').' /> '.$o[1].'</label><br />';
}

echo '</div><div style="text-align: center">
	<input type="submit" value="OK" name="poll" />'.($poll['num'] ?
	'<a href="'.url('poll/'.$poll['ID']).'"><input type="button" value="'.$lang['results'].'" onclick="location=this.parentNode.href; return false" /></a>' : '') .
'</div>
</form>';

unset($poll,$option,$voted,$pollproc);