<center style="line-height: 22px">
<?php
if(iCMS!='E123') exit;
#Licznik
if(file_exists('cfg/visits.txt'))
{
 $licznik=@file_get_contents('cfg/visits.txt');
}
else
{
 $licznik=0;
}
if(!$_SESSION['online'])
{
 $f=@fopen('cfg/visits.txt','w');
 @flock($f,2);
 @fwrite($f,++$licznik);
 @flock($f,3);
 @fclose($f);
}

#Google?
if(strstr($_SERVER['USER_AGENT'],'Googlebot'))
{
 $r='Googlebot';
 #Log
 if(!$_SESSION['online']) db_q('INSERT INTO {pre}log VALUES ("","Google",NOW(),"-",0)');
}
else
{
 $r=db_esc($_SERVER['REMOTE_ADDR'].' '.$_SERVER['HTTP_X_FORWARDED_FOR']);
}

#Online
if($_SESSION['online']>(time()-600))
{
 db_q('UPDATE {pre}online SET time=NOW(), user='.UID.' WHERE IP="'.$r.'"');
}
else
{
 db_q('DELETE FROM {pre}online WHERE time<(NOW()-600) OR IP="'.$r.'"');
 db_q('INSERT INTO {pre}online VALUES ("'.$r.'",'.UID.',NOW(),"")');
 $_SESSION['online']=time();
}
global $lang;
echo $lang['visits'].'<br /><b>'.$licznik.'</b><br />'.$lang['online'].'<br /><b>'.db_count('IP','online','').'</b>';
?>
</center>
