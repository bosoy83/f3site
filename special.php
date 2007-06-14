<?php
if($_GET['mode']!='doc' && is_numeric(SPECIAL)) require_once($catl.'special.php');
#HTML Start
define('sHTML','<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta name="Robots" content="no-index" />
 <meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
 '.((defined('WHERE'))?'<meta http-equiv="Refresh" content="2; URL='.WHERE.'" />':'').'
 <link type="text/css" rel="stylesheet" href="'.$catst.'s.css" />
 <title>'.$cfg['doc_title'].'</title>
 <script type="text/javascript" src="inc/js.js"></script>
</head>
<body>');
#HTML Koniec
define('eHTML','</body></html>');
#Info
if(defined('SPECIAL')) {
 echo sHTML.'
 <br /><br />
 <div align="center">
  <div class="info" style="text-align: center; padding: 4px; width: 300px">'.((is_numeric(SPECIAL))?$lang['s'.SPECIAL]:SPECIAL).((defined('WHERE'))?'<br /><br /><a href="'.WHERE.'">'.$lang['s3'].'</a>':'').'</div>
 </div>'.eHTML; 
}
#Doc
elseif($_GET['mode']=='doc')
{
 echo sHTML;
 include($catl.'d'.$_GET['id'].'.php');
 echo eHTML; exit;
}
?>

