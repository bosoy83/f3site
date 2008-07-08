<?php /* Lista pozycji - nowoœci */
if(iCMS!=1) exit;

#Odczyt
$res = $db->query('SELECT n.*,login FROM '.PRE.'news n LEFT JOIN '.PRE.'users u ON
	n.author=u.ID WHERE n.'.$cats.' AND n.access=1 ORDER BY n.ID DESC LIMIT '.$st.','.$cfg['newsNum']);

#Prawa
$rights = Admit('N') OR Admit($d,'CAT') ? true : false;

#Komentarze
$comm = ($cat['opt']&2 && isset($cfg['ncomm'])) ? true : false;

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
		'full_url' => $n['opt']&4 ? '?co=news&amp;id='.$n['ID'] : false,

		#Edytuj URL
		'edit_url' => $rights ? '?co=edit&amp;act=new&amp;id='.$n['ID'] : false,

		#Komentarze URL
		'comm_url' => $comm ? '?co=news&amp;id='.$n['ID'].'#comments' : false,

		#Autor URL
		'wrote_url' => '?co=user&amp;id='.$n['author']
	);

	#Treœæ - Emoty
	if($n['opt']&2)  $n['txt'] = Emots($n['txt']);

	#Zawijanie
	if($n['opt']&1)  $n['txt'] = nl2br($n['txt']);

	#Przypisz treœæ
	$news[$num]['text'] = $n['txt'];   ++$num;
}

#Do szablonu
if($num > 0)
{
	$content->file[] = 'cat_news';
	$content->data += array(
		'news' => &$news,
		'add_url' => $rights ? '?co=edit&amp;act=news' : null,
		'cat_type'=> $lang['news']
	);
}
$res=null;
unset($comm,$rights,$n);