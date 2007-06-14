<?php
if(iCMS!=1) { exit; }
require($catl.'psw.php');
if(LOGD==1)
{
 Info($lang['psw_3']);
}
elseif($cfg['pswp']!=1)
{
 Info($lang['psw_2']);
}
else
{
 if($_POST['sav'] && $_SESSION['PSWN']!=1)
 {
  #Poprawno¶æ
  $x_m=TestForm($_POST['x_m'],1,1,1);
  $x_n=TestForm($_POST['x_n'],1,1,0);
  if(empty($x_m) || empty($x_n))
  {
   Info($lang['psw_5']);
  }
  else
  {
   $xusr[0]='';
   db_read('ID,mail','users','xusr','on',' WHERE login="'.db_esc($x_n).'"');
   if($xusr[0]==1)
   {
    Info($lang['psw_7']);
   }
   if($xusr[0] && strtolower($x_m)==strtolower($xusr[1]))
   {
    $_psw=mt_rand(1000,10000).'x'.mt_rand(10,1000);
    $_mailt=file_get_contents($catl.'mail_psw.php');
    $_mailt=str_replace('%u',$x_n,$_mailt);
    $_mailt=str_replace('%psw',$_psw,$_mailt);
    require('inc/mail.php');
    #Wysy³anie
    $_SESSION['PSWN']=1;
    if(SendMail($x_n,$x_m,$lang['psw_4'],$cfg['title'],$cfg['mail'],$_mailt))
    {
     db_q('UPDATE {pre}users SET pass="'.md5($_psw).'" WHERE ID='.$xusr[0]);
     Info($lang['psw_8']);
    }
    else
    {
     Info($lang['psw_9']);
    }
   }
   else
   {
    Info($lang['psw_6']);
   }
  }
 }
 else
 {
  Info($lang['psw_1']);
  echo '<form action="?co=psw" method="post">';
  cTable($lang['psw_4'],2);
  echo '
 <tr>
  <td><b>1. Login:</b></td>
  <td><input name="x_n" maxlength="30" /></td>
 </tr>
 <tr>
  <td><b>2. E-mail:</b></td>
  <td><input name="x_m" maxlength="50" /></td>
 </tr>
 <tr>
  <td class="eth" colspan="2" align="center"><input type="submit" value="OK" name="sav" /></td>
 </tr>
  ';
  eTable();
  echo '</form>';
 }
}
?>
