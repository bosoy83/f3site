<?php
if(iCMSa!=1 || !Admit('FM')) exit;
if($_GET['ff']) $ff=$_GET['ff'];
if(isset($_GET['file'])) { if(strstr($_GET['file'],'/')) exit('ERROR!'); }
if(isset($_GET['file2'])) { if(strstr($_GET['file2'],'/')) exit('ERROR!'); }
require(LANG_DIR.'upl.php');
#Katalog
if($_GET['dir']) {
 $dir=$_GET['dir'];
 if(strstr($dir,'://') || strstr($dir,'./..') || !strstr($dir,'./')) exit('ERROR!');
 if(substr($dir,-1)!='/') $dir.='/';
 if(substr($dir,-3)=='../') { $dir=substr_replace($dir,'',-4); $dir=substr_replace($dir,'',strrpos($dir,'/')).'/'; }
}
else {
 $dir='./img/';
}
#Lista
echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<link type="text/css" rel="stylesheet" href="'.STYLE_DIR.'s.css" />
<title>F3Site File Manager</title>
<script type="text/javascript">
<!--
function fm_del(a,b) { if(confirm("'.$lang['fmdelf'].' "+b+"?")) { location="?x=fm&act=del'.(($ff)?'&ff='.$ff:'').'&file="+b+"&dir='.$dir.'&ft="+a; } }
function fm_nd(x,y) { if(a=prompt("'.$lang['fmndn'].'")) { location="?x=fm&act="+x+"'.(($ff)?'&ff='.$ff:'').'&dir='.$dir.'"+((y==0)?"":"&file2="+y)+"&file="+a } }
-->
</script>
</head>
<body>
';
if(Admit('FM2')) {
switch($_GET['act']) {
#Nowy folder
case 'nd':
 if(!mkdir($dir.$_GET['file'],0700)) Info($lang['fmnoa']);
break;
#Nazwa
case 'rn':
 if(!@rename($dir.$_GET['file2'],$dir.$_GET['file'])) Info($lang['fmnoa']);
break;
#Wgraj
case 'up':
 if($_FILES['uplf']) { move_uploaded_file($_FILES['uplf']['tmp_name'],$dir.$_FILES['uplf']['name']); }
break;
#Usuñ
case 'del':
 if($_GET['ft']==1) { if(@rmdir($dir.$_GET['file'])) { Info($lang['fmdel1']); } else { Info($lang['fmnoa']); } } else { if(@unlink($dir.$_GET['file'])) { Info($lang['fmdel1']); } else { Info($lang['fmnoa']); } }
break;
} }
if(Admit('FM2')) Info($lang['fmw'].'<br /><br /><center><form action="?x=fm&amp;act=up&amp;dir='.$dir.(($ff)?'&amp;ff='.$ff:'').'" method="post" enctype="multipart/form-data">'.$lang['fmupl'].': <input name="uplf" type="file" /> <input type="submit" value="OK" /> &nbsp;<input type="button" value="'.$lang['fmndir'].'" onclick="fm_nd(\'nd\',0)" /></form></center>');
OpenBox(((strlen($dir)>50)?'(...)'.substr($dir,-40):$dir),3);
echo '
<tr>
 <th style="width: 23px"></th>
 <th>'.$lang['name'].'</th>
 <th>'.$lang['opt'].'</th>
</tr>';
#Katalogi
if($cp=opendir($dir)) {
 while(false !== ($dirq=readdir($cp))) {
  if($dirq!='.' && is_dir($dir.$dirq)) {
   if($dirq=='..' && $dir=='./') {  } else { echo '<tr><td align="center" style="padding: 3px"><img src="'.SCIMG.'" alt="[cat]" /></td><td><a href="?x=fm'.(($ff)?'&amp;ff='.$ff:'').'&amp;dir='.$dir.$dirq.'">'.(($dirq=='..')?'^ '.$lang['goup']:$dirq).'</a></td><td align="center">'.(($dirq!='..' && Admit('FM2'))?'<a href="javascript:fm_del(1,\''.$dirq.'\')">'.$lang['del'].'</a> &middot; <a href="javascript:fm_nd(\'rn\',\''.$dirq.'\')">'.$lang['fmchn'].'</a>':'').'</td></tr>'; }
  }
 }
 closedir($cp);
}
unset($cp,$dirq);
#Pliki
if($cp=opendir($dir)) {
 while(false !== ($dirq=readdir($cp))) {
  if(is_file($dir.$dirq)) {
   echo '<tr><td align="center" style="padding: 3px"><img src="'.IMGFILE.'" alt="[file]" /></td><td><a href="'.$dir.$dirq.'">'.$dirq.'</a></td><td align="center">'.((Admit('FM2'))?'<a href="javascript:fm_del(2,\''.$dirq.'\')">'.$lang['del'].'</a> &middot; <a href="javascript:fm_nd(\'rn\',\''.$dirq.'\')">'.$lang['fmchn'].'</a>':'').(($ff)?' &middot; <a href="javascript:opener.document.forms[0].'.$ff.'.value=\''.$dir.$dirq.'\'; opener.focus()">'.$lang['fins'].' &raquo;</a>':'').'</td></tr>';
  }
 }
 closedir($cp);
}
unset($cp,$dirq);
CloseBox();
echo '
</body>
</html>';
exit; ?>
