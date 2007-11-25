<?php
if(iCMSa!=1 || !Admit('DEL') || !isset($_GET['id'])) exit;
$id=$_GET['id'];
$dec='';

switch($_POST['co'])
{
 case 'nav':
	if(!Admit('NM')) exit;
	$db->exec('DELETE FROM '.PRE.'menu WHERE ID='.$id);
	$db->exec('UPDATE '.PRE.'menu SET seq=seq-1 WHERE seq>'.$id);
	if($cfg['mc']==1) require('admin/inc/mcache.php');
 break;

 case 'cat':
	if(!Admit('C')) exit;
	$db->exec('DELETE FROM '.PRE.'cats WHERE ID='.$id);
 break;

 case 'user':
  if(!Admit('U') || $id==1) exit;
  $db->exec('DELETE FROM '.PRE.'users WHERE ID='.$id.(($user[UID]['lv']==4)?'':' && lv!=4'));
	if($_GET['all']==1) $db->exec('DELETE FROM '.PRE.'comms WHERE at=1 AND author='.$id);
 break;

 case 'page':
	if(!Admit('IP')) exit;
	$db->exec('DELETE FROM '.PRE.'pages WHERE ID='.$id);
	$db->exec('DELETE FROM '.PRE.'comms WHERE TYPE=59 && CID='.$id);
 break;

 case 'art':
  if(!Admit('A')) exit;
  $db->exec('DELETE FROM '.PRE.'arts WHERE ID='.$id);
	$db->exec('DELETE FROM '.PRE.'artstxt WHERE ID='.$id);
  $db->exec('DELETE FROM '.PRE.'comms WHERE TYPE=1 && CID='.$id);
	$dec='arts';
 break;

 case 'file':
  if(!Admit('F')) exit;
  $db->exec('DELETE FROM '.PRE.'files WHERE ID='.$id);
  $db->exec('DELETE FROM '.PRE.'comms WHERE TYPE=2 && CID='.$id);
	$dec='files';
 break;

 case 'new':
  if(!Admit('N')) exit;
  $db->exec('DELETE FROM '.PRE.'news WHERE ID='.$id);
	$db->exec('DELETE FROM '.PRE.'fnews WHERE ID='.$id);
  $db->exec('DELETE FROM '.PRE.'comms WHERE TYPE=5 && CID='.$id);
	$dec='news';
 break;

 case 'link':
  if(!Admit('L')) exit;
  $db->exec('DELETE FROM '.PRE.'links WHERE ID='.$id);
  $db->exec('DELETE FROM '.PRE.'comms WHERE TYPE=4 && CID='.$id);
	$dec='links';
 break;

 case 'img':
  if(!Admit('G')) exit;
  $db->exec('DELETE FROM '.PRE.'imgs WHERE ID='.$id);
  $db->exec('DELETE FROM '.PRE.'comms WHERE TYPE=3 && CID='.$id);
	$dec='imgs';
 break;

 case 'b':
  if(!Admit('B')) exit;
  $db->exec('DELETE FROM '.PRE.'banners WHERE ID='.$id);
 break;

 case 'group':
	if(!Admit('UG')) exit;
	$db->exec('DELETE FROM '.PRE.'groups WHERE ID='.$id);
	$db->exec('UPDATE '.PRE.'users SET gid=1 WHERE gid='.$id);
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
