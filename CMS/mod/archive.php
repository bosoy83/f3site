<?php /* Archiwum wszystkich nowo¶ci */
if(iCMS!=1) exit;
include('./cfg/c.php');

#Tytu³
$content->title = $lang['archive'];

#Lista nowo¶ci
if($id)
{
	$date = explode('.',$_GET['id']);

	#Pobierz
	$res = $db->query('SELECT ID,name,date FROM '.PRE.'news WHERE YEAR(date)='.
		$date[0] . ((!empty($date[1]) && !isset($cfg['archyear'])) ? ' AND MONTH(date)='.
		$date[1] : '') . ' AND access=1 ORDER BY ID DESC');

	$res->setFetchMode(3);
	$news = array();

	#Przygotuj dane
	foreach($res as $n)
	{
		$news[] = array(
			'date' => genDate($n[1]),
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
$date = explode('-',$date);

#Brak nowo¶ci?
if(!$date[0]) return;

#Usuñ 0
if($date[1] < 10) $date[1] = str_replace('0','',$date[1]);

#Miesi±c i rok
$m = date('M');
$y = date('Y');

#Zapisz do:
$dates = array();

#Lata
if($cfg['archyear'] == 1)
{
	do {
		$dates[] = array(
			'url'   => '?co=archive&amp;id='.$y,
			'title' => $y--
		);
	}
	while( $y>$date[0] && $y>1981 );
}

#Miesi±ce
elseif($date[1])
{
	do {
		$dates[] = array(
			'url'   => '?co=arch&amp;id='.$y.'.'.$m,
			'title' => $mlang[$m].' '.$y
		);
		if($m==1)
		{
			$m=12; --$y; $dates[] = array('url' => null);
		}
		else --$m;
	}
	while( $y!=$date[0] || $m!=$date[1] );
}
unset($y,$m,$date);

#Do szablonu
$content->data['dates'] =& $dates;
$content->data['newslist'] = false;
?>
