<?php
if(iCMS!=1) exit;

#Zacznij od...
if($st != 0) $st = ($page-1) * $cfg['inp'];

#Odczyt
$res = $db->query('SELECT ID,name,date,filem FROM '.PRE.'imgs WHERE '.$cats.
	' AND access=1 ORDER BY priority, '.CatSort($cat['sort']).' LIMIT '.$st.','.$cfg['inp']);

$res->setFetchMode(3);
$total = 0;
$img = array();

#Lista
foreach($res as $x)
{
	$img[] = array(
		'num'   => ++$total,
		'title' => $x[1],
		'src'   => $x[3],
		'url'   => '?co=img&amp;id='.$x[0],
		'date'  => genDate($x[2])
	);
}

#Brak?
if($total === 0): $content->info($lang['noc']); return 1; endif;

#Strony
if($cat['num'] > $total)
{
	$pages = Pages($page,$cat['num'],$cfg['np'], '?d='.$d);
}
else
{
	$pages = null;
}

#Do szablonu
$content->file[] = 'cat_images';
$content->data += array(
	'pages' => &$pages,
	'img'   => &$img,
	'add_url' => Admit($d,'CAT') ? '?co=edit&amp;act=4' : null,
	'cat_type'=> $lang['imgs']
);

unset($res,$total,$x);