<?php
if(iCMSa!='X159E') exit;

#Zapis
if($_POST)
{
 define('WHS','cfg/plug_bugs.php');
 define('CFGA','cfg+');
 require('admin/zc.php');
 Info($lang['saved']);
}
else
{
 echo '<form action="?a=bugs&amp;act=o" method="post">';
 @include('cfg/plug_bugs.php');
 cTable($lang['opt'],2);
 echo '
 <tr>
  <td style="width: 40%"><b>1. '.$lang['ab_c1'].':</b></td>
	<td><input name="bugs_on" type="checkbox"'.(($cfg['bugs_on']==1)?' checked="checked"':'').' /></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['ab_c2'].':</b></td>
	<td><input name="bugsnum" value="'.$cfg['bugsnum'].'" size="5" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['ab_c3'].':</b><div class="txtm">'.$lang['ab_c3d'].'</div></td>
	<td><input name="bugs_ae" type="checkbox"'.(($cfg['bugs_ae']==1)?' checked="checked"':'').' /></td>
 </tr>
 <tr>
  <td><b>4. '.$lang['ab_c4'].':</b></td>
	<td><input name="bugs_tl" value="'.$cfg['bugs_tl'].'" size="5" /></td>
 </tr>
 <tr>
  <td><b>5. '.$lang['ab_c6'].':</b><div class="txtm">'.$lang['ab_c6d'].'</div></td>
	<td><input name="bugs_mod" type="checkbox"'.(($cfg['bugs_mod']==1)?' checked="checked"':'').' /></td>
 </tr>
 <tr>
  <td><b>6. '.$lang['ab_c10'].':</b></td>
	<td><input name="bugs_v" type="checkbox"'.(($cfg['bugs_v']==1)?' checked="checked"':'').' /></td>
 </tr>
 <tr>
  <td><b>7. '.$lang['skin'].':</b></td>
	<td><select name="bugs_s">'.sListBox('plugins/bugs/style',1,$cfg['bugs_s']).'</select></td>
 </tr>
 <tr>
  <td><b>8. '.$lang['ab_c7'].':</b></td>
	<td><input name="bugs_i1" type="checkbox"'.(($cfg['bugs_i1']==1)?' checked="checked"':'').' /> '.$lang['ab_c8'].'<br /><input name="bugs_i2" type="checkbox"'.(($cfg['bugs_i2']==1)?' checked="checked"':'').' /> '.$lang['ab_c9'].'</td>
 </tr>
 <tr>
  <th colspan="2">'.$lang['ab_c5'].'</th>
 </tr>
 <tr>
	<td colspan="2" align="center">';
	include_once('inc/btn.php');
	Colors('bugs_i',1);
	FontBtn('bugs_i',1);
	echo '<textarea name="bugs_i" id="bugs_i" cols="50" rows="7" style="margin: 3px">'.htmlspecialchars($cfg['bugs_i']).'</textarea><br />';
	Btns(1,1,'bugs_i');
	SpecChr('bugs_i');
	echo '</td>
 </tr>
 <tr>
  <td colspan="2" class="eth"><input type="submit" value="'.$lang['save'].'" /></td>
 </tr>';
 eTable();
 echo '</form>';
}
?>