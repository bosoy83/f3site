<?php
if(iCMS!=1) exit;

#Skin
$content->title = 'Chat';
$content->dir = './plugins/chat/';
$content->cache = './cache/chat/';
$content->css('plugins/chat/chat.css');
$content->script('plugins/chat/chat.js');

#Config
if(file_exists('./cfg/chat.php'))
{
	require './cfg/chat.php';
}
else
{
	$content->message('Chat is NOT installed!');
}

#Messages
$num = -1;
$msg = array();
$res = $db->prepare('SELECT * FROM '.PRE.'chat ORDER BY ID DESC LIMIT ?');
$res -> bindValue(1, $cfg['chatLast'], 1);
$res -> execute();

foreach($res as $x)
{
	array_unshift($msg, array(
		'id'   => $x['ID'],
		'uid'  => $x['uid'],
		'msg'  => $x['msg'],
		'nick' => $x['nick'],
		'time' => date('H:i', $x['time']),
		'user_url' => url('user/'.urlencode($x['nick'])),
	));
	++$num;
}

#Template
$content->add('chat', array(
	'message' => &$msg,
	'lastID'  => $msg ? ($_SESSION['chatLast'] = $msg[$num]['id']) : 0,
	'nick'    => UID ? $user['login'] : 'Guest'
));