<?php
if(iCMS!=1) exit;

#Odczyt
$res = $db->query('SELECT ID,name,dsc,adr,count,nw FROM '.PRE.'links WHERE '.$cats.
	' AND access=1 ORDER BY priority, '.CatSort($cat['sort']).' LIMIT '.$st.','.$cfg['np']);

$res->setFetchMode(3);
$total = 0;
$links = array();
$count = isset($cfg['lcnt']) ? 1 : 0;

#Lista
foreach($res as $link)
{
	$links[] = array(
		'title' => $link[1],
		'url'   => $count ? 'go.php?link='.$link[0] : $link[3],
		'views' => $count ? $link[4] : null,
		'nw'    => $link[5],
		'desc'  => $link[2],
		'num'   => ++$st
	);
	++$total;
}

#Brak?
if($total === 0): $content->info($lang['noc']); return 1; endif;

#Strony
$pages = $cat['num'] > $total ? pages($page,$cat['num'],$cfg['np'],$d) : null;

#Do szablonu
$content->file[] = 'cat_links';
$content->data += array(
	'pages' => &$pages,
	'links' => &$links,
	'count' => $count,
	'add_url' => admit($d,'CAT') ? url('edit/4') : null,
	'cats_url'=> url('cats/links'),
	'cat_type'=> $lang['links']
);
unset($res,$link,$total);