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
		$q = ' WHERE p.st=3 AND p.owner='.UID; #Zapisane
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
$res = $db->query('SELECT p.ID,p.topic,p.usr,p.owner,'.(($id==4)?'p.st,':'')
	.'u.ID as uid, u.login FROM '.PRE.'pms p LEFT JOIN '.PRE.'users u ON p.'
	.(($id==2)?'owner':'usr').'=u.ID'.$q.' ORDER BY p.ID DESC LIMIT '.$st.',20');

#Do szablonu
$content->data['who'] = ($id==4 || $id==3) ? $lang['pm_12'] : $lang['pm_13'];
$content->data['url'] = '?co=pms&amp;act=m&amp;id='.$id;
$content->data['file'] = 'pms_list.html';
$content->data['total'] = $total;
$content->data['pages'] = Pages($page, $total, 20, '?co=pms&amp;act=l&amp;id='.$id, 1);

#Numer PM
$num = 0;

#Lista
foreach($res as $pm)
{
	$content->data['pms'][] = array(
		'ID'     => $pm['ID'],
		'topic'  => $pm['topic'],
		'num'    => ++$num + $st,
		'new'    => $id==4 && $pm['st']==1,
		'url'    => '?co=pms&amp;act=v&amp;id='.$pm['ID'],
		'login'  => $pm['login'],
		'user_url' => '?co=user&amp;act='.$pm['uid']
	);
}
$res=null;
?>
