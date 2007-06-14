<?php
if(iCMS!=1) exit;
require_once('cfg/mail.php');
$rn="\r\n";
function SendMail($ndo,$edo,$t,$od,$odn,$txt)
{
 global $cfg;
 if($cfg['mailon']!=1) return false;
 #Test
 $ile=count($edo);
 $dm=Array('<','>',';',',');
 $txt=str_replace('%a','<a href="'.$cfg['adr'].'">'.$cfg['adr'].'</a>',$txt);
 $txt=str_replace('%title',$cfg['doc_title'],$txt);
 $od=str_replace($dm,'',$do);
 $odn=str_replace($dm,'',$odn);
 $ndo=str_replace($dm,'',$ndo);
 #FSockOpen
 if($cfg['mailh']==2)
 {
  $fp=fsockopen($cfg['smtp'],$cfg['mailport']);
  if($fp)
  {
   fwrite($fp,'HELO '.$cfg['smtp'].$rn);
   #Has³o
   if(!empty($cfg['smtpl']))
   {
    fwrite($fp,'AUTH LOGIN'.$rn);
    fwrite($fp,base64_encode($cfg['smtpl']).$rn);
    fwrite($fp,base64_encode($cfg['smtph']).$rn);
   }
   #Od
   fwrite($fp,'MAIL FROM: <'.$od.'>'.$rn);
   #Do
   for($i=0;$i<$ile;$i++) { fwrite($fp,'RCPT TO: <'.str_replace($dm,'',$edo[$i]).'>'.$rn); }
   #Dane
   fwrite($fp,'DATA'.$rn);
   fwrite($fp,'Subject: '.$t.$rn.'Content-type: text/html; charset=iso-8859-2'.$rn.'From: '.$odn.$rn.'To: '.$ndo.$rn.$rn);
   fwrite($fp,$txt.$rn);
   fwrite($fp,'.'.$rn);
   fwrite($fp,'QUIT'.$rn);
   fclose($fp);
   return true;
  }
  else
  {
   return false;
  }
 }
 #Mail
 else
 {
  $w=Array();
  for($i=0;$i<$ile;$i++) { $w[]=$ndo.' <'.str_replace($dm,'',$edo[$i]).'>'; }
  if(mail($odn.' <'.$od.'>',$t,$txt,'Content-type: text/html; charset=iso-8859-2'.$rn.'From: '.$odn.' <'.$od.'>'.'Bcc: '.join(', ',$w)))
  {
   return true;
  }
  else
  {
   return false;
  }
  unset($w);
 }
 unset($ile,$dm);
}
?>
