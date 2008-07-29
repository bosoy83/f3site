<?php
if(iCMS!=1) exit; //Stos: cat='.$d.

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
		'nw'    => $link[4],
		'desc'  => $link[2],
		'num'   => ++$st
	);
	++$total;
}

#Brak?
if($total === 0): $content->info($lang['noc']); return 1; endif;

#Strony
$pages = $cat['num'] > $total ? Pages($page,$cat['num'],$cfg['np'],'?d='.$d) : null;

#Do szablonu
$content->file[] = 'cat_links';
$content->data += array(
	'pages' => &$pages,
	'links' => &$links,
	'count' => $count,
	'add_url' => Admit('L') || Admit($d,'CAT') ? '?co=edit&amp;act=link' : null,
	'cat_type'=> $lang['links']
);

unset($res,$link,$total);