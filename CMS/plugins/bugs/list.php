<?php
if(iCMS!=1) exit;

#Pobierz kategoriê
if(!$cat = $db->query('SELECT name,post,num,text FROM '.PRE.'bugcats
	WHERE (see=1 OR see="'.$nlang.'") AND ID='.$id)->fetch(2)) return;

#Komunikat
if($cat['text'] && isset($cfg['bugsUp'])) $content->info(nl2br($cat['text']));

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
{
	$page = $_GET['page'];
	$st = ($page-1)*$cfg['bugsnum'];
}
else
{
	$page = 1;
	$st = 0;
}
 
#Pobierz zg³oszenia
$res = $db->prepare('SELECT ID,name,num,date,status,level FROM '.PRE.'bugs WHERE cat=?'.
	(Admit('BUGS') ? '' : ' AND status!=5').' ORDER BY ID DESC LIMIT ?,?');
$res -> execute(array($id, $st, $cfg['bugsNum']));

$all = array();
$num = 0;

foreach($res as $x)
{
	$all[] = array(
		'id'     => $x['ID'],
		'title'  => $x['name'],
		'status' => $x['status'],
		'level'  => $x['level'],
		'num'    => $x['num'],
		'url'    => '?co=bugs&amp;id='.$x['ID'],
		'date'   => genDate($x['date'], 1),
		'class'  => BugIsNew('', $x['date']) ? 'new' : 'old',
		'levelText' => $lang['L'.$x['level']]
	);
	++$num;
}

#Strony
if(!$num)
{
	$content->info($lang['noc']);
}
elseif($cat['num'] > $num)
{
	$pages = Pages($page,$cat['num'],$cfg['bugsNum'],'?co=bugs&amp;act=l&amp;id='.$id);
}
else
{
	$pages = '';
}

#Szablony
$content->title = $cat['name'];
$content->data = array(
	'issue'   => &$all,
	'postURL' => BugRights($cat['post']) ? '?co=bugs&amp;act=e&amp;f='.$id : false
);