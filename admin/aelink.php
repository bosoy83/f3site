<?php
if(iCMSa!='X159E' || !ChPrv('L') || $_REQUEST['link']) exit;
require($catl.'adm_z.php');
if($_GET['id']) { $id=$_GET['id']; } else { $id='new'; }
#Zapis?
if($_POST['sav'])
{
 extract($_POST);
 $xu_n=db_esc(TestForm($xu_n,1,1,0));
 $xu_d=db_esc(TestForm($xu_d,0,1,0));
 $xu_adr=db_esc(TestForm($xu_adr,1,1,1));
 if($id=='new')
 {
  db_q('INSERT INTO {pre}links VALUES ("","'.db_esc($xu_c).'","'.$xu_n.'","'.$xu_d.'","'.db_esc($xu_a).'","'.db_esc($xu_adr).'","'.db_esc($xu_p).'",0,'.(($xu_nw)?1:2).')');
  if($xu_a!=2) ChItmN($xu_c,'+1');
 }
 else
 {
  $link[0]=0;
  db_read('cat,access','links','link','on',' WHERE ID='.$id);
  db_q('UPDATE {pre}links SET cat="'.db_esc($xu_c).'", name="'.$xu_n.'", dsc="'.$xu_d.'", access="'.db_esc($xu_a).'", adr="'.db_esc($xu_adr).'", priotity="'.db_esc($xu_p).'", nw='.(($xu_nw)?1:2).' WHERE ID='.$id);
  #Ilo¶æ linków
  if($link[0]!=$xu_c) { ChItmN($xu_c,'+1'); ChItmN($link[0],'-1'); }
  if($link[1]>$xu_a) ChItmN($xu_c,'+1');
  if($link[1]<$xu_a) ChItmN($xu_c,'-1');
	unset($cat);
 }
 Info('<div align="center">'.$lang['saved'].'<br /><br /><a href="?a=elink">'.$lang['addlink'].'</a></div>');
}

#Odczyt
if($id!='new' && !$_POST['sav'])
{
 db_read('*','links','link','oa',' WHERE ID='.$id);
 if(empty($link['ID'])) { exit('Link nie istnieje!'); }
}

#Form
if(!$_POST['sav'])
{
 db_read('ID,name','cats','xcat','tn',' WHERE type=4');
 $ile=count($xcat);
 echo '<form action="adm.php?a=elink'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 cTable( (($id=='new')?$lang['addlink']:$lang['editlink']) ,0);
 echo '
 <tr>
  <td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
  <td><select name="xu_c">'; for($i=0;$i<$ile;$i++) { echo '<option value="'.$xcat[$i][0].'"'.(($link['cat']==$xcat[$i][0])?' selected="selected"':'').'>'.$xcat[$i][1].'</option>'; } echo '<option value="0">'.$lang['lack'].'</option></select></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['name'].':</b></td>
  <td><input maxlength="50" name="xu_n" value="'.$link['name'].'" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['ap_acc'].':</b></td>
  <td><input type="radio" name="xu_a" value="1"'.(($link['access']==1 || $id=='new')?' checked="checked"':'').' /> '.$lang['ap_isaon'].' &nbsp;<input type="radio" name="xu_a" value="2"'.(($link['access']==2)?' checked="checked"':'').' /> '.$lang['ap_isaoff'].'</td>
 </tr>
 <tr>
  <td><b>4. '.$lang['priot'].':</b></td>
  <td><select name="xu_p"><option value="1"'.(($link['priotity']==1)?' selected="selected"':'').'>'.$lang['high'].'</option><option value="2"'.(($link['priotity']==2 || $id=='new')?' selected="selected"':'').'>'.$lang['normal'].'</option><option value="3"'.(($link['priotity']==3)?' selected="selected"':'').'>'.$lang['low'].'</option></select></td>
 </tr>
 <tr>
  <td><b>5. '.$lang['desc'].':</b></td>
  <td><textarea name="xu_d" style="width: 95%">'.htmlspecialchars($link['dsc']).'</textarea></td>
 </tr>
 <tr>
  <td><b>6. '.$lang['adr'].':</b></td>
  <td><input style="width: 80%" maxlength="200" name="xu_adr" value="'.(($id=='new')?'http://':$link['adr']).'" /></td>
 </tr>
 <tr>
  <td><b>7. '.$lang['opt'].':</b></td>
  <td><input type="checkbox" value="1" name="xu_nw"'.(($link['nw']==1)?' checked="checked"':'').' /> '.$lang['openinnw'].'</td>
 </tr>
 <tr class="eth">
  <td colspan="2"><input type="submit" name="sav" value="'.$lang['save'].'" /></td>
 </tr>
 ';
 eTable();
}
?>
</form>
