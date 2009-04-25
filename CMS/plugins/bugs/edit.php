<?php 
if(iCMS!=1) exit;

$error = $cat = $bug = array();

#Edytuj zg�oszenie
if($id)
{
	if(!$bug = $db->query('SELECT cat,name,level,poster,text FROM '.PRE.'bugs WHERE ID='.$id)->fetch(2))
	{
		$content->set404();
		return;
	}
	$f = $bug['cat'];

	#Prawa
	if(!Admit('BUGS') && ($bug['UID'] != UID || !isset($cfg['bugsEdit'])))
	{
		$error[] = $lang['noRight'];
	}
}
else
{
	#Kategoria w URL
	if(!isset($_GET['f']) OR !is_numeric($_GET['f']))
	{
		$content->set404();
		return;
	}
	$f = $_GET['f'];
}

#Kategoria
$cat = $db->query('SELECT name,see,post,text FROM '.PRE.'bugcats WHERE ID='.$f)->fetch(3);

#Mo�e pisa�?
if(!$cat[1] OR !BugRights($cat[2]))
{
	$error[] = $lang['noRight'];
}

#Zapisz
if($_POST)
{
	$bug = array(
		'name' => Clean($_POST['name'],50,1),
		'env'  => Clean($_POST['env'],150,1),
		'text' => Clean($_POST['text'],0,1),
		'level'=> (int)$_POST['level']
	);

	#Tekst za d�ugi
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
	if(!$id && !LOGD)
	{
		if(isset($cfg['captcha']) && ($_POST['code']!=$_SESSION['code'] || empty($_POST['xb_c'])))
		{
			$error[] = $lang['badCode'];
		}
	}

	#Zapisz, gdy nie ma b��d�w
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
				if(LOGD)
				{
					$bug['UID'] = UID;
					$bug['who'] = $user[UID]['login'];
				}
				else
				{
					$bug['UID'] = 0;
					$bug['who'] = empty($_POST['who']) ? $lang['guest'] : Clean($_POST['who'],30,1);
				}
				$_SESSION['postTime'] = $_SERVER['REQUEST_TIME'] + $cfg['antyFlood'];
				$bug['ip'] = $_SERVER['REMOTE_ADDR'];
				$bug['date'] = $_SERVER['REQUEST_TIME'];
				$bug['cat']  = $f;
				$bug['level'] = isset($cfg['bugsMod']) ? 5 : 4;

				$i = $db->prepare('UPDATE '.PRE.'bugcats SET last=?, num=num+1 WHERE ID=?');
				$q = $db->prepare('INSERT INTO '.PRE.'bugs (cat,name,date,level,env,UID,who,ip,text)
				VALUES (:cat, :name, :date, :level, :env, :UID, :who, :ip, :text)');
			}
			$q->execute($bug);

			#ID
			$newID = $id ? $id : $db->lastInsertId();

			#Zaktualizuj dane kategorii
			if($i) $i->execute(array($bug['date'], $newID));

			#Zatwierd� transakcj�
			$db->commit();

			#Gdy trzeba moderowa�
			if(!$id && isset($cfg['bugs_mod']))
			{
				$content->message($lang['queued'], '?co=bugs&id='.$newID);
			}
			else
			{
				Header('Location: '.URL.'?co=bugs&id='.$newID);
				$content->message($lang['saved'], '?co=bugs&id='.$newID);
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
	$bug = array('name' => '', 'env' => '', 'level' => 3, 'text' => '');
}

#Poka� b��dy
if($error)
{
	$content->info('<ul><li>'.join('</li><li>', $error).'</li></ul>');
	if(!$_POST) return;
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
$content->title = $id ? $lang['editBug'] : $lang['postBug'];
$content->file = 'edit';
$content->data = array(
	'bug'  => &$bug,
	'code' => !$id && isset($cfg['captcha']) && !LOGD,
	'who'  => !$id && !LOGD,
	'bbcode' => isset($cfg['bbcode'])
);