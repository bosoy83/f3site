<?php
if(iCMS!=1) exit;

if($_GET['ch_l'])
{
	setcookie(PRE.'tlang',Clean($_GET['ch_l'],0,1,1),time()+12960000) or exit('SetCookie() ERROR!');
}
if($_GET['ch_s'])
{
	setcookie(PRE.'tstyle',Clean($_GET['ch_s'],0,1,1),time()+12960000) or exit('SetCookie() ERROR!');
}
Header(URL);
require('./lib/info.php');
Notify(15,'index.php');
?>
