<?php /* Lista kategorii */
if(iCMS!=1) exit;

#Typ kategorii - domyœlnie: news
$id = isset($_GET['id']) ? $_GET['id'] : 5;

#Odczyt
$res = $db->query('SELECT ID,name,dsc,nums FROM '.PRE.'cats WHERE sc=0
	AND type='.$id.' AND (access=1 OR access="'.$nlang.'") ORDER BY lft');

$res->setFetchMode(3);
$total=0;

#Do szablonu
foreach($res as $cat)
{
	$content->data['cats'][++$total] = array(
		'id'   => $cat[0],
		'title'=> $cat[1],
		'url'  => '?d='.$cat[0],
		'desc' => $cat[2],
		'num'  => $cat[3]
	);
}

#Brak kategorii?
if($total === 0)
{
	$content -> info($lang['nocats']);
}

$res=null;
unset($cat,$total,$id);
?>
