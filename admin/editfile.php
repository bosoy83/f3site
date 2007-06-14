<?php
if(iCMSa!='X159E' || !ChPrv('F') || $_REQUEST['file']) exit;
require($catl.'adm_z.php');
if(isset($_GET['id'])) { $id=$_GET['id']; } else { $id='new'; }
#Zapis?
if($_POST['sav'])
{
 extract($_POST);
 $xu_c=db_esc($xu_c);
 $xu_n=db_esc(TestForm($xu_n,1,1,0));
 $xu_d=db_esc(TestForm($xu_d,0,1,0));
 $xu_s=db_esc((($xu_t==1)?'A':TestForm($xu_s,1,1,1)));
 $xu_au=db_esc(TestForm($xu_au,1,1,0));
 $xu_f=db_esc(TestForm($xu_f,1,1,1));
 if($id=='new')
 {
  db_q('INSERT INTO {pre}files VALUES ("","'.$xu_c.'","'.$xu_n.'","'.$xu_au.'","'.strftime('%Y-%m-%d').'","'.$xu_d.'","'.$xu_f.'",0,"'.db_esc($xu_a).'","'.$xu_s.'","'.db_esc($xu_p).'","0|0","'.db_esc(TestForm($xu_fd,0,0,0)).'")');
  if($_POST['xu_a']!=2) ChItmN($xu_c,'+1');
 }
 else
 {
  $file[0]=0;
  db_read('cat,access','files','file','on',' WHERE ID='.$id);
  db_q('UPDATE {pre}files SET cat="'.$xu_c.'", name="'.$xu_n.'", author="'.$xu_au.'", dsc="'.$xu_d.'", file="'.$xu_f.'", access="'.db_esc($xu_a).'", size="'.$xu_s.'", priority="'.db_esc($xu_p).'", fulld="'.db_esc(TestForm($xu_fd,0,0,0)).'" WHERE ID='.$id);
  #Ilo¶æ plików
  if($file[0]!=$xu_c) { ChItmN($xu_c,'+1'); ChItmN($file[0],'-1'); }
  if($file[1]>$xu_a) ChItmN($xu_c,'+1');
  if($file[1]<$xu_a) ChItmN($xu_c,'-1');
 }
 Info('<center>'.$lang['saved'].'<br /><br /><a href="?a=editfile">'.$lang['addfile'].'</a></center>');
}

#Form
if(!$_POST['sav'])
{
 #Odczyt
 if($id!='new')
 {
  db_read('*','files','file','oa',' WHERE ID='.$id);
  if(empty($file['ID'])) exit('Plik nie istnieje!');
 }
 require($catl.'files.php');
 require('inc/btn.php');
 db_read('ID,name','cats','xcat','tn',' WHERE type=2');
 $ile=count($xcat);
 echo '<form action="adm.php?a=editfile'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 cTable( (($id=='new')?$lang['addfile']:$lang['editf']) ,0);
 echo '
 <tr>
  <td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
  <td><select name="xu_c">'; for($i=0;$i<$ile;$i++) { echo '<option value="'.$xcat[$i][0].'"'.(($file['cat']==$xcat[$i][0])?' selected="selected"':'').'>'.$xcat[$i][1].'</option>'; } echo '<option value="0">'.$lang['lack'].'</option></select></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['name'].':</b></td>
  <td><input maxlength="50" name="xu_n" value="'.$file['name'].'" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['ap_acc'].':</b></td>
  <td><input type="radio" name="xu_a" value="1"'.(($file['access']==1 || $id=='new')?' checked="checked"':'').' /> '.$lang['ap_isaon'].' &nbsp;<input type="radio" name="xu_a" value="2"'.(($file['access']==2)?' checked="checked"':'').' /> '.$lang['ap_isaoff'].'</td>
 </tr>
 <tr>
  <td><b>4. '.$lang['priot'].':</b></td>
  <td><select name="xu_p"><option value="1"'.(($file['priority']==1)?' selected="selected"':'').'>'.$lang['high'].'</option><option value="2"'.(($file['priority']==2 || $id=='new')?' selected="selected"':'').'>'.$lang['normal'].'</option><option value="3"'.(($file['priority']==3)?' selected="selected"':'').'>'.$lang['low'].'</option></select></td>
 </tr>
 <tr>
  <td><b>5. '.$lang['desc'].':</b></td>
  <td><textarea name="xu_d" style="width: 95%">'.htmlspecialchars($file['dsc']).'</textarea></td>
 </tr>
 <tr>
  <td><b>6. '.$lang['ap_type'].':</b></td>
  <td><input onclick="xu_s.disabled=\'disabled\'; if(xu_f.value==\'http://\') { xu_f.value=\'files/\'; }" type="radio" value="1" name="xu_t"'.(($file['size']=='A' || $id=='new')?' checked="checked"':'').' /> '.$lang['local'].' &nbsp;<input type="radio" onclick="xu_s.disabled=0; if(xu_f.value==\'files/\') { xu_f.value=\'http://\' }" value="2" name="xu_t"'.(($file['size']!='A' && $id!='new')?' checked="checked"':'').' /> '.$lang['remote'].'</td>
 </tr>
 <tr>
  <td><b>7. '.$lang['file'].':</b></td>
  <td><input name="xu_f" maxlength="230" value="'.(($id=='new')?'files/':$file['file']).'" style="width: 250px" />'.((ChPrv('FM'))?' <input type="button" value="'.$lang['files'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=xu_f&amp;dir=./files/\',580,400,150,150)"':'').'</td>
 </tr>
 <tr>
  <td><b>8. '.$lang['size'].':</b><div class="txtm">'.$lang['fsizd'].'</div></td>
  <td><input name="xu_s" maxlength="20"'.(($file['size']=='A' || $id=='new')?' disabled="disabled"':' value="'.$file['size'].'"').'" /></td>
 </tr>
 <tr>
  <td><b>8. '.$lang['author'].':</b><br /><span class="txtm">'.$lang['nameid'].'</span></td>
  <td><input name="xu_au" maxlength="30" value="'.(($id=='new')?UID:$file['author']).'" /></td>
 </tr>
 <tr>
  <td><b>9. '.$lang['fulld'].':</b></td>
  <td>'; Colors('xu_fd',1); FontBtn('xu_fd',1); echo '<textarea name="xu_fd" id="xu_fd" rows="7" style="width: 95%; margin: 3px 0px 3px 0px">'.htmlspecialchars($file['fulld']).'</textarea>'; Btns(1,1,'xu_fd'); SpecChr('xu_fd'); echo '</td>
 </tr>
 <tr class="eth">
  <td colspan="2"><input type="submit" name="sav" value="'.$lang['save'].'" /></td>
 </tr>
 ';
 eTable();
}
?>
</form>
