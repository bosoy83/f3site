<?php
/* F3Site 3.0: copyright (C) 2007 COMPMaster
Skrypt rozpowszechniany na licencji GPL (wiêcej w license.txt i CZYTAJ.txt) */
require('kernel.php');
Header('Cache-Control: public');
$title='';
$head='';

#Modu³, wtyczka
if($_GET['co'])
{
	$co=$_GET['co'];
	switch($co)
	{
		case 'art'; case 'file'; case 'img'; case 'news'; case 'page':
			require('mod/content.php'); break;
		default:
		if(file_exists('mod/'.$co.'.php'))
		{
			define('MOD','mod/'.$co.'.php');
			$title=$lang[$co];
		}
		else
		{
			if(file_exists('plugins/'.$co.'/co.php'))
			{
				@include('plugins/'.$co.'/head.php');
				if(MOD=='MOD') define('MOD','plugins/'.$co.'/co.php');
			}
			else { define('MOD','404.php'); }
		}
	}
	unset($co);
}

#Kategoria/strona
else
{
	require('cfg/c.php');
	if($_GET['d'] || $cfg['dfct']!=2)
	{
		if($_GET['d']) { $d=$_GET['d']; } else { $d=$cfg['dfc'][$nlang]; }
		db_read('*','cats','dinfo','oa',' WHERE access!=3 AND ID='.$d);
		if($dinfo['ID'])
		{
			$title=&$dinfo['name']; define('MOD','d.php');
		}
		else { define('MOD','404.php'); }
	}
	else
	{
		$_GET['id']=(int)$cfg['dfc'][$nlang]; define('MOD','mod/page.php');
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
 <?php echo '<meta http-equiv="Content-Language" content="'.$nlang.'" />
 <meta name="description" content="'.$cfg['meta_d'].'" />
 <meta name="keywords" content="'.$cfg['meta_k'].'" />
 <meta name="robots" content="'.$cfg['robots'].'" />
 <link type="text/css" rel="stylesheet" href="'.$catst.'s.css" />
 '.$cfg['dkh'].$head.'
 <title>'.(($title)?$title.' :: ':'').$cfg['doc_title'] ?></title>
 <script type="text/javascript" src="inc/js.js"></script>
</head>
<body>
<?php
unset($head,$title);

if($_GET['om']==1)
{
	require(MOD);
}
else
{
	if($cfg['mc']==1 && file_exists('cfg/menu'.$nlang.'.php')) {
		require('cfg/menu'.$nlang.'.php');
	}
	else {
		require('inc/menu.php');
	}
	require($catst.'body.php');
} ?>
</body>
</html>
