<?php /* Lista pozycji - nowoœci */
if(iCMS!=1) exit;

#Zacznij od...
if($st != 0) $st = ($page-1) * $cfg['newsNum'];

#Odczyt
$res = $db->query('SELECT n.*,login FROM '.PRE.'news n LEFT JOIN '.PRE.'users u ON
	n.author=u.ID WHERE n.'.$cats.' AND n.access=1 ORDER BY n.ID DESC LIMIT '.$st.','.$cfg['newsNum']);

#Prawa
$rights = admit($d,'CAT') ? true : false;

#Komentarze
$comm = $cat['opt']&2 && isset($cfg['ncomm']) ? true : false;

#URL
$userURL = url('user/');
$fullURL = url('news/');
$editURL = url('edit/5/');

#Tu zapisuj:
$news = array();
$num = 0;

foreach($res as $n)
{
	#Data, itd.
	$news[] = array(
		'title' => $n['name'],
		'date'  => genDate($n['date']),
		'wrote' => $n['login'],
		'comm'  => $n['comm'],
		'img'   => $n['img'],

		#Glowny URL
		'url' => $fullURL.$n['ID'],

		#Komentarze URL
		'comm_url' => $comm ? $fullURL.$n['ID'] : false,

		#Pe³ny URL
		'full_url' => $n['opt']&4 ? $fullURL.$n['ID'] : false,

		#Edytuj URL
		'edit_url' => $rights ? $editURL.$n['ID'] : false,

		#Autor URL
		'wrote_url' => $userURL.urlencode($n['login'])
	);

	#Treœæ - Emoty
	if($n['opt']&2) $n['txt'] = emots($n['txt']);

	#Zawijanie
	if($n['opt']&1) $n['txt'] = nl2br($n['txt']);

	#Przypisz treœæ
	$news[$num++]['text'] = $n['txt'];
}

#Strony
if(isset($cfg['newsPages']) && $cat['num'] > $num)
{
	$pages = pages($page, $cat['num'], $cfg['newsNum'], url($d), 0, '/');
}
else
{
	$pages = null;
}

#Do szablonu
$view->add('cat_news', array(
	'news'  => &$news,
	'pages' => &$pages,
	'add'   => $rights ? url('edit/5') : null,
	'cats'  => url(isset($cfg['allCat']) ? 'cats' : 'cats/news'),
	'type'  => isset($cfg['allCat']) ? $lang['cats'] : $lang['news']
));
unset($res,$comm,$rights,$n);
