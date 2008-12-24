<?php
if(iCMSa!=1 || !Admit('U')) exit;
require LANG_DIR.'adm_o.php';
require LANG_DIR.'profile.php';

#Usuñ
if(isset($_POST['del']) && !isset($_POST['x'][1]) && $x = GetID(true))
{
	$db->exec('DELETE FROM '.PRE.'users WHERE ID IN ('.$x.')'.(UID!=1 ? ' AND lv<'.LEVEL : ''));
}

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
{
	$page = $_GET['page'];
	$st = ($page-1)*30;
}
else
{
	$page = 1;
	$st = 0;
}

#Grupa
$w = '';
if(isset($_GET['gid']))
{
	$gid = (int)$_GET['gid'];
	$w.= ' WHERE gid='.$_GET['gid'];
}
else { $gid=null; }

#Szukaj
if(isset($_REQUEST['s']) && $_REQUEST['s'])
{
	$s = str_replace(array('"','\'','%'), '', Clean($_REQUEST['s'],30));
	$w.= (($w=='')?' WHERE ':' AND ').'login LIKE "'.str_replace('*', '%', $s).'"';
}
else { $s=''; }

#Wszystkich
$total = db_count('users'.$w);
$users = array();

#Pobierz
$res = $db->query('SELECT ID,login,lv FROM '.PRE.'users'.$w.' ORDER BY lv DESC, ID DESC LIMIT '.$st.',30');
$res ->setFetchMode(3); //NUM

foreach($res as $user)
{
	#Kim jest
	switch($user[2])
	{
		case 0: $lv = $lang['locked']; break;
		case 1: $lv = $lang['user']; break;
		case 2: $lv = $lang['editor']; break;
		case 3: $lv = $lang['admin']; break;
		case 4: $lv = $lang['owner']; break;
		default: $lv = 'ERR!';
	}

	$users[] = array(
		'id'   => $user[0],
		'login'=> $user[1],
		'num'  => ++$st,
		'url'  => MOD_REWRITE ? '/user/'.$user[0] : 'index.php?co=user&amp;id='.$user[0],
		'level'=> $lv,
		'options' => $user[2]==4 || $user[0]==UID ? false : true,
	);
}

#Szablon
$content->title = $lang['users'];
$content->data = array(
	'users' => &$users,
	'search'=> $s,
	'pages' => Pages($page,$total,30,'adm.php?a=users&amp;s='.$s.(($gid)?'&amp;gid='.$gid:''),1)
);