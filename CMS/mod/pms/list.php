<?php /* PM - lista wiadomoœci */
if(iCMS!=1) exit;

#Strona
if(isset($URL[2]) && $URL[2] > 1 && is_numeric($URL[2]))
{
	$page = $URL[2];
	$st = ($page-1)*30;
}
else
{
	$page = 1;
	$st = 0;
}

#Nazwa folderu
$id = isset($URL[1]) && ctype_alnum($URL[1]) ? $URL[1] : 'inbox';

#Tytu³ strony + warunek do zapytania SQL
switch($id)
{
	case 'sent':
		$q = ' WHERE p.st=4 AND p.owner='.UID; #Wys³ane
		$content->title = $lang['sent'];
		break;
	case 'outbox':
		$q = ' WHERE p.st=1 AND p.usr='.UID; #Oczekuj¹ce
		$content->title = $lang['await'];
		$content->info($lang['pm3i']);
		break;
	case 'drafts':
		$q = ' WHERE p.st=3 AND p.owner='.UID; #Kopie robocze
		$content->title = $lang['drafts'];
		break;
	default:
		$q = ' WHERE (p.st=1 OR p.st=2) AND p.owner='.UID; #Odebrane
		$content->title = $lang['inbox']; 
}

#Licz
$total = dbCount('pms p'.$q);

#Brak?
if($total < 1)
{
	$content->info($content->title.'<br /><br />'.$lang['pm11']);
	return 1;
}

#Pobierz
$res = $db->query('SELECT p.ID, p.topic, p.usr, p.owner, u.ID as uid, u.login'.
	($id=='inbox' ? ', p.st' : '').' FROM '.PRE.'pms p LEFT JOIN '.PRE.'users u ON p.'.
	($id=='outbox' ? 'owner' : 'usr').'=u.ID'.$q.' ORDER BY p.ID DESC LIMIT '.$st.',20');

#Liczba PM
$num = $st;
$pms = array();
$res->setFetchMode(3);

#Adresy
$userURL = url('user/');
$url = url('pms/view/');

#Lista
foreach($res as $pm)
{
	$pms[] = array(
		'id'     => $pm[0],
		'topic'  => $pm[1],
		'num'    => ++$num,
		'new'    => $id=='inbox' && $pm[6]==1,
		'url'    => $url.$pm[0],
		'login'  => $pm[5],
		'user_url' => $userURL.urlencode($pm[5])
	);
}
$res=null;

#Szablon
$content->file[] = 'pms_list';

#Do szablonu
$content->data += array(
	'pm'  => $pms,
	'who' => $id=='inbox' ? $lang['pm12'] : $lang['pm13'],
	'url' => 'pms/del/'.$id,
	'total' => $total,
	'pages' => pages($page, $total, 30, url('pms/'.$id), 1, '/')
);