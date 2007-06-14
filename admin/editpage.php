<?php
if(iCMS!=1 || !ChPrv('IP')) exit;
if(isset($_GET['id'])) { $id=$_GET['id']; } else { $id='new'; }
require($catl.'adm_z.php');
if($id!='new' && !$_POST)
{
 db_read('*','pages','xpage','oa',' WHERE ID='.$id);
 if(empty($xpage['ID'])) exit('Strona informacyjna nie istnieje!');
}
if($_POST)
{
 $xp_txt=TestForm($_POST['xp_txt'],0,0,0);
 $xp_n=TestForm($_POST['xp_n'],1,1,1);
 if(strlen($_POST['xp_txt'])>50000) { unset($_POST['sav'],$sav); echo '<script type="text/javascript">alert("'.$lang['artlong'].'")</script>'; }
}
#Zapis
if($_POST['sav'])
{
 if($id=='new')
 {
  db_q('INSERT INTO {pre}pages VALUES("","'.db_esc($xp_n).'","'.db_esc($_POST['xp_a']).'",'.(($_POST['xp_t'])?1:2).','.(($_POST['xp_br'])?1:2).','.(($_POST['xp_e'])?1:2).','.(($_POST['xp_c'])?1:2).','.(($_POST['xp_php'])?1:2).',"'.db_esc($xp_txt).'")');
	Info($lang['saved'].' ID: '.db_id());
 }
 else
 {
  db_q('UPDATE {pre}pages SET name="'.db_esc($xp_n).'", access="'.db_esc($_POST['xp_a']).'", tab='.(($_POST['xp_t'])?1:2).', br='.(($_POST['xp_br'])?1:2).', emo='.(($_POST['xp_e'])?1:2).', comm='.(($_POST['xp_c'])?1:2).', php='.(($_POST['xp_php'])?1:2).', text="'.db_esc($xp_txt).'" WHERE ID='.$id);
	Info($lang['saved'].' ID: '.$id);
 }
}
#Podgl±d
if($_POST['preview'])
{
 if($_POST['xp_t']) { cTable($xp_n,1); echo '<tr><td>'; }
 if($_POST['xp_br']) { $xprev=nl2br($xp_txt); } else { $xprev=&$xp_txt; }
 if($_POST['xp_php'])
 {
  eval('?>'.(($_POST['xp_e'])?Emots($xprev):$xprev).'<?');
 }
 else
 {
  echo (($_POST['xp_e'])?Emots($xprev):$xprev);
 }
 if($_POST['xp_t']) { echo '</td></td>'; eTable(); }
}
if(!$_POST['sav'])
{
 if($_POST)
 {
  $xybr=($_POST['xp_br'])?1:0;
  $xye=($_POST['xp_e'])?1:0;
  $xyc=($_POST['xp_c'])?1:0;
  $xyt=($_POST['xp_t'])?1:0;
  $xyp=($_POST['xp_php'])?1:0;
 }
 else
 {
  $xybr=($id=='new')?1:$xpage['br'];
  $xye=$xpage['emo'];
  $xyc=$xpage['comm'];
  $xyt=($id=='new')?1:$xpage['tab'];
  $xyp=$xpage['php'];
 }
 require('inc/btn.php');
 echo '<form action="?a=editpage'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 if($id=='new') { cTable($lang['ap_addp'],2); } else { cTable($lang['ap_editp'],2); }
 echo '
<tr>
 <td style="width: 35%"><b>1. '.$lang['name'].':</b></td>
 <td><input name="xp_n" value="'.(($_POST)?$xp_n:$xpage['name']).'" maxlength="50" style="width: 80%" /></td>
</tr>
<tr>
 <td><b>2. '.$lang['ap_acc'].':</b></td>
 <td><select name="xp_a"><option value="1">'.$lang['ap_isaon'].'</option><option value="3"'.(($xpage['access']==3 || ($_POST && $_POST['xp_a']==3))?' selected="selected"':'').'>'.$lang['forregt'].'</option><option value="2"'.(($xpage['access']==2 || ($_POST && $_POST['xp_a']==2))?' selected="selected"':'').'>'.$lang['ap_isaoff'].'</option></select></td>
</tr>
<tr>
 <td><b>3. '.$lang['opt'].':</b></td>
 <td><input type="checkbox" name="xp_c"'.(($xyc==1)?' checked="checked"':'').' /> '.$lang['comms'].'<br /><input type="checkbox" name="xp_e"'.(($xye==1)?' checked="checked"':'').' /> '.$lang['emoon'].'<br /><input type="checkbox" name="xp_br"'.(($xybr==1)?' checked="checked"':'').' /> '.$lang['br'].'<br /><input type="checkbox" name="xp_t"'.(($xyt==1)?' checked="checked"':'').' /> '.$lang['ap_epttab'].'<br /><input type="checkbox" name="xp_php"'.(($xyp==1)?' checked="checked"':'').' /> PHP</td>
</tr>
';
eTable();
cTable($lang['text'],1);
echo '
<tr>
 <td colspan="2" align="center">'; Colors('xp_txt',1); FontBtn('xp_txt',1); echo '<textarea name="xp_txt" id="xp_txt" style="width: 95%; margin: 3px 0px 3px 0px" rows="17">'.htmlspecialchars((($_POST)?$xp_txt:$xpage['text'])).'</textarea><br />'; Btns(1,1,'xp_txt'); SpecChr('xp_txt'); echo '</td>
</tr>
<tr class="eth">
 <td colspan="2"><input type="submit" name="preview" value="'.$lang['preview'].'" /> <input type="submit" value="'.$lang['save'].'" name="sav" /></td>
</tr>
';
eTable();
echo '</form>';
}
?>
