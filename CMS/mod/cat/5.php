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
$comm = ($cat['opt']&2 && isset($cfg['ncomm'])) ? true : false;

#URL
$userURL = url('user/');
$editURL = url('edit/5/');
$fullURL = url('news/');

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

		#Pe³ny URL
		'full_url' => $n['opt']&4 ? $fullURL.$n['ID'] : false,

		#Edytuj URL
		'edit_url' => $rights ? $editURL.$n['ID'] : false,

		#Komentarze URL
		'comm_url' => $comm ? $fullURL.$n['ID'] : false,

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
	$pages = pages($page, $cat['num'], $cfg['newsNum'], $d, 0, '/');
}
else
{
	$pages = null;
}

#Do szablonu
$content->file[] = 'cat_news';
$content->data += array(
	'news' => &$news,
	'pages' => &$pages,
	'add_url' => $rights ? url('edit/5') : null,
	'cats_url'=> url('cats/news'),
	'cat_type'=> $lang['news']
);
unset($res,$comm,$rights,$n);