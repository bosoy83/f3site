<?php
if(iCMS!=1) exit;
require './mod/account.php';

#Pobierz grupê
if(!$group = $db->query('SELECT * FROM '.PRE.'groups WHERE ID='.$id)->fetch(2)) return;

#Za³o¿yciel
$group['who'] = $group['who']>0 ? autor($group['who']) : false;

#Kto do³±czy³
$new = array();
$res = $db->query('SELECT login FROM '.PRE.'users u INNER JOIN '.PRE.'groupuser g ON u.ID=g.u WHERE g.g='.$id);
foreach($res as $x)
{
	$new[] = array(
		'login' => $x['login'],
		'url'   => url('user/'.urlencode($x['login']))
	);
}

#Jeste¶ cz³onkiem
$member = UID ? dbCount('groupuser WHERE g='.$id.' AND u='.UID) : 0;

#Tytu³ i dane do szablonu
$content->title = $group['name'];
$content->data = array(
	'group'  => &$group,
	'user'   => &$new,
	'edit'   => admit('G') ? url('editGroup/'.$id, '', 'admin') : false,
	'groups' => url('groups'),
	'join'   => false,
	'leave'  => false
);

#Komentarze
if(true)
{
	require './lib/comm.php';
	comments($id, 11);
}