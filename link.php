<?php
require('./kernel.php');
require('./cfg/c.php');
$link=array();
#Dodawanie ods³ony
if($cfg['lcnt']==1)
{
 $id=$_GET['id'];
 db_read('cat,access,adr','links','link','on',' WHERE ID='.$id);
 if($link[1]==1)
 {
  db_read('access','cats','dinfo','on',' WHERE ID='.$link[0]);
	if($dinfo[0]!=3)
	{
	 db_q('UPDATE {pre}links SET count=count+1 WHERE ID='.$id);
	 header('Location: '.str_replace('&amp;','&',$link[2]));
	 echo '<script type="text/javascript">location="'.$link[2].'"</script>';
	}
 }
}
exit;
?>
