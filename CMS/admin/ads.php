<?php
if(iCMSa!=1 || !Admit('B')) exit;
require LANG_DIR.'admAll.php';

#Usuñ
if($_POST && $x = GetID(true))
{
	if(isset($_POST['del'])) $db->exec('DELETE FROM '.PRE.'banners WHERE ID IN ('.$x.')');
}

#Info
$content->info($lang['adInfo'], array('?a=editAd' => $lang['addAd']));

#Odczyt
$res = $db->query('SELECT ID,gen,name,ison FROM '.PRE.'banners ORDER BY gen,name');
$res -> setFetchMode(3);
$ad  = array();
$num = 0;

foreach($res as $x)
{
	$ad[] = array(
		'num'  => ++$num,
		'id'   => $x[0],
		'gen'  => $x[1],
		'title'=> $x[2],
		'on'   => $x[3]==1 ? $lang['on2'] : $lang['off2']
	);
}

#Do szablonu
$content->data['ad'] =& $ad;