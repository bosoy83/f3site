<?php /* Powiadomienie na osobnej stronie */
include_once($catl.'special.php');

function Notify($txt,$where='')
{
	global $lang,$cfg;
	if(is_numeric($txt))
	{
		$txt=$lang['s'.$txt];
	}
	if($where)
	{
		$txt.='<br /><br /><a href="'.$where.'">'.$lang['s3'].'</a>';
		define('HEAD','<meta http-equiv="Refresh" content="2; URL='.$where.'" />');
	}
	else
	{
		define('HEAD','');
	}
	define('CONTENT',$txt);
	require($GLOBALS['catst'].'info.php');
	exit;
}
?>