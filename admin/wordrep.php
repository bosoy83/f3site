<?php if(iCMSa!='X159E' || !ChPrv('CFG')) exit;
require($catl.'adm_conf.php');
#Zapis
if($_GET['z']==1)
{
 $ile=count($_POST['xw_1']);
 if($ile>0 && count($_POST['xw_d'])!=$ile)
 {
  unset($_z1,$_z2,$_x1,$_x2);
  $_z1=Array();
  $_z2=Array();
  for($i=0;$i<$ile;$i++)
  {
   if(!$_POST['xw_d'][$i])
   {
    $_x1=str_replace('\'','\\\'',htmlspecialchars(TestForm($_POST['xw_1'][$i],0,1,0)));
    $_x2=str_replace('\'','\\\'',TestForm($_POST['xw_2'][$i],0,1,0));
    if(!empty($_x1)) { array_push($_z1,'\''.$_x1.'\''); array_push($_z2,'\''.$_x2.'\''); }
   }
  }
  $f=fopen('cfg/words.php','w');
	flock($f,2);
	fwrite($f,((count($_z1)==0)?'':'<?php $words1=Array('.implode(',',$_z1).'); $words2=Array('.implode(',',$_z2).'); ?>'));
	flock($f,3);
	fclose($f);
  unset($_z1,$_z2,$_x1,$_x2);
 }
}
#Form
include('cfg/words.php');
$ile=count($words1);
Info($lang['aw_i'].(($cfg['wordc']==1)?'':'<br />'.$lang['aw_f']));
echo '<script type="text/javascript">
<!--
ileusr='.$ile.';
function Dodaj() { ii=ileusr+1; document.getElementById("itm"+ileusr).innerHTML=\'<div style="margin: 5px"><input maxlength="100" name="xw_1[\'+ileusr+\']" /> '.$lang['aw_c'].' <input maxlength="100" name="xw_2[\'+ileusr+\']" /> <input type="checkbox" name="xw_d[\'+ileusr+\']" /> '.$lang['del'].'</div><div id="itm\'+(ileusr+1)+\'"></div>\'; ileusr++; }
-->
</script><form action="?a=wordrep&amp;z=1" method="post">';
cTable($lang['aw_t'],1);
echo '<tr><td align="center">';
#Lista
for($i=0;$i<$ile;$i++)
{
 $ii=$i+1;
 echo '<div style="margin: 5px">
  <input maxlength="100" value="'.$words1[$i].'" name="xw_1['.$i.']" /> '.$lang['aw_c'].' <input maxlength="100" value="'.htmlspecialchars($words2[$i]).'" name="xw_2['.$i.']" /> <input type="checkbox" value="1" name="xw_d['.$i.']" /> '.$lang['del'].'
 </div>';
}
echo '<div id="itm'.$ile.'" align="center"></div>
 <div style="padding: 5px">
 <a href="javascript:Dodaj()"><b>'.$lang['add'].'</b></a>
 </div>
 </td>
</tr>
<tr class="eth">
 <td><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
eTable();
?>
</form>

