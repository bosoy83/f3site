<?php
if(iCMS!=1) exit;

#Powitanie
if($cfg['bugsIntro']) $content->info($cfg['bugsIntro']);

#Pobierz kategorie
$res = $db->query('SELECT c.ID,c.name,c.dsc,c.post,c.num,c.last,s.title FROM '.PRE.'bugcats c LEFT JOIN '.PRE.'bugsect s ON c.sect = s.ID WHERE c.see=1 OR c.see="'.LANG.'" ORDER BY s.seq,c.name');

$cat  = array();
$sect = '';
$show = 0;
$num  = 0;

foreach($res as $x)
{
	#Sekcja
	if($x['title'] != $sect)
	{
		$sect = $x['title'];
		$show = 1;
	}
	elseif($show == 1)
	{
		$show = 0;
	}
	$cat[] = array(
		'url'    => url('bugs/list/'.$x['ID']),
		'num'    => $x['num'],
		'title'  => $x['name'],
		'desc'   => $x['dsc'],
		'class'  => BugIsNew('', $x['last']) ? 'catNew' : 'cat',
		'section'=> $show ? $sect : false
	);
	++$num;
}

#Brak kategorii
if(!$num)
{
	$content->info($lang['nocats']);
}
else
{
	$content->file = 'cats';
	$content->addCSS('plugins/bugs/style/bugs.css');
	$content->title = $lang['BUGS'];
	$content->data = array('cat' => &$cat);
}