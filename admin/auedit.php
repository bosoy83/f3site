<?php
if(iCMSa!='X159E' || !ChPrv('U') || !$_GET['id']) exit;
$id=$_GET['id'];
if($id==1 && UID!=1) exit;
require($catl.'profile.php');
#Uaktualnienie
if($_POST['sav'])
{
 #Login
 $xu_l=TestForm($_POST['xu_l'],1,1,1);
 if(empty($xu_l) || strlen($xu_l)>20 || strlen($xu_l)<3)
 {
   $xerrtxt.=$lang['eplerr'].'<br /><br />'; $xu_OK=2;
 }
 if(db_count('*','users',' WHERE login="'.$xu_l.'" && ID!='.db_esc($_POST['xu_usr']))!=0)
 {
   $xerrtxt.=$lang['eploginex'].'<br /><br />'; $xu_OK=2;
 }
 #E-mail
 if(!preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$_POST['xu_m'])) {
   $xerrtxt.=$lang['eperrm'].'<br /><br />'; $xu_OK=2;
 }
 #Inne
 if(strlen($_POST['xu_m'])>50) exit;
 if(strlen($_POST['xu_ab'])>255) { $xerrtxt.=$lang['eperrab'].'<br /><br />'; $xu_OK=2; }
 #Zapis
 if($xu_OK==2) {
   Info($xerrtxt);
 }
 else {
   $xu_ab=TestForm($_POST['xu_ab'],0,1,1);
   $xu_gg=((is_numeric($_POST['xu_gg']))?$_POST['xu_gg']:'');
   $xu_icq=((is_numeric($_POST['xu_icq']))?$_POST['xu_icq']:'');
   $xu_tl=TestForm($_POST['xu_tl'],1,1,1);
   $xu_w=TestForm($_POST['xu_w'],1,1,1);
   $xu_ja=TestForm($_POST['xu_ja'],1,1,1);
   $xu_fr=TestForm($_POST['xu_fr'],1,1,1);
   db_q('UPDATE {pre}users SET login="'.db_esc($xu_l).'", mail="'.$_POST['xu_m'].'", about="'.db_esc($xu_ab).'", www="'.db_esc($xu_w).'", city="'.db_esc($xu_fr).'", icq="'.$xu_icq.'", skype="'.db_esc($xu_ja).'", tlen="'.db_esc($xu_tl).'", gg="'.$xu_gg.'", gid="'.db_esc($_POST['xu_gr']).'" WHERE ID='.$_POST['xu_usr']);
   Info($lang['upact']);
 }
}
if(!$_POST['sav'] || $xu_OK==2) {
 db_read('*','users','xuser','oa',' WHERE ID='.$id);
 if(empty($xuser['ID'])) exit('U¿ytkownik nie istnieje! User doesn\'t exist!');
 echo('<form action="adm.php?a=uedit&amp;id='.$id.'" method="post"><input type="hidden" name="xu_usr" value="'.$id.'" />');
 cTable($lang['editu'],2);
 #Dane
 echo '
 <tr>
  <td><b>1. '.$lang['login'].':</b><br /><span class="txtm">'.$lang['logind'].'</span></td>
  <td width="65%"><input name="xu_l" maxlength="30" value="'.(($_POST['xu_l'])?$_POST['xu_l']:$xuser['login']).'" /></td>
 </tr>
 <tr>
  <td><b>2. '.$lang['mail'].':</b></td>
  <td><input name="xu_m" value="'.(($_POST['xu_m'])?$xu_m:$xuser['mail']).'" maxlength="50" /></td>
 </tr>
 <tr>
  <td><b>3. Gadu-Gadu:</b></td>
  <td><input name="xu_gg" maxlength="15" value="'.(($_POST['xu_gg'])?$xu_gg:$xuser['gg']).'" /></td>
 </tr>
 <tr>
  <td><b>4. Tlen.pl ID:</b><br /><span class="txtm">'.$lang['tlenwot'].'</span></td>
  <td><input name="xu_tl" maxlength="50" value="'.(($_POST['xu_tl'])?$xu_tl:$xuser['tlen']).'" /></td>
 </tr>
 <tr>
  <td><b>5. ICQ#:</b></td>
  <td><input name="xu_icq" maxlength="20" value="'.(($_POST['xu_icq'])?$xu_icq:$xuser['icq']).'" /></td>
 </tr>
 <tr>
  <td><b>6. Skype ID:</b></td>
  <td><input name="xu_ja" maxlength="50" value="'.(($_POST['xu_ja'])?$xu_ja:$xuser['jabber']).'" /></td>
 </tr>
 <tr>
  <td><b>7. '.$lang['wwwp'].':</b></td>
  <td><input name="xu_w" maxlength="120" value="'.(($_POST['xu_w'])?$xu_w:$xuser['www']).'" /></td>
 </tr>
 <tr>
  <td><b>8. '.$lang['ufrom'].'</b></td>
  <td><input name="xu_fr" maxlength="50" value="'.(($_POST['xu_fr'])?$xu_fr:$xuser['city']).'" /></td>
 </tr>
 <tr>
  <td><b>9. '.$lang['group'].':</b></td>
  <td><select name="xu_gr">'; GList($xuser['group']); echo '</select></td>
 </tr>
 <tr>
  <td><b>10. '.$lang['abouty'].':</b></td>
  <td><textarea name="xu_ab" cols="40" rows="6">'.(($_POST['xu_ab'])?$xu_ab:$xuser['about']).'</textarea></td>
 </tr>
 <tr>
  <td class="eth" colspan="2"><input type="submit" value="'.$lang['save'].'" name="sav" /> <input type="reset" value="'.$lang['reset'].'" /></td>
 </tr>';
 eTable();
 echo '</form>';
}
