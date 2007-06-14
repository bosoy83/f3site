<?php
if(iCMSa!='X159E' || !ChPrv('DEL') || !isset($_GET['id'])) exit;
Header('Content-type: text/html; charset=iso-8859-2');
$id=$_GET['id'];
$dec='';

switch($_POST['co'])
{
 case 'nav':
	if(!ChPrv('NM')) exit;
	db_q('DELETE FROM {pre}menu WHERE ID='.$id);
	db_q('UPDATE {pre}menu SET seq=seq-1 WHERE seq>'.$id);
	if($cfg['mc']==1) require('admin/inc/mcache.php');
 break;

 case 'cat':
	if(!ChPrv('C')) exit;
	db_q('DELETE FROM {pre}cats WHERE ID='.$id);
 break;
 
 case 'user':
  if(!ChPrv('U')) exit;
  db_q('DELETE FROM {pre}users WHERE ID='.$id);
	if($_POST['all']==1) db_q('DELETE FROM {pre}comms WHERE at=1 AND author='.$id);
 break;

 case 'page':
  if(!ChPrv('IP')) exit;
  db_q('DELETE FROM {pre}pages WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="59_'.$id.'"');
 break;
	
 case 'art':
  if(!ChPrv('A')) exit;
  db_q('DELETE FROM {pre}arts WHERE ID='.$id);
	db_q('DELETE FROM {pre}artstxt WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="1_'.$id.'"');
	$dec='arts';
 break;
 
 case 'file':
  if(!ChPrv('F')) exit;
  db_q('DELETE FROM {pre}files WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="2_'.$id.'"');
	$dec='files';
 break;
	
 case 'new':
  if(!ChPrv('N')) exit;
  db_q('DELETE FROM {pre}news WHERE ID='.$id);
	db_q('DELETE FROM {pre}fnews WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="5_'.$id.'"');
	$dec='news';
 break;
 
 case 'link':
  if(!ChPrv('L')) exit;
  db_q('DELETE FROM {pre}links WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="4_'.$id.'"');
	$dec='links';
 break;
 
 case 'img':
  if(!ChPrv('G')) exit;
  db_q('DELETE FROM {pre}imgs WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="3_'.$id.'"');
	$dec='imgs';
 break;
 
 case 'b':
  if(!ChPrv('B')) exit;
  db_q('DELETE FROM {pre}banners WHERE ID='.$id);
 break;
 
 default: exit;
}

#Zmniejsz ilo¶æ pozycji
if($dec!='')
{
 $cat=0;
 $cat=db_read('cat',$dec,1,'get',' WHERE ID='.$id);
 if($cat!=0 && is_numeric($cat)) ChItmN($cat,'-1');
}

#OK
exit($lang['deldone']);
?>