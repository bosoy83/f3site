<?php
if(iCMSa!='X159E' || !ChPrv('G') || $_REQUEST['img']) exit;
require($catl.'adm_z.php');
if($_GET['id']) { $id=$_GET['id']; } else { $id='new'; }
#Zapis?
if($_POST['sav'])
{
 $xu_c=db_esc($_POST['xu_c']);
 $xu_n=db_esc(TestForm($_POST['xu_n'],1,1,1));
 $xu_d=db_esc(TestForm($_POST['xu_d'],0,0,0));
 $xu_fm=db_esc(TestForm($_POST['xu_fm'],1,1,1));
 $xu_au=db_esc(TestForm($_POST['xu_au'],1,1,1));
 $xu_f=db_esc(TestForm($_POST['xu_f'],1,1,1));
}

if($_POST['sav'])
{
 if($id=='new')
 {
  db_q('INSERT INTO {pre}imgs VALUES ("","'.$xu_c.'","'.$xu_n.'","'.$xu_d.'","'.db_esc($_POST['xu_t']).'","'.strftime('%Y-%m-%d').'","'.db_esc($_POST['xu_p']).'","'.db_esc($_POST['xu_a']).'","0|0","'.$xu_au.'","'.$xu_fm.'","'.$xu_f.'","'.db_esc($_POST['xu_s1']).'||'.db_esc($_POST['xu_s2']).'")');
  if($_POST['xu_a']!=2) ChItmN($xu_c,'+1');
 }
 else
 {
  $img[0]=0;
  db_read('cat,access','imgs','img','on',' WHERE ID='.$id);
  db_q('UPDATE {pre}imgs SET cat="'.$xu_c.'", name="'.$xu_n.'", author="'.$xu_au.'", dsc="'.$xu_d.'", file="'.$xu_f.'", filem="'.$xu_fm.'", access='.db_esc($_POST['xu_a']).', priotity="'.db_esc($_POST['xu_p']).'", type="'.db_esc($_POST['xu_t']).'", size="'.db_esc($_POST['xu_s1']).'||'.db_esc($_POST['xu_s2']).'" WHERE ID='.$id);
  #Ilo¶æ artów
  if($img[0]!=$xu_c) { ChItmN($xu_c,'+1'); ChItmN($img[0],'-1'); }
  if($img[1]>$_POST['xu_a']) ChItmN($xu_c,'+1');
  if($img[1]<$_POST['xu_a']) ChItmN($xu_c,'-1');
 }
 Info('<div align="center">'.$lang['saved'].'<br /><br /><a href="?a=eimg">'.$lang['addimg'].'</a></div>');
}

#Odczyt
if($id!='new' && !$_POST['sav'])
{
 db_read('*','imgs','img','oa',' WHERE ID='.$id);
 $xsize=explode('||',$img['size']);
 if(empty($img['ID'])) { exit('Obraz nie istnieje!'); }
}

#Form
if(!$_POST['sav'])
{
 db_read('ID,name','cats','xcat','tn',' WHERE type=3');
 $ile=count($xcat);
 require('inc/btn.php');
 echo '<form action="adm.php?a=eimg'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 cTable( (($id=='new')?$lang['addimg']:$lang['editimg']) ,0);
 echo '
 <tr>
  <td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
  <td><select name="xu_c">'; for($i=0;$i<$ile;$i++) { echo '<option value="'.$xcat[$i][0].'"'.(($img['cat']==$xcat[$i][0])?' selected="selected"':'').'>'.$xcat[$i][1].'</option>'; } echo '<option value="0">'.$lang['lack'].'</option></select></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['title'].':</b></td>
  <td><input maxlength="50" name="xu_n" value="'.$img['name'].'" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['ap_acc'].':</b></td>
  <td><input type="radio" name="xu_a" value="1"'.(($img['access']==1 || $id=='new')?' checked="checked"':'').' /> '.$lang['ap_isaon'].' &nbsp;<input type="radio" name="xu_a" value="2"'.(($img['access']==2)?' checked="checked"':'').' /> '.$lang['ap_isaoff'].'</td>
 </tr>
 <tr>
  <td><b>4. '.$lang['priot'].':</b></td>
  <td><select name="xu_p"><option value="1"'.(($img['priotity']==1)?' selected="selected"':'').'>'.$lang['high'].'</option><option value="2"'.(($img['priotity']==2 || $id=='new')?' selected="selected"':'').'>'.$lang['normal'].'</option><option value="3"'.(($img['priotity']==3)?' selected="selected"':'').'>'.$lang['low'].'</option></select></td>
 </tr>
 <tr>
  <td><b>5. '.$lang['desc'].':</b></td>
  <td>'; Colors('xu_d',1); FontBtn('xu_d',1); echo '<textarea name="xu_d" id="xu_d" rows="6" style="width: 95%; margin: 3px 0px 3px 0px">'.htmlspecialchars($img['dsc']).'</textarea><br />'; Btns(1,1,'xu_d'); SpecChr('xu_d'); echo '</td>
 </tr>
 <tr>
  <td><b>6. '.$lang['img'].':</b><div class="txtm">'.$lang['ap_filed'].'</div></td>
  <td><input name="xu_f" id="xu_f" maxlength="200" value="'.(($id=='new')?'img/':$img['file']).'" /> <input type="button" value="'.$lang['preview'].'" onclick="Okno(xu_f.value,500,400,100,100)" />'.((ChPrv('FM'))?' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=xu_f\',580,400,150,150)" />':'').'</td>
 </tr>
 <tr>
  <td><b>7. '.$lang['minimg'].':</b><div class="txtm">'.$lang['minimgd'].'</div></td>
  <td><input name="xu_fm" maxlength="50" value="'.(($id=='new')?'img/':$img['filem']).'" /> <input type="button" value="'.$lang['preview'].'" onclick="Okno(xu_fm.value,500,400,100,100)" />'.((ChPrv('FM'))?' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=xu_fm\',580,400,150,150)" />':'').'</td>
 </tr>
 <tr>
  <td><b>8. '.$lang['author'].':</b><div class="txtm">'.$lang['nameid'].'</div></td>
  <td><input name="xu_au" maxlength="30" value="'.(($id=='new')?UID:$img['author']).'" /></td>
 </tr>
 <tr>
  <td><b>9. '.$lang['ap_type'].':</b></td>
  <td><input type="radio" name="xu_t" value="1"'.(($img['type']==1 || $id=='new')?' checked="checked"':'').' /> &lt;img&gt; &nbsp;<input type="radio" name="xu_t" value="2"'.(($img['type']==2)?' checked="checked"':'').' /> Macromedia Flash &nbsp;<input type="radio" name="xu_t" value="3"'.(($img['type']==3)?' checked="checked"':'').' /> Apple QuickTime</td>
 </tr>
 <tr>
  <td><b>10. '.$lang['isize'].':</b><div class="txtm">'.$lang['isized'].'</div></td>
  <td><input name="xu_s1" value="'.$xsize[0].'" style="width: 30px" maxlength="4" /> x <input maxlength="4" name="xu_s2" value="'.$xsize[1].'" style="width: 30px" /> (px)</td>
 </tr>
 <tr class="eth">
  <td colspan="2"><input type="submit" name="sav" value="'.$lang['save'].'" /></td>
 </tr>
 ';
 eTable();
}
?>
</form>
