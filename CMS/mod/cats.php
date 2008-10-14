<?php /* Lista kategorii */
if(iCMS!=1) exit;

#Tytu� strony
$content->title = $lang['cats'];

#Typ kategorii - domy�lnie: news
if(!$id) $id = 5;

#Odczyt
$res = $db->query('SELECT ID,name,dsc,last,nums FROM '.PRE.'cats WHERE sc=0
	AND type='.$id.' AND (access=1 OR access="'.$nlang.'") ORDER BY lft');

$res->setFetchMode(3);
$total = 0;
$cat = array();

#Do szablonu
foreach($res as $x)
{
	$cat[] = array(
		'title'=> $x[1],
		'url'  => MOD_REWRITE ? '/'.$x[0] : '?d='.$x[0],
		'desc' => $x[2],
		'num'  => $x[4],
		'new'  => isset($_SESSION['recent']) && $x[3] > $_SESSION['recent']
	);
	++$total;
}

#Brak kategorii?
if($total === 0)
{
	$content -> info($lang['nocats']); return;
}
#Tylko 1 - przekierowa�?
elseif($total === 1)
{
	require './cfg/content.php';
	if(isset($cfg['goCat']))
	{
		$_GET['d'] = $x[0];
		unset($cat,$x,$total,$res);
		require './lib/category.php';
	}
}

#Szablon
$content->data['cat'] =& $cat;
unset($res,$x,$total);