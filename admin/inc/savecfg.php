<?php
if(iCMSa!='X159E' || !ChPrv('CFG')) exit;
$ile=count($_POST);
if($ile==0 || !defined('WHS')) exit('B³±d!');
$txtz='<?php'."\r\n".((defined('CFGA'))?'$'.CFGA."=Array(\r\n":'');
foreach($_POST as $key=>$val)
{
 #Bez \
 if(get_magic_quotes_gpc()) $val=stripslashes($val);
 $val=str_replace('\\','\\\\',$val);
 $val=str_replace('\'','\\\'',$val); 
 $key=str_replace('u_','',$key);
 if($val=='on') $val=1;
 if(defined('CFGA'))
 {
  #Tablica?
  if(is_array($_POST['u_'.$key]))
	{
	 $txtz.='\''.$key.'\'=>Array(';
	 foreach($_POST['u_'.$key] as $k2=>$v2)
	 {
	  $txtz.='\''.$k2.'\'=>'.((is_numeric($v2))?$v2:'\''.$v2.'\'').",\r\n";
	 }
	 unset($k2,$v2);
	 $txtz.='),';
  }
	else
	{
   $txtz.='\''.$key.'\'=>'.((is_numeric($val))?$val:'\''.$val.'\'').",\r\n";
	}
 }
 else
 {
  $txtz.='$'.$key.'=\''.$val."';\r\n";
 }
}
$txtz.=((defined('CFGA'))?'); ':'').'?>';
$f=fopen(WHS,'w');
flock($f,2);
fwrite($f,$txtz);
flock($f,3);
fclose($f);
?>
