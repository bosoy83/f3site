<?php
if(iCMS!=1 OR !admit('CM')) exit;
require LANG_DIR.'comm.php';

#Tytu� strony
$content->title = $lang['comms'];

#Akcje
if(isset($_POST['a']))
{
	$del = $ok = $no = array();
	foreach($_POST['a'] as $id=>$x)
	{
		switch($x)
		{
			case 1: $ok[] = (int)$id; break;
			case 0: $no[] = (int)$id; break;
			case 2: $del[] = (int)$id;
		}
		try
		{
			if($del) $db->exec('DELETE FROM '.PRE.'comms WHERE ID IN('.join(',',$del).')');
			if($no) $db->exec('UPDATE '.PRE.'comms SET access=0 WHERE ID IN('.join(',',$no).')');
			if($ok) $db->exec('UPDATE '.PRE.'comms SET access=1 WHERE ID IN('.join(',',$ok).')');
		}
		catch(PDOException $e)
		{
			$content->info('ERROR: '.$e);
		}
	}
}

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
{
	$page = (int)$_GET['page'];
	$st = ($page-1)*20;
}
else
{
	$page = 1;
	$st = 0;
}

#Tylko do akceptacji?
$q = isset($URL[1]) ? ' WHERE access!=1' : '';

#Razem
$total = dbCount('comms'.$q);
$com   = array();

#Pobierz ostatnie komentarze
$res = $db->query('SELECT c.*,u.login FROM '.PRE.'comms c LEFT JOIN '.PRE.
	'users u ON c.author=u.ID AND c.guest!=1'.$q.' ORDER BY c.ID DESC LIMIT '.$st.',20');

#BBCode?
if(isset($cfg['bbcode'])) include_once('./lib/bbcode.php');

#Typy kategorii
$type = parse_ini_file('cfg/types.ini',1);

foreach($res as $x)
{
	switch($x['TYPE'])
	{
		case '10': $co = 'user'; break;
		case '59': $co = 'page'; break;
		case '15': $co = 'poll'; break;
		default: $co = isset($type[$x['TYPE']]) ? $type[$x['TYPE']]['name'] : null;
	}
	$com[] = array(
		'text'  => nl2br(emots(isset($cfg['bbcode']) ? BBCode($x['text']) : $x['text'])),
		'date'  => genDate($x['date'],1),
		'url'   => url('comment/'.$x['ID']),
		'item'  => $co ? url($co.'/'.$x['CID']) : null,
		'id'    => $x['ID'],
		'title' => $x['name'],
		'user'  => $x['login'] ? $x['login'] : $x['author'],
		'ip'    => $x['ip'],
		'access' => $x['access'],
		'profile' => $x['login'] ? url('user/'.urlencode($x['login'])) : null
	);
}

$content->data = array(
	'comment' => $com,
	'total'   => $total,
	'url'     => url('moderate'),
	'nourl'   => url('moderate/hidden'),
	'pages'   => pages($page,$total,20,'moderate',1)
);