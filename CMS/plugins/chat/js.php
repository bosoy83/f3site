<?php if(JS!=1) exit;
require './cfg/chat.php';

#Ostatni pobrany ID
if(isset($_SESSION['chatLast']))
{
	$id = $_SESSION['chatLast'];
}
elseif(isset($_POST['lastid']) && is_numeric($_POST['lastid']))
{
	$id = $_SESSION['chatLast'] = $_POST['lastid'];
}
else
{
	$id = 0;
}

#Pobierz ostatnie rekordy
$msg = array();
$res = $db->prepare('SELECT * FROM '.PRE.'chat WHERE ID>? ORDER BY ID DESC LIMIT 20');
$res -> bindValue(1, $id, 1);
$res -> execute();

foreach($res as $x)
{
	array_unshift($msg, array(
		'id'   => $x['ID'],
		'nick' => $x['nick'],
		'uid'  => $x['uid'],
		'msg'  => $x['msg'],
		'time' => $x['time']
	));
}

#Return messages as JSON
echo json_encode($msg);

#Dodaj wys³any rekord
if($_POST)
{
	$msg = array(
		0 => $_SERVER['REQUEST_TIME'],
		1 => UID,
		2 => UID ? $user['login'] : clean($_POST['msg'], $cfg['chatNickLen']),
		3 => clean($_POST['msg'], $cfg['chatMsgLen'], 1)
	);

	#Czy mo¿e wstawiaæ linki
	if(!isset($cfg['URLs']))
	{
		if(strpos($msg[3],'://') OR strpos($msg[3],'www.')!==false)
		{
			return;
		}
	}

	try
	{
		$q = $db->prepare('INSERT INTO '.PRE.'chat (time,uid,nick,msg) VALUES (?,?,?,?)');
		$q -> execute($msg);
		$_SESSION['chatLast'] = $db->lastInsertId();
	}
	catch(PDOException $e) {echo $e;}
}
elseif($msg)
{
	$_SESSION['chatLast'] = $msg['id'];
}