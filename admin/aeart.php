<?php
if(iCMSa!='X159E' || !ChPrv('A') || $_REQUEST['art']) exit;
$send=((isset($_POST['send']))?1:2);
require($catl.'adm_z.php');
if(isset($_GET['id'])) { $id=$_GET['id']; } else { $id='new'; }
#Zapis?
if($send==1)
{
 $xu_c=db_esc($_POST['xu_c']);
 $xu_n=db_esc(TestForm($_POST['xu_n'],1,1,0));
 $xu_d=db_esc(TestForm($_POST['xu_d'],0,1,0));
 $xu_au=db_esc(TestForm($_POST['xu_au'],1,1,0));
 $xu_txt=TestForm($_POST['xu_txt'],0,0,0);
 if(strlen($xu_txt)>50000 || strlen($xu_d)>1000) { unset($_POST['sav'],$sav); echo '<script type="text/javascript">alert("'.$lang['txttoolong'].'")</script>'; }
}

if($_POST['sav'])
{
 if($id=='new')
 {
  db_q('INSERT INTO {pre}arts VALUES ("","'.$xu_c.'","'.$xu_n.'","'.$xu_d.'","'.strftime('%Y-%m-%d').'","'.$xu_au.'","0|0","'.db_esc($_POST['xu_a']).'","'.db_esc($_POST['xu_p']).'",0)');
  db_q('INSERT INTO {pre}artstxt VALUES ('.db_id().',"'.$xu_c.'","'.db_esc($xu_txt).'",'.(($_POST['xu_emo'])?1:2).','.(($_POST['xu_br'])?1:2).','.(($_POST['xu_php'])?1:2).')');
  if($_POST['xu_a']!=2) ChItmN($xu_c,'+1');
 }
 else
 {
  $art[0]=0;
  db_read('cat,access','arts','art','on',' WHERE ID='.$id);
  db_q('UPDATE {pre}arts SET cat="'.$xu_c.'", name="'.$xu_n.'", dsc="'.$xu_d.'", author="'.$xu_au.'", access="'.db_esc($_POST['xu_a']).'", priotity="'.db_esc($_POST['xu_p']).'" WHERE ID='.$id);
  db_q('UPDATE {pre}artstxt SET cat="'.$xu_c.'", text="'.db_esc($xu_txt).'", emo='.(($_POST['xu_emo'])?1:2).', br='.(($_POST['xu_br'])?1:2).', php='.(($_POST['xu_php'])?1:2).' WHERE ID='.$id);
  #Ilo¶æ artów
  if($art[0]!=$xu_c) { ChItmN($xu_c,'+1'); ChItmN($art[0],'-1'); }
  if($art[1]>$_POST['xu_a']) ChItmN($xu_c,'+1');
  if($art[1]<$_POST['xu_a']) ChItmN($xu_c,'-1');
 }
 Info('<div align="center">'.$lang['saved'].'<br /><br /><a href="?a=eart">'.$lang['addart'].'</a></div>');
}
#Podgl±d
if($_POST['preview'])
{
 cTable($lang['preview'],1);
 if($_POST['xu_br']) { $xprev=nl2br($xu_txt); } else { $xprev=&$xu_txt; }
 echo '<tr><td class="txt">';
 if($_POST['xu_php'])
 {
  eval('?>'.(($_POST['xu_emo'])?Emots($xprev):$xprev).'<?');
 }
 else
 {
  echo (($_POST['xu_emo'])?Emots($xprev):$xprev);
 }
 echo '</td></tr>'; eTable();
}

#Odczyt
if($id!='new' && $send==2)
{
 db_read('*','arts','art','oa',' WHERE ID='.$id);
 db_read('*','artstxt','fart','oa',' WHERE ID='.$id);
 if(empty($art['ID'])) { exit('Artyku³ nie istnieje! Article doesn\'t exists!'); }
}

