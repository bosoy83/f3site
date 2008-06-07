<?php
if(iCMSa!=1 || !Admit('B')) exit;
require LANG_DIR.'adm_o.php';

#Info
$content->info($lang['bnrinfo'], array('?a=editad' => $lang['addbn']));

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