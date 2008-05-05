<?php
if(iCMSa!=1 || !Admit('UG')) exit;
require LANG_DIR.'adm_o.php'; //Jêzyk

#Info
$content->info($lang['ugrw'], array('?a=editgroup'=>$lang['gradd']));

#Odczyt
$res = $db->query('SELECT ID,name,opened FROM '.PRE.'groups');
$res-> setFetchMode(3); //NUM

#Lista
$group = array();
$num = 0;

foreach($res as $g)
{
	$group[] = array(
		'id'  => $g[0],
		'num' => ++$num,
		'title'  => $g[1],
		'opened' => ($g[2]===1) ? $lang['yes'] : $lang['no']
	);
}

$res = null;
$content->data['group'] =& $group;
?>
