<?php
require('kernel.php');

#Wyloguj?
if($_GET['logout']==1)
{
 if(LOGD==2) exit('Session error!');
 unset($_SESSION['uid'],$_SESSION['uidl'],$_SESSION['uidp']);
 if(isset($_COOKIE[$cfg['c'].'login'])) setcookie($cfg['c'].'login','',time()-25920000);
 define('SPECIAL',4);
 define('WHERE','index.php');
 require('special.php');
}

#Loguj?
else
{
 if(LOGD==1) exit('Session error!');
 #Dane wpisane?
 $snduser=TestForm($_POST['snduser'],1,1,1);
 $sndpass=TestForm($_POST['sndpass'],1,1,1);
 if(empty($snduser) || empty($sndpass)) exit('???');

 unset($tmpu);
 $xpass=md5($sndpass);
 db_read('ID,login,pass,lv','users','tmpu','oa',' WHERE login="'.db_esc($snduser).'"');
 
 #Ban?
 if($tmpu['lv']==5)
 {
  define('SPECIAL',16);
 }
 
 #Poprawne?
 elseif(strtolower($tmpu['login'])===strtolower($snduser) && $tmpu['pass']===$xpass && !empty($tmpu['login'])  && !empty($tmpu['pass']))
 {
  #Pamiêtanie
  if($_POST['sndr']==1)
	{
   setcookie($cfg['c'].'login',$tmpu['ID'].':'.$xpass,time()+25920000) or exit('Cookies problem!');
	}
	else
	{
	 $_SESSION['uid']=$tmpu['ID'];
   $_SESSION['uidl']=$tmpu['login'];
   $_SESSION['uidp']=$xpass;
   $_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
	}
  define('SPECIAL',1);
  define('WHERE',(($_POST['gtadm']==1)?'adm.php':'index.php'));
 }
 else
 {
  require($catl.'special.php');
  define('SPECIAL',$lang['s2'].(($cfg['pswp']==1)?'<br /><br />'.$lang['pswp']:''));
 }
 require('special.php');
}
?>
