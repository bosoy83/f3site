<?php
if(iCMS!=1 || !$id) return;
require LANG_DIR.'comm.php';

#Je¶li istnieje TYP pozycji w GET - nowy komentarz, inaczej - edycja
if(isset($_GET['type']))
{
	$type = (int)$_GET['type'];

	#Go¶æ nie mo¿e pisaæ?
	if(LOGD!=1 && $cfg['commGuest']!=1) $error[] = $lang['c11'];
}
elseif(Admit('CM'))
{
	$type = null;
}
else
{
	$content->info($lang['noex']); return; //B³êdny typ
}

#Tytu³ strony
$content->title = $type ? $lang['addComm'] : $lang['c1'];

#B³êdy
$error = array();
$preview = null;

#Dane POST
if($_POST)
{
	#Dane
	$c = array(
		'name'  => Clean($_POST['name'], 30, 1),
		'text'  => Clean($_POST['text'], 0, 1)
	);

	#D³ugo¶æ
	if(isset($c['name'][51]) || isset($c['text'][801]))
	{
		$error[] = $lang['c5'];
	}
  if(empty($c['text']))
  {
		$error[] = $lang['c4'];
  }

	#Podgl±d
	if(isset($_POST['prev']) && !$error)
	{
		$preview = nl2br(Emots($c['text']));
		if(isset($cfg['bbcode']))
		{
			include './lib/bbcode.php';
			$preview = BBCode($preview);
		}
	} 

	#Zapis
	elseif(isset($_POST['save']))
	{
		if($type) //NOWY KOM.
		{
			if(LOGD==1)
			{
				$c['author'] = UID; //Autor
			}
			else
			{
				$c['author'] = empty($_POST['author']) ? $lang['c9'] : Clean($_POST['author'],30);

				#KOD
				if($cfg['captcha']==1 && (empty($_POST['code']) || $_POST['code']!=$_SESSION['code']))
				{
					$error[] = $lang['c2'];
				}
			}
			#Anty-flood
			if(isset($_SESSION['post']) && $_SESSION['post']>time()) $error[] = $lang['c3'];

			#Moderowaæ? + IP
			$c['access'] = !isset($cfg['moderate']) || LEVEL>1 || Admit('CM') ? 1 : 0;
			$c['ip'] = $_SERVER['REMOTE_ADDR'];
			$c['guest'] = LOGD==1 ? 0 : 1;
		}

		#START
		if(!$error)
		{
			$db->beginTransaction();
			try
			{
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

				#Info
				$content->info( (($type && $c['access']!=1) ? $lang['c6'] : $lang['c7']) ); return 1;
			}
			catch(PDOException $e)
			{
				$content->info($lang['c10'].$e->errorInfo[2]);
			}
		}
	}
}

#Nowy lub edycja
else
{
	if($type)
	{
		$c = array('name'=>'', 'author'=>UID, 'text'=>'', 'guest'=>(LOGD==1)?0:1);
	}
	else
	{
		$c = $db->query('SELECT * FROM '.PRE.'comms WHERE ID='.$id)->fetch(2);
	}
}

#B³±d?
if($error) $content->info('<ul><li>'.join('</li><li>',$error).'</li></ul>');

#Szablon
$content->data = array(
	'comment' => $c,
	'code'    => $type && LOGD!=1 && $cfg['captcha']==1 ? true : false,
	'author'  => $type && LOGD!=1 ? true : false,
	'preview' => $preview,
	'url'     => '?co=comment&amp;id='.$id.(($type)?'&amp;type='.$type:'')
);

#JS
if(isset($cfg['bbcode']))
{
	$content->addScript(LANG_DIR.'edit.js');
	$content->addScript('cache/emots.js');
	$content->addScript('lib/editor.js');
}