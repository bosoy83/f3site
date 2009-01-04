<?php /* Archiwum wszystkich nowo¶ci */
if(iCMS!=1) exit;
include './cfg/content.php';

#Tytu³
$content->title = $lang['archive'];

#Lista nowo¶ci
if($id)
{
	#Ca³y rok / 1 miesi±c
	if(isset($cfg['archYear']) && $id == (int)$id)
	{
		$q = 'date BETWEEN \''.$id.'-01-01\' AND \''.$id.'-12-31\'';
	}
	elseif($id == (float)$id)
	{
		$date = explode('.',$id);
		if(!isset($date[1][1])) $date[1] = '0'.$date[1][0];
		$q = 'date BETWEEN \''.$date[0].'-'.$date[1].'-01\' AND \''.$date[0].'-'.$date[1].'-31\'';
	}
	else return;
	
	#Pobierz newsy
	$res = $db->query('SELECT ID,name,date FROM '.PRE.'news WHERE '.$q.' AND access=1 ORDER BY ID DESC');

	$res->setFetchMode(3);
	$news = array();
	$num  = 0;

	#Przygotuj dane
	foreach($res as $n)
	{
		$news[] = array(
			'num'  => ++$num,
			'date' => genDate($n[2], true),
			'title'=> $n[1],
			'url'  => '?co=news&amp;id='.$n[0]
		);
	}
	$res=null;

	#Do szablonu
	$content->data['news'] =& $news;
	$content->data['newslist'] = true;
	return 1;
}

#Lista lat i miesiêcy
$date = $db->query('SELECT date FROM '.PRE.'news LIMIT 1') -> fetchColumn();

#Brak nowo¶ci?
if(!$date[0]) return;

#Data 1. newsa
$year = (int)$date[0].$date[1].$date[2].$date[3];
$mon  = (int)$date[5].$date[6];

#Bie¿±cy miesi±c i rok
$m = date('n');
$y = date('Y');

$dates = array();

#Lata
if(isset($cfg['archYear']))
{
	do {
		$dates[] = array(
			'url'   => '?co=archive&amp;id='.$y,
			'title' => $y--
		);
	}
	while( $y>$year && $y>1981 );
}

#Miesi±ce
elseif($mon)
{
	include LANG_DIR.'date.php';
	do {
		$dates[] = array(
			'url'   => '?co=archive&amp;id='.$y.'.'.$m,
			'title' => $months[$m].' '.$y
		);
		if($m==1)
		{
			$m=12; --$y; $dates[] = array('url' => null);
		}
		else --$m;
	}
	while( $y>$year || $m>=$mon );
}
unset($y,$m,$date);

#Do szablonu
$content->data['dates'] =& $dates;
$content->data['newslist'] = false;
