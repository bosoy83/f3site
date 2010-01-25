<?php 
if(iCMS!=1) exit;

$error = $cat = $bug = array();
$right = admit('BUGS');
$id = isset($URL[2]) ? (int)$URL[2] : 0;
$content->title = $id ? $lang['editBug'] : $lang['postBug'];

#Edytuj zg³oszenie
if($id)
{
	if(!$bug = $db->query('SELECT cat,name,env,level,who,text FROM '.PRE.'bugs WHERE ID='.$id)->fetch(2))
	{
		return;
	}
	$f = $bug['cat'];

	#Prawa
	if(!$right && ($bug['UID'] != UID || !isset($cfg['bugsEdit'])))
	{
		$error[] = $lang['noRight'];
	}
}
#Dodaj nowe - musi mieæ parametr f = ID kategorii
else
{
	if(!isset($_GET['f']) OR !is_numeric($_GET['f']))
	{
		return;
	}
	$f = $_GET['f'];
}

#Kategoria
$cat = $db->query('SELECT name,see,post,text FROM '.PRE.'bugcats WHERE ID='.$f)->fetch(3);

#Mo¿e pisaæ?
if(!$cat[1] OR !BugRights($cat[2]))
{
	$error[] = $lang['noRight'];
}

#System CAPTCHA
if(!UID && !empty($cfg['captcha']) && !isset($_SESSION['human']))
{
	require './lib/spam.php';
	$noSPAM = CAPTCHA();
}
else
{
	$noSPAM = false;
}

#Zapisz
if($_POST)
{
	$bug = array(
		'name' => clean($_POST['name'],50,1),
		'env'  => clean($_POST['env'],150,1),
		'text' => clean($_POST['text'],0,1),
		'level'=> (int)$_POST['level']
	);

	#Tekst za d³ugi
	if(empty($bug['text']) || empty($bug['name']))
	{
		$error[] = $lang['bugsFill'];
	}
	if(isset($bug['text'][5001]))
	{
		$error[] = sprintf($lang['bugs_max'], 5000);
	}
	if(isset($_SESSION['postTime']) && $_SESSION['postTime'] > time())
	{
		$error[] = $lang['flood'];
	}
	if($noSPAM && !$noSPAM->verify())
	{
		$error[] = $lang['badCode'];
	}

	#Zapisz, gdy nie ma b³êdów
	if(!$error)
	{
		try
		{
			$db->beginTransaction();
			if($id)
			{
				$i = false;
				$q = $db->prepare('UPDATE '.PRE.'bugs SET name=:name,	env=:env, level=:level, text=:text WHERE ID=:id');
				$bug['id'] = $id;
			}
			else
			{
				if(UID)
				{
					$bug['UID'] = UID;
					$bug['who'] = $user['login'];
				}
				else
				{
					$bug['UID'] = 0;
					$bug['who'] = empty($_POST['who']) ? $lang['guest'] : clean($_POST['who'],30,1);
				}
				$_SESSION['postTime'] = $_SERVER['REQUEST_TIME'] + $cfg['antyFlood'];
				$bug['ip'] = $_SERVER['REMOTE_ADDR'];
				$bug['date'] = $_SERVER['REQUEST_TIME'];
				$bug['cat']  = $f;
				$bug['status'] = isset($cfg['bugsMod']) && !$right ? 5 : 4;

				$i = $db->prepare('UPDATE '.PRE.'bugcats SET last=?, num=num+1 WHERE ID=?');
				$q = $db->prepare('INSERT INTO '.PRE.'bugs (cat,name,date,status,level,env,UID,who,ip,text)
				VALUES (:cat, :name, :date, :status, :level, :env, :UID, :who, :ip, :text)');
			}
			$q->execute($bug);

			#ID
			if(!$id) $id = $db->lastInsertId();

			#Zaktualizuj dane kategorii
			if($i) $i->execute(array($bug['date'], $f));

			#ZatwierdŸ transakcjê
			$db->commit();

			#Gdy trzeba moderowaæ
			if(!$id && isset($cfg['bugsMod']))
			{
				$content->message($lang['queued'], url('bugs/'.$id));
			}
			else
			{
				header('Location: '.URL.url('bugs/'.$id));
				$content->message($lang['saved'], url('bugs/'.$id));
			}
		}
		catch(PDOException $e)
		{
			$content->info($lang['error'].$e->getMessage());
		}
	}
}
elseif(!$id)
{
	$bug = array('name' => '', 'env' => '', 'level' => 3, 'text' => '', 'who' => '');
}

#Poka¿ b³êdy
if($error)
{
	$content->info('<ul><li>'.join('</li><li>', $error).'</li></ul>');
	if(!$_POST) return 1;
}
elseif(isset($cfg['bugsWhile']) && $cat[3])
{
	$content->info(nl2br($cat[3]));
}

#BBCode
if(isset($cfg['bbcode']))
{
	$content->addScript(LANG_DIR.'edit.js');
	$content->addScript('cache/emots.js');
	$content->addScript('lib/editor.js');
}

#Szablon
$content->file = 'edit';
$content->data = array(
	'bug'  => &$bug,
	'code' => $noSPAM,
	'who'  => !$id && !UID,
	'bbcode' => isset($cfg['bbcode'])
);