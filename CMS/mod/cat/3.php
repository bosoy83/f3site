<?php
if(iCMS!=1) exit;

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
		'url'   => MOD_REWRITE ? 'img/'.$x[0] : '?co=img&amp;id='.$x[0],
		'date'  => genDate($x[2], false)
	);

	/*</tr>
  if($if==$cfg['imgsRow'] || $if==$cfg['inp'])
  {
		echo '</tr>';
		$if=1;
  }
	else { ++$if; }*/
}

#Brak?
if($total === 0): $content->info($lang['noc']); return 1; endif;

#Strony
if($cat['num'] > $total)
{
	$pages = Pages($page,$cat['num'],$cfg['np'], MOD_REWRITE ? '/'.$d : '?d='.$d);
}
else $pages = null;

#Do szablonu
$content->file = 'cat_images';
$content->data += array(
	'pages' => &$pages,
	'img'   => &$img,
	'add_url' => Admit($d,'CAT') ? '?co=edit&amp;act=link' : null,
	'cat_type'=> $lang['imgs']
);

unset($res,$total,$x);