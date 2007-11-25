<?php
if(iCMS!=1 || $_REQUEST['file']) exit;
require('./kernel.php');
require('./cfg/c.php');
$file[0]='';
#Pobieranie
if($cfg['fcdl']==1)
{
 $id=$_GET['id'];
 db_read('cat,file,access,size','files','file','on',' WHERE ID='.$id);
 db_read('access','cats','dinfo','on',' WHERE ID='.$file[0]);
 if($file[0]!='' && $dinfo[0]!=3 && $file[2]==1)
 {
  db_q('UPDATE '.PRE.'files SET dls=dls+1 WHERE ID='.$id);
	$file[1]=str_replace('&amp;','&',$file[1]);
  header('Location: '.(($file[3]=='A')?'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/'.$file[1]:$file[1]));
 }
}
exit;
?>
