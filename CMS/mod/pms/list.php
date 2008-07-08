<?php /* PM - lista wiadomoœci */
if(iCMS!=1) exit;

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

#Tytu³ strony + warunek do zapytania SQL
switch($id)
{
	case 1:
		$q = ' WHERE p.st=4 AND p.owner='.UID; #Wys³ane
		$content->title = $lang['pm_6'];
		break;
	case 2:
		$q = ' WHERE p.st=1 AND p.usr='.UID; #Do wys³ania
		$content->title = $lang['pm_8'];
		$content->info($lang['pm_3i']);
		break;
	case 3:
		$q = ' WHERE p.st=3 AND p.owner='.UID; #Kopie robocze
		$content->title = $lang['pm_7'];
		break;
	default:
		$id = 4;
		$q = ' WHERE (p.st=1 OR p.st=2) AND p.owner='.UID; #Odebrane
		$content->title = $lang['pm_5']; 
}

#Licz
$total = db_count('p.ID', 'pms p'.$q);

#Brak?
if($total < 1)
{
	$content->info($content->title.'<br /><br />'.$lang['pm_11']);
	return 1;
}

#Pobierz
$res = $db->query('SELECT p.ID, p.topic, p.usr, p.owner, u.ID as uid, u.login'.
	(($id==4)?', p.st':'').' FROM '.PRE.'pms p LEFT JOIN '.PRE.'users u ON p.'.
	(($id==2)?'owner':'usr').'=u.ID'.$q.' ORDER BY p.ID DESC LIMIT '.$st.',20');

#Liczba PM
$num = $st;
$pms = array();
$res->setFetchMode(3);

#Lista
foreach($res as $pm)
{
	$pms[] = array(
		'id'     => $pm[0],
		'topic'  => $pm[1],
		'num'    => ++$num,
		'new'    => $id==4 && $pm[6]==1,
		'url'    => '?co=pms&amp;act=v&amp;id='.$pm[4],
		'login'  => $pm[5],
		'user_url' => MOD_REWRITE ? '/user/'.$pm[4] : '?co=user&amp;id='.$pm[4]
	);
}
$res=null;

#Szablon
$content->file[] = 'pms_list';

#Do szablonu
$content->data += array(
	'pm'  => $pms,
	'who' => ($id==4 || $id==3) ? $lang['pm_12'] : $lang['pm_13'],
	'url' => '?co=pms&amp;act=m&amp;id='.$id,
	'total' => $total,
	'pages' => Pages($page, $total, 20, '?co=pms&amp;act=l&amp;id='.$id, 1)
);