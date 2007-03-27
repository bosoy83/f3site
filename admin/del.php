<?php
if(iCMSa!='X159E' || !ChPrv('DEL') || !isset($_GET['id'])) exit;
$id=$_GET['id'];
$hdl=HURL.'adm.php?';
#Ilo¶æ
function DecNow($id,$tab)
{
 global $_dcn;
 db_read('cat',$tab,'_dcn','on',' WHERE ID='.$id);
 if($_dcn[0]!='0' && is_numeric($_dcn[0])) ChItmN($_dcn[0],'-1');
}
switch($_GET['co']) {
 case 'nav':
   if(!ChPrv('NM')) exit;
   db_q('DELETE FROM {pre}menu WHERE ID='.$id);
   db_q('UPDATE {pre}menu SET seq=seq-1 WHERE seq>'.$id);
	 if($cfg['mc']==1) require('admin/mcache.php');
   Header($hdl.'a=nav'); break;
 case 'cat':
   if(!ChPrv('C')) exit;
   db_q('DELETE FROM {pre}cats WHERE ID='.$id);
   Header($hdl.'a=cats'); break;
 case 'user':
  if(!ChPrv('U')) exit;
  db_q('DELETE FROM {pre}users WHERE ID='.$id);
  if($_GET['all']==1)
  {
   db_q('DELETE FROM {pre}comms WHERE at=1 AND author='.$id);
  }
  Header($hdl.'a=users'); break;
 case 'page':
  if(!ChPrv('IP')) exit;
  db_q('DELETE FROM {pre}pages WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="59_'.$id.'"');
  Header($hdl.'a=pages'); break;
 case 'art':
  if(!ChPrv('A')) exit;
  DecNow($id,'arts');
  db_q('DELETE FROM {pre}arts WHERE ID='.$id);
	db_q('DELETE FROM {pre}artstxt WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="1_'.$id.'"');
  Header($hdl.'a=list'); break;
 case 'file':
  if(!ChPrv('F')) exit;
  DecNow($id,'files');
  db_q('DELETE FROM {pre}files WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="2_'.$id.'"');
  Header($hdl.'a=list&co=2'); break;
 case 'new':
  if(!ChPrv('N')) exit;
  DecNow($id,'news');
  db_q('DELETE FROM {pre}news WHERE ID='.$id);
	db_q('DELETE FROM {pre}fnews WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="5_'.$id.'"');
  Header($hdl.'a=list&co=5'); break;
 case 'link':
  if(!ChPrv('L')) exit;
  DecNow($id,'links');
  db_q('DELETE FROM {pre}links WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="4_'.$id.'"');
  Header($hdl.'a=list&co=4'); break;
 case 'img':
  if(!ChPrv('G')) exit;
  DecNow($id,'imgs');
  db_q('DELETE FROM {pre}imgs WHERE ID='.$id);
  db_q('DELETE FROM {pre}comms WHERE th="3_'.$id.'"');
  Header($hdl.'a=list&co=3'); break;
 case 'b':
  if(!ChPrv('B')) exit;
  db_q('DELETE FROM {pre}banners WHERE ID='.$id);
  Header($hdl.'a=bn'); break;
 default: Header($hdl.'a='); }
?>
