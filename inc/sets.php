<?php
if(iCMS!=1) exit;
if($_GET['ch_l'])
{
 setcookie($cfg['c'].'tlang',TestForm($_GET['ch_l'],0,1,1),time()+12960000) or exit('SetCookie() ERROR!');
}
if($_GET['ch_s'])
{
 setcookie($cfg['c'].'tstyle',TestForm($_GET['ch_s'],0,1,1),time()+12960000) or exit('SetCookie() ERROR!');
}
Header(URL);
define('SPECIAL',15);
define('WHERE','index.php');
require('special.php');
exit;
?>
