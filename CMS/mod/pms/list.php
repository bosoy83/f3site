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
	case 4:
		$q = ' WHERE p.st=4 AND p.owner='.UID; #Wys³ane
		$content->title = $lang['sent'];
		break;
	case 1:
		$q = ' WHERE p.st=1 AND p.usr='.UID; #Oczekuj¹ce
		$content->title = $lang['await'];
		$content->info($lang['pm3i']);
		break;
	case 3:
		$q = ' WHERE p.st=3 AND p.owner='.UID; #Kopie robocze
		$content->title = $lang['drafts'];
		break;
	default:
		$id = 2;
		$q = ' WHERE (p.st=1 OR p.st=2) AND p.owner='.UID; #Odebrane
		$content->title = $lang['inbox']; 
}

#Licz
$total = db_count('pms p'.$q);

#Brak?
if($total < 1)
{
	$content->info($content->title.'<br /><br />'.$lang['pm11']);
	return 1;
}

#Pobierz
$res = $db->query('SELECT p.ID, p.topic, p.usr, p.owner, u.ID as uid, u.login'.
	(($id==2)?', p.st':'').' FROM '.PRE.'pms p LEFT JOIN '.PRE.'users u ON p.'.
	(($id==1)?'owner':'usr').'=u.ID'.$q.' ORDER BY p.ID DESC LIMIT '.$st.',20');

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
		'new'    => $id==2 && $pm[6]==1,
		'url'    => '?co=pms&amp;act=v&amp;id='.$pm[0],
		'login'  => $pm[5],
		'user_url' => '?co=user&amp;id='.$pm[4]
	);
}
$res=null;

#Szablon
$content->file[] = 'pms_list';

#Do szablonu
$content->data += array(
	'pm'  => $pms,
	'who' => ($id==4 || $id==3) ? $lang['pm12'] : $lang['pm13'],
	'url' => '?co=pms&amp;act=m&amp;id='.$id,
	'total' => $total,
	'pages' => Pages($page, $total, 30, '?co=pms&amp;id='.$id, 1)
);