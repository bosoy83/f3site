<?php
if(iCMS!=1) exit;

#Odczyt
$res = $db->query('SELECT ID,name,dsc,date FROM '.PRE.'arts WHERE cat='.$d.' AND
	access=1 ORDER BY priority,'.CatSort($cat['sort']).' LIMIT '.$st.','.$cfg['np']);

$res->setFetchMode(3);
$arts = array();
$total = 0;

#Lista
foreach($res as $art)
{
	$arts[] = array(
		'title' => $art[1],
		'desc'  => $art[2],
		'num'   => ++$st,
		'url'   => url('art/'.$art[0]),
		'date'  => $art[3]
	);
	++$total;
}

#Brak?
if($total===0) { $content->info($lang['noc']); return 1; }

#Strony
$pages = $cat['num'] > $total ? pages($page,$cat['num'],$cfg['np'],$d) : null;

#Do szablonu
$content->file[] = 'cat_arts';
$content->data += array(
	'pages' => &$pages,
	'arts'  => &$arts,
	'add_url' => admit($d,'CAT') ? url('edit/1') : null,
	'cats_url'=> url('cats/articles'),
	'cat_type'=> $lang['arts']
);
unset($res,$total,$art);