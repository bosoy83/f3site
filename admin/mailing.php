<?php
if(iCMSa!='X159E' || !ChPrv('MM')) exit;
require_once('cfg/mail.php');
require($catl.'adm_ml.php');
if($cfg['mailon']==1)
{
if($_POST['sav'])
{
 require_once('inc/mail.php');
 db_read('login,mail','users','xuser','tn',' WHERE mails=1');
 $ile=count($xuser);
 if($ile>0)
 {
  $edo=Array();
  for($i=0;$i<$ile;$i++)
  {
   $edo[]=$xuser[$i][1];
  }
  if(SendMail($_POST['xm_r'],$edo,$_POST['xm_t'],$_POST['xm_e'],$_POST['xm_n'],$_POST['xm_tr'].'<br>----------<br>'.$lang['apmm2'].'<br>----------<br>%title<br>%a'))
  {
   Info($lang['msent']);
  }
  else
  {
   Info($lang['mnsent']);
  }
 }
}
if(!$_POST['sav']) $ile=db_count('*','users',' WHERE mails=1');
if($ile>0)
{
 require('inc/btn.php');
 Info($lang['apmm1']);
 echo '<form action="?a=mailing" method="post">';
 cTable($lang['massl'],2);
 echo '<tr>
  <td style="width: 25%"><b>1. '.$lang['sender'].':</b></td>
  <td><input name="xm_n" maxlength="50" value="'.(($_POST)?$_POST['xm_n']:$cfg['doc_title']).'" /></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['senderm'].':</b></td>
  <td><input name="xm_e" maxlength="50" value="'.(($_POST)?$_POST['xm_e']:$cfg['mail']).'" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['rcpt'].':</b></td>
  <td><input name="xm_r" maxlength="50" value="'.(($_POST)?$_POST['xm_r']:$lang['rcpt2'].$cfg['doc_title']).'" /></td>
 </tr>
 <tr>
  <td><b>4. '.$lang['topic'].':</b></td>
  <td><input name="xm_t" maxlength="50" value="'.$_POST['xm_t'].'" /></td>
 </tr>
 <tr>
  <td><b>5. '.$lang['text'].':</b></td>
  <td>'; Colors('xm_tr',1); FontBtn('xm_tr',1); echo '<textarea name="xm_tr" id="xm_tr" style="width: 90%; margin: 3px 0px 3px 0px" rows="8">'.$_POST['xm_tr'].'</textarea><br />'; Btns(1,1,'xm_tr'); SpecChr('xm_tr'); echo '</td>
 </tr>
 <tr>
  <td colspan="2" class="eth"><input type="submit" name="sav" value="OK" /></td>
 </tr>';
 eTable();
 echo '</form>';
}
else
{
 Info($lang['nousnd']);
}
}
else
{
 Info($lang['mailsd']);
}
?>