#Form
if(!$_POST['sav'])
{
 db_read('ID,name','cats','xcat','tn',' WHERE type=1');
 $ile=count($xcat);
 #Zmienne
 if($send==1)
 {
  $xyc=&$_POST['xu_c'];
  $xya=&$_POST['xu_a'];
  $xyp=&$_POST['xu_p'];
  $xybr=($_POST['xu_br'])?1:2;
  $xyemo=($_POST['xu_emo'])?1:2;
  $xyph=($_POST['xu_php'])?1:2;
 }
 else
 {
  $xyc=&$art['cat'];
  $xya=($id=='new')?1:$art['access'];
  $xyp=($id=='new')?2:$art['priotity'];
  $xybr=($id=='new')?1:$fart['br'];
  $xyemo=($id=='new')?2:$fart['emo'];
  $xyph=($id=='new')?2:$fart['php'];
 }
 echo '<form action="adm.php?a=eart'.(($id=='new')?'':'&amp;id='.$id).'" method="post">';
 cTable( (($id=='new')?$lang['addart']:$lang['editart']) ,2);
 echo '
 <tr>
  <td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
  <td><select name="xu_c">'; for($i=0;$i<$ile;$i++) { echo '<option value="'.$xcat[$i][0].'"'.(($xyc==$xcat[$i][0])?' selected="selected"':'').'>'.$xcat[$i][1].'</option>'; } echo '<option value="0">'.$lang['lack'].'</option></select></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['name'].':</b></td>
  <td><input maxlength="50" name="xu_n" value="'.(($send==1)?$xu_n:$art['name']).'" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['ap_acc'].':</b></td>
  <td><input type="radio" name="xu_a" value="1"'.(($xya==1)?' checked="checked"':'').' /> '.$lang['ap_isaon'].' &nbsp;<input type="radio" name="xu_a" value="2"'.(($xya==2)?' checked="checked"':'').' /> '.$lang['ap_isaoff'].'</td>
 </tr>
 <tr>
  <td><b>4. '.$lang['priot'].':</b></td>
  <td><select name="xu_p"><option value="1"'.(($xyp==1)?' selected="selected"':'').'>'.$lang['high'].'</option><option value="2"'.(($xyp==2)?' selected="selected"':'').'>'.$lang['normal'].'</option><option value="3"'.(($xyp==3)?' selected="selected"':'').'>'.$lang['low'].'</option></select></td>
 </tr>
 <tr>
  <td><b>5. '.$lang['desc'].':</b></td>
  <td><textarea name="xu_d" style="width: 95%">'.stripslashes( (($send==1)?$xu_d:$art['dsc']) ).'</textarea></td>
 </tr>
 <tr>
  <td><b>6. '.$lang['author'].':</b><div class="txtm">'.$lang['nameid'].'</div></td>
  <td><input name="xu_au" value="'.(($send==1)?$xu_au:(($id=='new')?UID:$art['author'])).'" maxlength="30" /></td>
 </tr>
 <tr>
  <td><b>7. '.$lang['opt'].':</b></td>
  <td><input type="checkbox" name="xu_emo"'.(($xyemo==1)?' checked="checked"':'').' /> '.$lang['emoon'].'<br /><input type="checkbox" name="xu_br"'.(($xybr==1)?' checked="checked"':'').' /> '.$lang['br'].'<br /><input name="xu_php" type="checkbox"'.(($xyph==1)?' checked="checked"':'').' /> PHP</td>
 </tr>
 ';
 eTable();
 require('inc/btn.php');
 cTable($lang['text'],1);
 echo '
 <tr>
  <td align="center">'; Colors('xu_txt',1); FontBtn('xu_txt',1); echo '<textarea style="width: 95%; margin: 3px 0px 3px 0px" rows="12" id="xu_txt" name="xu_txt">'.htmlspecialchars( (($send==1)?$xu_txt:$fart['text']) ).'</textarea><br />';  Btns(1,1,'xu_txt'); SpecChr('xu_txt'); echo '</td>
 </tr>
 <tr class="eth">
  <td><input type="hidden" name="send" /><input type="submit" name="preview" value="'.$lang['preview'].'" /> <input type="submit" name="sav" value="'.$lang['save'].'" /></td>
 </tr>
 ';
 eTable();
}
?>
</form>
