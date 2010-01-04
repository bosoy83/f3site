<?php
if(iCMS!=1) return;
require LANG_DIR.'comm.php';

#Brak ID
if(isset($URL[1]) && is_numeric($URL[1])) $id = $URL[1]; else return;

#B��dy
$error = array();
$preview = null;

#Akceptuj, usu�
if(isset($_POST['act']) && $id)
{
	switch($_POST['act'])
	{
		case 'ok':
		if(admit('CM')) $db->exec('UPDATE '.PRE.'comms SET access=1 WHERE ID='.$id);
		break;
		case 'del':
		if($comm = $db->query('SELECT CID,TYPE FROM '.PRE.'comms WHERE ID='.$id)->fetch(3))
		{
			if(($comm[0] == UID && $comm[1] == '10') OR admit('CM'))
			{
				if($db->exec('DELETE FROM '.PRE.'comms WHERE ID='.$id) && $comm[1] == '5')
				{
					$db->exec('UPDATE '.PRE.'news SET comm=comm-1 WHERE ID='.$comm[0]);
				}
			}
		}
	}
	echo 'OK';
	exit;
}

#Je�li jest typ w URL, dodaj nowy komentarz
if(isset($URL[2]))
{
	#Go�� nie mo�e pisa�?
	if(!UID && !isset($cfg['commGuest'])) $error[] = $lang['c11'];

	#TYP JEST LICZB�
	$type = (int)$URL[2];

	#Sprawd�, czy pozycja jest w��czona
	if(!isset($_SESSION['CV'][$type][$id]))
	{
		switch($type)
		{
			case 10: $if = 'users WHERE lv>0 AND ID='.$id; break;
			case 59: $if = 'pages WHERE access=1 AND ID='.$id; break;
			case 15: $if = 'polls WHERE access="'.LANG.'"'; break;
			default: $data = parse_ini_file('./cfg/types.ini',1);
				$if = isset($data[$type]['comm']) ? $data[$type]['table'].' i INNER JOIN '.
				PRE.'cats c ON i.cat=c.ID WHERE i.access=1 AND c.access!=3 AND c.opt&2 AND i.ID='.$id : '';
		}
		if(!$if OR !dbCount($if))
		{
			$error[] = $lang['c11'];
		}
	}
}
else
{
	if(!admit('CM'))
	{
		$error[] = $lang['c11']; #Edycja komentarza - brak praw
	}
	$type = null;
}

#Tytu� strony
$content->title = $type ? $lang['addComm'] : $lang['c1'];

#Dane POST
if($_POST)
{
	#Dane
	$c = array(
		'name' => clean($_POST['name'], 30, 1),
		'text' => clean($_POST['text'], 0, 1)
	);

	#D�ugo��
	if(isset($c['name'][51]) || isset($c['text'][1999]))
	{
		$error[] = $lang['c5'];
	}
	if(empty($c['text']))
	{
		$error[] = $lang['c4'];
	}

	#Autor i linki w tre�ci
	if($type)
	{
		if(UID)
		{
			$c['author'] = UID;
		}
		else
		{
			$c['author'] = empty($_POST['author']) ? $lang['c9'] : clean($_POST['author'],30,1);
			if(!isset($cfg['URLs']))
			{
				if(strpos($c['author'],'://') OR strpos($c['text'],'://') OR strpos($c['name'],'://'))
				{
					$error[] = $lang['c12'];
				}
			}
		}
	}

	#Podgl�d
	if(isset($_POST['prev']) && !$error)
	{
		$preview = nl2br(Emots($c['text']));
		if(isset($cfg['bbcode']))
		{
			try
			{
				include './lib/bbcode.php';
				$preview = BBCode($preview, 1);
			}
			catch(Exception $e)
			{
				$error[] = $lang['unclosed'];
			}
		}
	} 

	#Zapis
	elseif(isset($_POST['save']))
	{
		if($type)
		{
			if(!UID && isset($cfg['captcha']) && (empty($_POST['code']) || $_POST['code']!=$_SESSION['code']))
			{
				$error[] = $lang['c2'];
			}

			#Anty-flood
			if(isset($_SESSION['post']) && $_SESSION['post']>time()) $error[] = $lang['c3'];

			#Moderowa�? + IP
			$c['access'] = !isset($cfg['moderate']) || LEVEL>1 || admit('CM') ? 1 : 0;
			$c['ip'] = $_SERVER['REMOTE_ADDR'];
			$c['guest'] = UID ? 0 : 1;
		}

		#START
		if(!$error)
		{
			try
			{
				$db->beginTransaction();
				if($type)
				{
					$q = $db->prepare('INSERT INTO '.PRE.'comms (TYPE,CID,name,access,author,guest,ip,date,text)
						VALUES ('.$type.','.$id.',:name,:access,:author,:guest,:ip,'.$_SERVER['REQUEST_TIME'].',:text)');

					#News?
					if($type==5) $db->exec('UPDATE '.PRE.'news SET comm=comm+1 WHERE ID='.$id);
				}
				else
				{
					#Zapytanie
					$q = $db->prepare('UPDATE '.PRE.'comms SET name=:name, text=:text WHERE ID='.$id);
				}
				$q->execute($c);
				$db->commit();

				#Ustaw anty-flood
				$_SESSION['post'] = time() + $cfg['antyFlood'];

				#Info lub komentarze (AJAX)
				if(JS)
				{
					$content->file = array();
					include './lib/comm.php';
					comments($id, $type);
					return 1;
				}
				else
				{
					$content->message(($type && $c['access']!=1) ? $lang['c6'] : $lang['c7']);
				}
			}
			catch(PDOException $e)
			{
				$content->info($lang['c10'].$e->getMessage());
			}
		}
	}
}

#Nowy lub edycja
else
{
	if($type)
	{
		$c = array('name'=>'', 'author'=>'', 'text'=>'', 'guest'=>(UID)?0:1);
	}
	else
	{
		$c = $db->query('SELECT * FROM '.PRE.'comms WHERE ID='.$id)->fetch(2);
	}
}

#B��d?
if($error) $content->info('<ul><li>'.join('</li><li>',$error).'</li></ul>');

#Szablon
$content->data = array(
	'comment' => $c,
	'code'    => $type && !UID && isset($cfg['captcha']) ? true : false,
	'author'  => $type && !UID ? true : false,
	'preview' => $preview,
	'url'     => url('comment/'.$id.($type ? '/'.$type : ''))
);

#JS
if(isset($cfg['bbcode']))
{
	$content->addScript(LANG_DIR.'edit.js');
	$content->addScript('cache/emots.js');
	$content->addScript('lib/editor.js');
}
