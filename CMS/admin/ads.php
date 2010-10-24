<?php
if(iCMSa!=1 || !admit('B')) exit;
require LANG_DIR.'admAll.php';

#Usu�
if($_POST and $x = GetID(true) and isset($_POST['del']))
{
	$db->exec('DELETE FROM '.PRE.'banners WHERE ID IN ('.$x.')');
}

#Info
$content->info($lang['adInfo'], array(url('editAd','','admin') => $lang['addAd']));

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
		'on'   => $x[3]==1 ? $lang['on2'] : $lang['off2'],
		'edit' => url('editAd/'.$x[0], '', 'admin')
	);
}

#Przekieruj, gdy nie ma nic
if(empty($ad))
{
	header('Location: '.URL.url('editAd','','admin'));
	exit;
}
else
{
	$content->data['ad'] =& $ad;
}