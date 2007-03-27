<?php
define('iCMS','E123');
if(!is_numeric($_POST['etap']) && isset($_POST['etap'])) exit;
if($_POST['lng']) { require('../lang/'.$_POST['lng'].'/cms.php'); require('../lang/'.$_POST['lng'].'/i.php'); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
 <meta http-equiv="Robots" content="no-index" />
 <title>F3Site Installer</title>
 <style type="text/css">
 body {background-color: #99B9DF; font: 13px Verdana}
 table {background-color: #7C8477; border-spacing: 1px; margin-bottom: 8px}
 td,th {padding: 4px}
 tbody {background-color: #EDF0F1}
 th {background-color: #DDE9DC; text-align: center}
 .txtm {font-size: 11px}
 </style>
</head>
<body>
<?php include('i'.$_POST['etap'].'.php'); ?>
</body>
</html>
