<?php
#J±dro
if($_REQUEST['admenu']) exit;
require('kernel.php');
header('Cache-control: public');

#Admin?
if(LOGD==1 && $user[UID]['lv']!=2 && $user[UID]['lv']!=3) exit('Brak praw dostêpu!');
define('iCMSa','X159E');
require($catl.'adm.php');

#Zazn. ID
function GetIDs($v)
{
 $x=Array();
 $ile=count($v);
 for($i=0;$i<$ile;$i++)
 {
	if(is_numeric(key($v))) array_push($x,key($v)); next($v);
 }
 unset($v,$ile); return $x;
}

#Typ
function GetCType($co)
{
 switch($co)
 {
  case 2: return 'files'; break;
  case 3: return 'imgs'; break;
  case 4: return 'links'; break;
  case 5: return 'news'; break;
  default: return 'arts';
 }
}

#Ilo¶æ zaw.
function ChItmN($i,$d)
{
 global $cat;
 if($i!=0)
 {
  $plus=Array($i);
	db_q('UPDATE {pre}cats SET num=num'.$d.' WHERE ID='.$i);
	if(!isset($cat[$i]['sc'])) db_read('ID,access,sc','cats','cat',$i,' WHERE ID='.$i);
	#Subk.
	while($cat[$i]['sc']!='P')
	{
	 if($cat[$i]['access']!=2 && $cat[$i]['access']!=3)
	 {
	  $i=$cat[$i]['sc'];
	  $plus[]=$i;
	  if(!isset($cat[$i]['sc'])) db_read('ID,access,sc','cats','cat',$i,' WHERE ID='.$i);
	 }
	 else
	 {
	  break;
	 }
	}
	db_q('UPDATE {pre}cats SET nums=nums'.$d.' WHERE ID='.join(' || ID=',$plus));
	unset($plus,$i);
 }
}

#Grupy
function GList($g)
{
 global $group;
 if(!isset($group)) db_read('ID,name','groups','group','tn','');
 $ile=count($group);
 for($i=0;$i<$ile;$i++) { echo '<option value="'.$group[$i][0].'"'.(($group[$i][0]==$g)?' selected="selected"':'').'>'.$group[$i][1].'</option>'; }
 unset($ile,$i);
}

#Specjalne
switch($_GET['x']) {
 case 'db': require('admin/adb.php'); break;
 case 'del': require('admin/del.php'); break;
 case 'fm': require('admin/fman.php'); break;
 case 'ch': require('admin/ch.php'); break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="inc/js.js"></script>
 <meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
 <meta name="Robots" content="no-index" />
 <?php echo '<link type="text/css" rel="stylesheet" href="'.$catst.'s.css" />
 <title>F3Site : Admin - '.$cfg['doc_title'].'</title>'; ?>
</head>
<body>
<?php
if(LOGD==1)
{ 
 #Modu³
 $a=str_replace('/','',$_GET['a']);
 $a=str_replace('.','',$a);
 if(file_exists('admin/a'.$a.'.php'))
 {
  $amod='admin/a'.$a.'.php';
 }
 elseif(file_exists('plugins/'.$a.'/admin.php'))
 {
  $amod='plugins/'.$a.'/admin.php';
 }
 else
 {
  $amod='admin/a.php';
 }
 require_once($catst.'global.php');
 #Menu
 function amenu($co,$adr,$cd)
 {
  if(ChPrv($cd)) mlink($co,'?a='.$adr,0);
 }
 $adgmenu.='<a href="index.php">'.$lang['escadm'].'</a> &nbsp;&nbsp;&nbsp;<a href="adm.php">'.$lang['admhp'].'</a> &nbsp;&nbsp;&nbsp;<a href="login.php?logout=1">'.$lang['logout'].'</a>';
 #Wtyczki
 function ShowMP()
 {
  global $lang,$admenu;
  db_read('*','admmenu','admenu','tn','');
  if(isset($admenu[0][0]))
  {
   mnew($lang['plugs'],'');
   $ile=count($admenu);
   for($i=0;$i<$ile;$i++)
   {
    if($admenu[$i][3]!=1) { amenu($admenu[$i][1],$admenu[$i][2],$admenu[$i][0]); }
   }
   mend();
  }
 }
 #Styl
 require_once($catst.'admin.php');
}
else
{
 require('admin/login.php');
} ?>
</body>
</html>