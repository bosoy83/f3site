<?php
if(iCMS!='E123') exit;
require('cfg/c.php');
$link[0]='';
#Dodawanie ods³ony
if($cfg['lcnt']==1)
{
 $id=$_GET['id'];
 db_read('cat,access,adr','links','link','on',' WHERE ID='.$id);
 db_read('access','cats','dinfo','on',' WHERE ID='.$link[0]);
 if($link[0]!='' && $dinfo[0]!=3 && $link[1]==1)
 {
  db_q('UPDATE {pre}links SET count=count+1 WHERE ID='.$id);
  header('Location: '.str_replace('&amp;','&',$link[2]));
  echo '<script type="text/javascript">location="'.$link[2].'"</script>';
 }
}
exit;
?>
