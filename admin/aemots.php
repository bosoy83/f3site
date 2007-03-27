<?php
if(iCMSa!='X159E' || !ChPrv('CFG')) exit;
require($catl.'adm_o.php');
#Zapis
if($_POST)
{
 $xile=count($_POST['eetxt']);
 $txt='<?php $emodata=Array(';
 for($i=0;$i<$xile;$i++)
 {
  if(!$_POST['eedel'][$i])
  {
	 $txt.='Array("'.TestForm($_POST['eedsc'][$i],1,1,0,20).'","'.TestForm($_POST['eeadr'.$i],1,1,0,80).'","'.TestForm($_POST['eetxt'][$i],1,1,0,8).'"),';
  }
	$f=fopen('cfg/emots.php','w');
	flock($f,2);
	fwrite($f,$txt.') ?>');
	flock($f,3);
	fclose($f);
 }
 unset($ile,$f,$_POST,$txt);
}
#Pliki
if($f=opendir('img/emo'))
{
 while(false!==($q=readdir($f)))
 {
  if(is_file('img/emo/'.$q)) $femts[]=$q;
 }
 closedir($f);
} 
unset($f,$q);
$ilep=count($femts);
#Zapytanie
include_once('cfg/emots.php');
$ile=count($emodata);
?>
<script type="text/javascript">
<!--
ile=<?=$ile?>;
function Dodaj() { 
 ii=ile+1; d("itm"+ile).innerHTML='<table cellpadding="3"><tbody><tr><td><img alt="?" src="img/emo/<?= $femts[0] ?>" id="img'+ile+'" /></td><td><?= $lang['desc'] ?>:<br /><input maxlength="30" name="eedsc['+ile+']" /></td><td><?= $lang['ap_txt'] ?>:<br /><input maxlength="10" name="eetxt['+ile+']" style="width: 70px" /></td><td><?= $lang['ap_file'] ?>:<br /><select name="eeadr'+ile+'" id="eeadr'+ile+'" onchange="Pr('+ile+')"><?php for($y=0;$y<$ilep;$y++) { echo '<option>'.$femts[$y].'</option>'; } ?></select></td><td><br /><input type="checkbox" name="eedel['+ile+']" /> <?= $lang['del'] ?></td></tr></tbody></table><div id="itm'+ii+'" align="center"></div>'; ile++; }
function Pr(co) {
 d('img'+co).src='img/emo/'+d('eeadr'+co).options[d('eeadr'+co).selectedIndex].innerHTML;
}
-->
</script>
<form action="?a=emots" method="post">
<?php
cTable($lang['emots'],1);
echo '<tr><td>'.$lang['eemotxt'].'<br /><div id="itm0" align="center"></div>';
if($ile==0)
{
 echo('<div id="itm0" align="center"></div>');
}
if(isset($femts)) unset($fempts);
#Lista
for($i=0;$i<$ile;$i++) { $ii=$i+1;
 echo '
 <div id="itm'.$i.'" align="center">
 <table cellpadding="3"><tbody>
 <tr>
  <td><img src="img/emo/'.$emodata[$i][1].'" alt="?" id="img'.$i.'" /></td>
  <td>'.$lang['desc'].':<br /><input maxlength="30" value="'.$emodata[$i][0].'" name="eedsc['.$i.']" /></td>
  <td>'.$lang['ap_txt'].':<br /><input maxlength="10" value="'.$emodata[$i][2].'" name="eetxt['.$i.']" style="width: 70px" /></td>
  <td>'.$lang['ap_file'].':<br /><select id="eeadr'.$i.'" name="eeadr'.$i.'" onchange="Pr('.$i.')">';
	for($y=0;$y<$ilep;$y++)
	{
	 echo '<option'.(($femts[$y]==$emodata[$i][1])?' selected="selected"':'').'>'.$femts[$y].'</option>';
	}
	echo '</select></td>
  <td><br /><input type="checkbox" name="eedel['.$i.']" /> '.$lang['del'].'</td>
 </tr>
 </tbody></table>
 </div>';
}
echo '
 <div id="itm'.$ile.'" align="center"></div>
 <center>
 <a href="javascript:Dodaj()">'.$lang['ap_addemo'].'</a>
 </center>
 </td>
</tr>
<tr>
 <td class="eth"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
eTable();
?>
</form>

