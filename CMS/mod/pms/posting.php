<?php /* Wysy³anie PW */
if(iCMS!=1) exit;

#Tytu³ strony
$content->title = $lang['write'];

#ID
$id = isset($URL[2]) && is_numeric($URL[2]) ? $URL[2] : 0;

#Wys³ane dane
if($_POST)
{
	#Dane
	$pm = array(
		'to'  => clean($_POST['to']),
		'txt' => clean($_POST['txt'],0,1),
		'bbc' => isset($cfg['bbcode']) && isset($_POST['bbc']) ? 1 : 0,
		'topic' => empty($_POST['topic']) ? $lang['notopic'] : clean($_POST['topic'],50,1)
	);

	#Wy¶lij lub edytuj
	if(isset($_POST['send']) OR isset($_POST['save']))
	{
		try
		{
			$db->beginTransaction();
			include './mod/pms/api.php';

			#Obiekt API
			$o = new PM;
			$o -> exceptions = true;
			$o -> bbcode = $pm['bbc'];
			$o -> topic = $pm['topic'];
			$o -> text = $pm['txt'];
			$o -> to = $pm['to'];

			#Wy¶lij - je¶li do siebie, zapisz jako kopiê robocz±
			if(isset($_POST['send']) && $o->to != $user['login'])
			{
				if($id && !isset($_POST['keep']))
				{
					$o -> status = 1;
					$o -> update($id); //Wy¶lij kopiê - zmiana w³a¶ciciela
				}
				else
				{
					$o -> send(); //Wy¶lij now± wiadomo¶æ
				}
				if(isset($_POST['sent']) && $cfg['pmLimit'] > $content->data['size'])
				{
					$o -> status = 4;
					$o -> send(1); //Zapisz do wys³anych
				}
			}
			else
			{
				if($id)
				{
					$o -> status = 3;
					$o -> update($id); //Aktualizuj kopiê robocz±
				}
				else
				{
					$o -> status = 3;
					$o -> send(); //Nowa kopia robocza
				}
			}
			$db -> commit();

			#Poka¿ listê PW
			$id = ($o->status == 3) ? 3 : 1;
			include './mod/pms/list.php';
			return 1;
		}
		catch(Exception $e)
		{
			$content->info($e->getMessage());
		}
	}
	#Podgl±d
	else
	{
		#BBCode
		if($pm['bbc'])
		{
			require './lib/bbcode.php';
			$preview = Emots(BBCode($pm['txt']));
		}
		else
		{
			$preview = Emots($pm['txt']);
		}
	}
	#Zapis od wys³anych?
	$saveSent = isset($_POST['sent']);
	$url = 'pms/edit/'.$id;
}

#Pobierz wiadomo¶æ
elseif($id)
{
	$pm = $db -> query('SELECT p.*,u.login as `to` FROM '.PRE.'pms p LEFT JOIN '
		.PRE.'users u ON p.usr=u.ID WHERE p.ID='.$id.' AND p.owner='.UID) -> fetch(2);

	#Nie istnieje?
	if(!$pm OR !is_numeric($pm['usr'])) return;

	#Dodaj Re: lub Fwd: do tytu³u
	if(isset($_GET['fwd']))
	{
		if(strpos($pm['topic'], 'Fwd:') === false)
		{
			$pm['topic'] = 'Fwd: '.$pm['topic'];
		}
		$url = 'pms/edit';
	}
	elseif($pm['st'] == 2)
	{
		if(strpos($pm['topic'], $lang['re']) === false)
		{
			$pm['topic'] = $lang['re'].$pm['topic'];
			$pm['txt'] = isset($cfg['bbcode']) ? '[quote]'.$pm['txt']."[/quote]\n" : '"'.$pm['txt']."\"\n";
		}
		$url = 'pms/edit';
	}
	else
	{
		$url = 'pms/edit/'.$id;
	}

	#Zapis od wys³anych?
	$saveSent = isset($_POST['sent']);
}
else
{
	if(isset($_GET['to']) && is_numeric($_GET['to']))
	{
		$to = $db->query('SELECT login FROM '.PRE.'users WHERE ID='.$_GET['to']) -> fetchColumn();
	}
	else
	{
		$to = '';
	}
	$pm = array(
		'to'  => $to,
		'bbc' => isset($cfg['bbcode']),
		'txt' => '',
		'topic' => '',
	);
	$url = 'pms/edit';
	$saveSent = false;
}

#Edytor JS
if(isset($cfg['bbcode']))
{
	$content->addScript(LANG_DIR.'edit.js');
	$content->addScript('lib/editor.js');
	$content->addScript('cache/emots.js');
}

#Formularz
$content->file[] = 'pms_posting';

#Do szablonu
$content->data += array(
	'pm'  => &$pm,
	'url'  => $url,
	'sent'  => $saveSent,
	'bbcode' => isset($cfg['bbcode']),
	'preview' => isset($preview) ? $preview : null
);
