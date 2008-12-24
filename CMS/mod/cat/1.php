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
		'url'   => MOD_REWRITE ? '/art-'.$art[0] : '?co=art&amp;id='.$art[0],
		'date'  => $art[3]
	);
	++$total;
}

#Brak?
if($total===0) { $content->info($lang['noc']); return 1; }

#Strony
$pages = $cat['num'] > $total ? Pages($page,$cat['num'],$cfg['np'],'?d='.$d) : null;

#Do szablonu
$content->file[] = 'cat_arts';
$content->data += array(
	'pages' => &$pages,
	'arts'  => &$arts,
	'add_url' => Admit($d,'CAT') ? '?co=edit&amp;act=art' : null,
	'cat_type'=> $lang['arts']
);

unset($res,$total,$art);