<?php
if(iCMS!=1) exit;

#Może zobaczyć IP?
$right = (LEVEL > 2 && admit('GB'));

#Usuń wpisy
if(isset($_POST['del']) && $right && isset($_POST['x']))
{
	$del = array();
	foreach($_POST['x'] as $key=>$val)
	{
		$del[] = (int)$key;
	}
	$db->exec('DELETE FROM '.PRE.'guestbook WHERE ID IN ('.join(',', $del).')');
}

#Strona
if(isset($_GET['page']) && $_GET['page'] > 1)
{
	$page = $_GET['page'];
	$st = ($page-1) * $cfg['gbNum'];
}
else
{
	$page = 1;
	$st = 0;
}

#Ilość wpisów
$total = dbCount('guestbook WHERE lang="'.LANG.'"');
$num = 0;
$all = array();

#Pobierz wpisy i loginy
$query = $db->prepare('SELECT * FROM '.PRE.'guestbook WHERE lang=? ORDER BY ID DESC LIMIT ?,?');

#Podepnij dane do zapytania bezpiecznie
$query -> bindValue(1, LANG);
$query -> bindValue(2, $st, 1);
$query -> bindValue(3, $cfg['gbNum'], 1); //PDO::PARAM_INT
$query -> execute();

#BBCode
if(isset($cfg['bbcode'])) require './lib/bbcode.php';

#Lista
foreach($query as $x)
{
	$all[] = array(
		'id'    => $x['ID'],
		'who'   => $x['UID'] ? '<a href="'.url('user/'.urlencode($x['who'])).'">'.$x['who'].'</a>' : $x['who'],
		'date'  => genDate($x['date'], true),
		'www'   => $x['www'],
		'text'  => emots(isset($cfg['bbcode']) ? BBCode($x['txt']) : $x['txt']),
		'gg'    => $x['gg'],
		'icq'   => $x['icq'],
		'tlen'  => $x['tlen'],
		'skype' => $x['skype'],
		'jabber'=> $x['jabber'],
		'mail'  => str_replace('@', '&#64;', $x['mail']),
		'ip'    => $right ? $x['ip'] : false,
		'edit'  => $right ? url('guestbook/post/'.$x['ID']) : false
	);
	++$num;
}

#Strony
if($total > $num)
{
	$pages = pages($page, $total, $cfg['gbNum'], url('guestbook'));
}
else
{
	$pages = false;
}

#Szablon
$content->title = $lang['guestbook'];
$content->data = array(
	'post'    => &$all,
	'pages'   => &$pages,
	'intro'   => &$cfg['gbIntro'],
	'rights'  => $right,
	'postURL' => ($cfg['gbPost']==1 || (UID && $cfg['gbPost']==2)) &&
		(stripos($cfg['gbBan'],$_SERVER['REMOTE_ADDR']) === false) ? url('guestbook/post') : false
);
