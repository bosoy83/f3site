<?php
if(iCMS!=1) exit;
require($catl.'profile.php');
$error=array();

#Rejestracja wy³¹czona?
if($cfg['reg_on']!=1 && LOGD!=1)
{
 Info($lang['regoff']);
}

#Zapis
elseif($_POST['sav'])
{
	#Niezalogowani
	if(LOGD==2)
	{
		#Login
		$xu_l=TestForm($_POST['xu_l'],1,1,1);
		if(empty($xu_l) || strlen($xu_l)>20 || strlen($xu_l)<3)
		{
			$error[]=$lang['eplerr'];
		}
		if(db_count('*','users',' WHERE login="'.db_esc($xu_l).'"')!=0)
		{
			$xerrtxt.=$lang['eploginex'].'<br /><br />';
		}
		#Kod
		if($cfg['imgsec']==1)
		{
			if($_POST['xu_code']!=$_SESSION['code'] || empty($_SESSION['code']))
			{
				$error[]=$lang['epbcode'];
			}
			$_SESSION['code']=false;
		}
	}
	#Has³o
	if(empty($_POST['xu_p']) && LOGD==1)
	{
		$xu_p=$user[UID]['pass'];
	}
	else
	{
		$xu_p=$_POST['xu_p'];
		if(!ereg('^[a-zA-Z0-9_-]{5,20}$',$xu_p) || empty($xu_p))
		{
			$xerrtxt.=$lang['eperrp'].'<br /><br />';
		}
		if($xu_p!=$_POST['xu_p2'])
		{
			$xerrtxt.=$lang['eperrp2'].'<br /><br />';
		}
		$xu_p=md5($xu_p);
	}
	#E-mail
	if(!preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$_POST['xu_m']))
	{
  $xerrtxt.=$lang['eperrm'].'<br /><br />';
 }
 if(db_count('*','users',' WHERE mail="'.db_esc($_POST['xu_m']).'"'.((LOGD==1)?' && ID!='.UID:''))!=0)
 {
  $xerrtxt.=$lang['epmailex'].'<br /><br />';
 }
 #Inne
 if(strlen($_POST['xu_m'])>70) exit('$xu_m error!');
 if(strlen($_POST['xu_ab'])>500) $xerrtxt.=$lang['eperrab'].'<br /><br />';
 $xu_ab=TestForm($_POST['xu_ab'],1,1,0);
 $xu_gg=((is_numeric($_POST['xu_gg']))?$_POST['xu_gg']:'');
 $xu_icq=((is_numeric($_POST['xu_icq']))?$_POST['xu_icq']:'');
 $xu_tl=TestForm($_POST['xu_tl'],1,1,1);
 $xu_w=TestForm($_POST['xu_w'],1,1,1);
 $xu_w=str_replace('javascript:','',$xu_w);
 $xu_w=str_replace('vbscript:','',$xu_w);
 $xu_ja=TestForm($_POST['xu_ja'],1,1,1);
 $xu_fr=TestForm($_POST['xu_fr'],1,1,1);
 if(!is_numeric($_POST['xu_vm']) || !is_numeric($_POST['xu_gm'])) exit;
 #Zapis
 if($xerrtxt!='') {
   Info($xerrtxt);
 }
 else {
  if(LOGD!=1)
  {
   db_q('INSERT INTO {pre}users VALUES ("","'.db_esc($xu_l).'","'.$xu_p.'","'.db_esc($_POST['xu_m']).'","'.$_POST['xu_vm'].'",1,1,"","'.strftime('%Y-%m-%d').'","",0,"'.db_esc($xu_ab).'","'.$_POST['xu_gm'].'","'.db_esc($xu_w).'","'.db_esc($xu_fr).'","'.$xu_icq.'","'.db_esc($xu_ja).'","'.db_esc($xu_tl).'","'.$xu_gg.'")');
   Info($lang['upregd'].'<br /><br />'.$lang['login'].': '.$xu_l);
  }
  else
  {
   db_q('UPDATE {pre}users SET pass="'.$xu_p.'", mail="'.db_esc($_POST['xu_m']).'", mvis="'.$_POST['xu_vm'].'", about="'.db_esc($xu_ab).'", mails='.$_POST['xu_gm'].', www="'.db_esc($xu_w).'", city="'.db_esc($xu_fr).'", icq="'.$xu_icq.'", skype="'.db_esc($xu_ja).'", tlen="'.db_esc($xu_tl).'", gg="'.$xu_gg.'" WHERE ID='.UID);
   Info($lang['upact']);
  }
 }
}
if(!$_POST || $xerrtxt!='')
{
 #Form
 if(LOGD==1)
 {
  #Odczyt
	db_read('*','users','user',UID,' WHERE ID='.UID);
	
  $xuomvis=($_POST['sav'])?$_POST['xu_vm']:$user[UID]['mvis'];
  $xuogetm=($_POST['sav'])?$_POST['xu_vm']:$user[UID]['mvis'];
 }
 else
 {
  $xuomvis=($_POST['sav'])?$_POST['xu_vm']:2;
  $xuogetm=($_POST['sav'])?$_POST['xu_vm']:1;
 }
 echo '<form action="index.php?co=account" method="post">';
 cTable($lang['editu'],2);
 #Dane
 echo('
 <tr>
  <th colspan="2">'.$lang['editup'].'</th>
 </tr>
 <tr>
  <td><b>1. '.$lang['login'].':</b><div class="txtm">'.$lang['logind'].'</div></td>
  <td width="50%">'.((LOGD==1)?$user[UID]['login']:'<input name="xu_l" maxlength="30" value="'.$xu_l.'" />').'</td>
 </tr>
 <tr>
  <td><b>2. '.$lang['newpass'].':</b><div class="txtm">'.$lang['passd'].'</div></td>
  <td><input type="password" name="xu_p" maxlength="30" /></td>
 </tr>
 <tr>
  <td><b>3. '.$lang['retpass'].':</b></td>
  <td><input maxlength="30" type="password" name="xu_p2" /></td>
 </tr>
 <tr>
  <td><b>4. '.$lang['mail'].':</b></td>
  <td><input name="xu_m" value="'.(($_POST['sav'])?$_POST['xu_m']:$user[UID]['mail']).'" maxlength="50" /></td>
 </tr>
 '.(($cfg['imgsec']==1 && LOGD==2)?'<tr>
  <td><b>5. '.$lang['imgcode'].':</b></td>
  <td><img src="code.php" alt="test" style="margin-bottom: 5px; border: 1px solid gray" /><br /><input name="xu_code" /></td>
 </tr>':'').'
 <tr>
  <th colspan="2">'.$lang['editus'].'</th>
 </tr>
 <tr>
  <td><b>1. '.$lang['vismail'].'?</b><div class="txtm">'.$lang['vismaild'].'</div></td>
  <td><input type="radio" value="1" name="xu_vm"'.(($xuomvis==1)?' checked="checked"':'').' /> '.$lang['yes'].' &nbsp;<input type="radio" value="2" name="xu_vm"'.(($xuomvis==2)?' checked="checked"':'').' /> '.$lang['no'].' &nbsp;</td>
 </tr>
 <tr>
  <td><b>2. '.$lang['getmails'].'?</b><div class="txtm">'.$lang['getmailsd'].'</div></td>
  <td><input type="radio" value="1" name="xu_gm"'.(($xuogetm==1)?' checked="checked"':'').' /> '.$lang['yes'].' &nbsp;<input type="radio" value="2" name="xu_gm"'.(($xuogetm==2)?' checked="checked"':'').' /> '.$lang['no'].' &nbsp;</td>
 </tr>
 <tr>
  <td><b>3. Gadu-Gadu:</b></td>
  <td><input name="xu_gg" maxlength="15" value="'.(($_POST['sav'])?$xu_gg:$user[UID]['gg']).'" /></td>
 </tr>
 <tr>
  <td><b>4. Tlen.pl ID:</b><div class="txtm">'.$lang['tlenwot'].'</div></td>
  <td><input name="xu_tl" maxlength="50" value="'.(($_POST['sav'])?$xu_tl:$user[UID]['tlen']).'" /></td>
 </tr>
 <tr>
  <td><b>5. ICQ#:</b></td>
  <td><input name="xu_icq" maxlength="20" value="'.(($_POST['sav'])?$xu_icq:$user[UID]['icq']).'" /></td>
 </tr>
 <tr>
  <td><b>6. Skype ID:</b></td>
  <td><input name="xu_ja" maxlength="50" value="'.(($_POST['sav'])?$xu_ja:$user[UID]['skype']).'" /></td>
 </tr>
 <tr>
  <td><b>7. '.$lang['wwwp'].':</b></td>
  <td><input name="xu_w" maxlength="120" value="'.(($_POST['sav'])?$xu_w:((LOGD==1)?$user[UID]['www']:'http://')).'" /></td>
 </tr>
 <tr>
  <td><b>8. '.$lang['ufrom'].'</b></td>
  <td><input name="xu_fr" maxlength="50" value="'.(($_POST['sav'])?$xu_fr:$user[UID]['city']).'" /></td>
 </tr>
 <tr>
  <td><b>9. '.$lang['abouty'].':</b></td>
  <td><textarea name="xu_ab" rows="5" cols="24">'.(($_POST['sav'])?$xu_ab:$user[UID]['about']).'</textarea></td>
 </tr>
 <tr class="eth">
  <td colspan="2"><input type="submit" value="'.$lang['save'].'" name="sav" /> <input type="reset" value="'.$lang['reset'].'" /></td>
 </tr>
 ');
 eTable();
 echo '</form>';
}
?>
