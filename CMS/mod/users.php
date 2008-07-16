<?php /* Lista u¿ytkowników */
if(iCMS!=1) exit;
require(LANG_DIR.'profile.php'); #Plik jêzyka

#Tytu³ strony
$content->title = $lang['users'];

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

#Szukanie
$url = '';
$param = Array();

if(isset($cfg['userFind']))
{
	if(!empty($_GET['sl']))
	{
		$sl = Clean($_GET['sl'],20);
		$param[] = 'login LIKE "%'.$sl.'%"';  $url.='&amp;sl='.$sl; //Login
	}
	if(!empty($_GET['pl']))
	{
		$pl = Clean($_GET['pl'],30);
		$param[] = 'city LIKE "%'.$pl.'%"';  $url.='&amp;pl='.$pl; //Miasto
	}
	if(!empty($_GET['www']))
	{
		$www = Clean($_GET['www'],80);
		$param[] = 'www LIKE "%'.$www.'%"';  $url.='&amp;www='.$www; //WWW
	}
}
#ID Grupy
if(isset($_GET['id']))
{
	$id = $_GET['id'];  $param[] = 'gid='.$id;  $url.='&amp;id='.$id;
}

#Licz
$total = db_count('ID','users'.(($url=='')?'':' WHERE '.join(' AND ',$param)));

#Brak?
if($total < 1)
{
	$content->info($lang['nousers']);
	return 1;
}

#Odczyt
$res = $db->query('SELECT ID,login,lv,regt FROM '.PRE.'users'.(($url)?' WHERE '.join(' AND ',$param):'').' ORDER BY '.((isset($_GET['sort']))?'login':'ID DESC').' LIMIT '.$st.',30');

$res->setFetchMode(3);
unset($param);

#Users
$users = array();

#Do tablicy!
foreach($res as $u)
{
	#Poziom
	switch($u[2]) {
		case 2: $lv = $lang['editor']; break;
		case 3: $lv = $lang['admin']; break;
		case 4: $lv = $lang['owner']; break;
		default: $lv = $lang['user'];
	}

	$users[] = array(
		'login' => $u[1],
		'date'  => genDate($u[3]),
		'level' => $lv,
		'num'   => ++$st,
		'url'   => '?co=user&amp;id='.$u[0]
	);
}
$res=null;

#Dane do szablonu
$content->data = array
(
	'users' => &$users,
	'total' => $total,
	'id'    => $id,
	'find'  => isset($cfg['userFind']),
	'joined_url' => '?co=users'.$url,
	'login_url'  => '?co=users&amp;sort=1'.$url,
	'find_login' => !empty($sl) ? $sl : '',
	'find_www'   => !empty($www) ? $www : '',
	'find_place' => !empty($pl) ? $pl : '',
	'pages'      => Pages($page,$total,30,'?co=users'.$url.((isset($_GET['sort']))?'&amp;sort=1':''),1)
);

#Do szablonu
$content->data['users'] =& $users;

#Usuñ zbêdne dane
unset($u,$url,$total,$www,$pl,$sl);
