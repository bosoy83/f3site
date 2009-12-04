<?php
if(iCMS!=1) exit;

#Pobierz kategoriê
if(isset($URL[2]) && is_numeric($URL[2]))
{
	$id = $URL[2];
	$q = $db->prepare('SELECT name,post,num,text FROM '.PRE.'bugcats WHERE (see=1 OR see=?) AND ID=?');
	$q->execute(array($nlang, $URL[2]));
	if(!$cat = $q->fetch(2)) return;
}
else return;

#Komunikat
if($cat['text'] && isset($cfg['bugsUp'])) $content->info(nl2br($cat['text']));

#Strona
if(isset($_GET['page']) && $_GET['page']>1)
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
	(admit('BUGS') ? '' : ' AND status!=5').' ORDER BY ID DESC LIMIT ?,?');
$res -> bindValue(1, $id, 1);
$res -> bindValue(2, $st, 1);
$res -> bindValue(3, $cfg['bugsNum'], 1);
$res -> execute();

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
		'url'    => url('bugs/'.$x['ID']),
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
	$pages = pages($page, $cat['num'], $cfg['bugsNum'], url('bugs/list/'.$id);
}
else
{
	$pages = '';
}

#Szablony
$content->title = $cat['name'];
$content->data = array(
	'issue'   => &$all,
	'postURL' => BugRights($cat['post']) ? url('bugs/post', '?f='.$id) : false
);