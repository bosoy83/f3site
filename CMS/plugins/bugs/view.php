<?php
if(iCMS!=1) exit;

#Prawa
if(Admit('BUGS'))
{
	$rights = 1;
}
else
{
	$rights = 0;
}

#Pobierz zg³oszenie - FETCH_ASSOC
$bug = $db->query('SELECT b.*, c.name as catName, c.rate FROM '.PRE.'bugs b INNER JOIN '.PRE.'bugcats c ON b.cat = c.ID WHERE b.ID='.$id.' AND (c.see=1 OR c.see="'.$nlang.'")') -> fetch(2);

#Brak
if(!$bug) return;

#Niemoderowany?
if($bug['status']==5 && $bug['author']!=UID && !$rights)
{
	Header('Location: '.URL.'?co=bugs');
	return;
}

#BBCode
if(isset($cfg['bbcode']))
{
	require 'lib/bbcode.php';
	$bug['text'] = BBCode($bug['text']);
}

#Data, autor
$bug['date'] = genDate($bug['date']);
$bug['who']  = $bug['UID'] ? Autor($bug['UID']) : $bug['who'];
$bug['text'] = Emots(nl2br($bug['text']));
$bug['level'] = $lang['L'.$bug['level']];
$bug['status'] = $lang['S'.$bug['status']];

#Ocena
if($bug['rate'] == 2)
{
	$bug['mark']  = $bug['pos'] ? $bug['pos'] : $lang['lack'];
	$bug['marks'] = $bug['neg'] ? $bug['neg'] : 0;
	$content->addCSS(SKIN_DIR.'rate.css');
}

#Szablon
$content->title = $bug['name'];
$content->file = 'view';
$content->data = array(
	'bug'   => &$bug,
	'edit'  => $rights OR ($bug['poster']==UID && isset($cfg['bugsEdit'])),
	'hands' => $bug['rate'] == 1,
	'stars' => $bug['rate'] == 2,
	'editStatus' => $rights
);