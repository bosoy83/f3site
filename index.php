<?php
/* F3Site 2.1: copyright (C) 2006 COMPMaster
Skrypt rozpowszechniany na licencji GPL (więcej w license.txt i CZYTAJ.txt) */
require('kernel.php');
Header('Cache-Control: public');

#Tryby
if($_GET['mode'])
{
 switch($_GET['mode'])
 {
  case 'dl': require('inc/dl.php'); break;
  case 's': require('inc/search.php'); break;
  case 'sets': require('inc/sets.php'); break;
  case 'o': require('inc/rate.php'); break;
  case 'dc': require('inc/delcomm.php'); break;
  case 'poll': require('inc/pollv.php'); break;
  case 'link': require('inc/link.php'); break;
  case 'doc': require('special.php'); exit; break;
 }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <?php echo '
 <meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
 <meta http-equiv="Content-Language" content="'.$nlang.'" />
 <meta name="description" content="'.$cfg['meta_d'].'" />
 <meta name="keywords" content="'.$cfg['meta_k'].'" />
 <meta name="robots" content="'.$cfg['robots'].'" />
 <link type="text/css" rel="stylesheet" href="'.$catst.'s.css" />
 '.$cfg['dkh'].'
 <title>'.$cfg['doc_title'].'</title>';
 #Dodatkowe
 if($cfg['dishead']!=1 && $_GET['co'])
 {
  @include('plugins/'.$_GET['co'].'/head.php');
 }
 ?>
 <script type="text/javascript" src="inc/js.js"></script>
</head>
<body>
<?php
if($_GET['om']==1)
{
 require('d.php');
}
else
{
 if($cfg['mc']==1)
 {
  if(file_exists('cfg/menu'.$nlang.'.php')) { require('cfg/menu'.$nlang.'.php'); } else { require_once('inc/menu.php'); }
 }
 else
 {
  require_once('inc/menu.php');
 }
 require($catst.'body.php');
} ?>
</body>
</html>
