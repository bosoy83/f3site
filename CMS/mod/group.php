<?php
if(iCMS!=1) exit;
require './cfg/account.php';
require LANG_DIR.'profile.php';

#Pobierz grupê
if(!$group = $db->query('SELECT * FROM '.PRE.'groups WHERE access!=0 AND ID='.$id)->fetch(2)) return;

#Tytu³ strony
$content->title = $group['name'];

#Jeste¶ cz³onkiem
$member = UID ? dbCount('groupuser WHERE g='.$id.' AND u='.UID) : 0;

#Adres grupy
$url = url('group/'.$id);

#Mo¿e do³±czyæ
$mayJoin = UID && !$member && $group['opened']==1;
$mayLeave = UID && $member;

#Misje specjalne
if(isset($URL[2]))
{
	#Do³±cz do grupy
	if($mayJoin && $URL[2] == 'join')
	{
		if(isset($_POST['yes']))
		{
			try
			{
				$db->beginTransaction();
				$q = $db->prepare('REPLACE INTO '.PRE.'groupuser (u,g,date) VALUES (?,?,?)');
				$q->execute(array(UID, $id, $_SERVER['REQUEST_TIME']));
				$db->prepare('UPDATE '.PRE.'groups SET num=num+1 WHERE ID=?')->execute(array($id));
				$db->commit();
				$content->message($lang['joinOK'], $url);
			}
			catch(Exception $e)
			{
				$content->info($e);
			}
		}
		elseif(!$_POST)
		{
			$content->file = 'ask';
			$content->data = array('url'=>'', 'query'=>sprintf($lang['wantJoin'], $group['name']));
			return 1;
		}
	}

	#Wyst±p z szeregów
	if($mayLeave && $URL[2] == 'leave')
	{
		if(isset($_POST['yes']))
		{
			try
			{
				$db->beginTransaction();
				$q = $db->prepare('DELETE FROM '.PRE.'groupuser WHERE u=? AND g=?');
				$q->execute(array(UID, $id));
				$db->prepare('UPDATE '.PRE.'groups SET num=num-1 WHERE ID=?')->execute(array($id));
				$db->commit();
				$content->message($lang['leaveOK'], $url);
			}
			catch(Exception $e)
			{
				$content->info($e);
			}
		}
		elseif(!$_POST)
		{
			$content->file = 'ask';
			$content->data = array('url'=>'', 'query'=>sprintf($lang['wantLeave'], $group['name']));
			return 1;
		}
	}
}

#Za³o¿yciel i data
$group['who'] = $group['who']>0 ? autor($group['who']) : false;
$group['date'] = genDate($group['date']);

#Kto do³±czy³
$new = array();
$res = $db->query('SELECT * FROM '.PRE.'users u INNER JOIN '.PRE.'groupuser g ON u.ID=g.u WHERE g.g='.$id);
foreach($res as $x)
{
	$new[] = array(
		'login' => $x['login'],
		'date'  => genDate($x['date']),
		'url'   => url('user/'.urlencode($x['login']))
	);
}

#Tytu³ i dane do szablonu
$content->data = array(
	'group'  => &$group,
	'user'   => &$new,
	'edit'   => admit('G') ? url('editGroup/'.$id, '', 'admin') : false,
	'groups' => url('groups'),
	'status' => $group['opened'] ? $lang['open'] : $lang['shut'],
	'join'   => $mayJoin ? url($url.'/join') : false,
	'leave'  => $mayLeave ? url($url.'/leave') : false,
	'all'    => $new ? url('users', 'id='.$id) : ''
);

#Komentarze
if(true)
{
	require './lib/comm.php';
	comments($id, 11);
}