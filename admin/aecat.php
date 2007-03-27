<?php
if(iCMSa!='X159E' || !ChPrv('C')) exit;
if($_GET['id']) { $id=$_GET['id']; } else { $id='new'; }
require($catl.'adm_z.php');
#Zapis
if($_POST['sav'])
{
 extract($_POST);
 $xc_n=db_esc(TestForm($xc_n,1,1,0));
 $xc_d=db_esc(TestForm($xc_d,1,1,0));
 $xc_sc=db_esc(TestForm($xc_sc,1,1,1));
 $xc_txt=db_esc(TestForm($xc_txt,0,0,0));
 $xc_o=(($xc1)?'S':'').(($xc2)?'C':'').(($xc3)?'O':'');
 #Nowy
 if($id=='new')
 {
  db_q('INSERT INTO {pre}cats VALUES ("","'.$xc_n.'","'.$xc_d.'","'.db_esc($xc_vis).'","'.db_esc($xc_t).'","'.$xc_sc.'","'.db_esc($xc_sort).'","'.$xc_txt.'",0,0,"'.$xc_o.'")');
 }
 #Aktualizacja
 else
 {
  $ile=db_count('*',GetCType($xc_t),' WHERE access!=2 AND cat='.$id);
  if(!is_numeric($ile)) $ile=0;
  db_q('UPDATE {pre}cats SET name="'.$xc_n.'", dsc="'.$xc_d.'", access="'.db_esc($xc_vis).'", type="'.db_esc($xc_t).'", sc="'.$xc_sc.'", sort="'.db_esc($xc_sort).'", text="'.$xc_txt.'", num="'.$ile.'", opt="'.$xc_o.'" WHERE ID='.$id);
 }
 Info('<center>'.$lang['saved'].(($id=='new')?' ID: '.db_id():'').'<br /><br /><a href="?a=ecat">'.$lang['ap_kaddc'].'</a></center>');
}

if(!$_POST['sav'])
{
 if($id!='new')
 {
  db_read('*','cats','dinfo','oa',' WHERE ID='.$id);
  if(empty($dinfo['ID'])) exit('Kategoria nie istnieje!');
 }
 echo '<form action="adm.php?a=ecat'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 if($id=='new') { cTable($lang['ap_kaddc'],2); } else { cTable($lang['ap_editc'],2); }
 include('inc/btn.php');
 echo '
<tr>
 <td style="width: 30%"><b>1. '.$lang['name'].':</b></td>
 <td><input name="xc_n" value="'.$dinfo['name'].'" maxlength="50" style="width: 80%" /></td>
</tr>
<tr>
 <td><b>2. '.$lang['desc'].':</b></td>
 <td><input name="xc_d" value="'.$dinfo['dsc'].'" maxlength="200" style="width: 80%" /></td>
</tr>
<tr>
 <td><b>3. '.$lang['ap_acc'].':</b></td>
 <td><select name="xc_vis"><option value="1">'.$lang['ap_isaon'].'</option>'.sListBox('lang',1,$dinfo['access']).'<option value="2"'.(($dinfo['access']==2)?' selected="selected"':'').'>'.$lang['ap_ishidden'].'</option><option value="3"'.(($dinfo['access']==3)?' selected="selected"':'').'>'.$lang['ap_isaoff'].'</option></select></td>
</tr>
<tr>
 <td><b>4. '.$lang['ap_type'].':</b></td>
 <td><select name="xc_t"><option value="1">'.$lang['arts'].'</option><option value="2"'.(($dinfo['type']==2)?' selected="selected"':'').'>'.$lang['files'].'</option><option value="3"'.(($dinfo['type']==3)?' selected="selected"':'').'>'.$lang['gallery'].'</option><option value="4"'.(($dinfo['type']==4)?' selected="selected"':'').'>'.$lang['links'].'</option><option value="5"'.(($dinfo['type']==5)?' selected="selected"':'').'>'.$lang['news'].'</option></select></td>
</tr>
<tr>
 <td><b>5. '.$lang['ap_wtxt'].':</b><div class="txtm">'.$lang['ap_wtxtd'].'</div></td>
 <td>'; Colors('xc_txt',1); FontBtn('xc_txt',1); echo '<textarea name="xc_txt" id="xc_txt" style="width: 90%; margin: 3px 0px 3px 0px" rows="7">'.htmlspecialchars($dinfo['text']).'</textarea><br />'; Btns(1,1,'xc_txt'); SpecChr('xc_txt'); echo '</td>
</tr>
<tr>
 <td><b>6. '.$lang['issubc'].'?</b><div class="txtm">'.$lang['ap_scd'].'</div></td>
 <td><select name="xc_sc"><option value="P">'.$lang['scno'].'</option>';
 
 #Lista kat.
 db_read('ID,name,type','cats','tcat','tn',' '.(($id!='new')?'WHERE ID!='.$id:'').' ORDER BY type,name');
 $ile=count($tcat);
 $t=0;
 for($i=0;$i<$ile;$i++)
 {
  if($tcat[$i][2]>$t)
	{
	 $t=$tcat[$i][2];
	 echo (($t==0)?'':'</optgroup>').'<optgroup label="'.$lang['ap_type'].': '.$lang[GetCType($t)].'">';
	}
  echo '<option value="'.$tcat[$i][0].'"'.(($dinfo['sc']==$tcat[$i][0])?' selected="selected"':'').'>'.$tcat[$i][1].'</option>';
 }
 
 echo '</optgroup></select></td>
</tr>
<tr>
 <td><b>7. '.$lang['ap_sort'].':</b><div class="txtm">'.$lang['ap_nnews'].'</div></td>
 <td><select name="xc_sort"><option value="1">'.$lang['sortid'].'</option><option value="2"'.(($dinfo['sort']==2 || $id=='new')?' selected="selected"':'').'>'.$lang['sortid2'].'</option><option value="3"'.(($dinfo['sort']==3)?' selected="selected"':'').'>'.$lang['sortn'].'</option></select></td>
</tr>
<tr>
 <td><b>8. '.$lang['opt'].':</b><div class="txtm">'.$lang['ap_disd'].'</div></td>
 <td><input type="checkbox" name="xc1"'.((strstr($dinfo['opt'],'S'))?' checked="checked"':'').' /> '.$lang['ap_dis1'].'<br /><input type="checkbox" name="xc2"'.((strstr($dinfo['opt'],'C'))?' checked="checked"':'').' /> '.$lang['ap_dis2'].'<br /><input type="checkbox" name="xc3"'.((strstr($dinfo['opt'],'O'))?' checked="checked"':'').' /> '.$lang['ap_dis3'].'</td>
</tr>
<tr class="eth">
 <td colspan="2"><input type="submit" name="sav" value="'.$lang['save'].'" /> <input type="reset" value="'.$lang['reset'].'" /></td>
</tr>
';
eTable();
echo '</form>';
} ?>
