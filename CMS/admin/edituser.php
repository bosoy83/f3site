<?php
if(iCMSa!=1 || !Admit('U') || !isset($_GET['id'])) return;
$id=$_GET['id'];
if($id==1 && UID!=1) return;

require($catl.'profile.php');
$error=array(); //B³êdy

#Uaktualnienie
if($_POST)
{
	#Dane
	$u=array(
	'login'=>Clean($_POST['x_l']),
	'about'=>Clean($_POST['x_ab']),
	'skype'=>Clean($_POST['x_sk'],40),
	'mail' =>Clean($_POST['x_m']),
	'city' =>Clean($_POST['x_fr']),
	'tlen' =>Clean($_POST['x_tl'],30),
	'www'  =>Clean($_POST['x_www']),
	'gid'  =>(int)$_POST['x_gr'],
	'icq'  =>(is_numeric($_POST['x_icq']))?$_POST['x_icq']:null,
	'gg'   =>(is_numeric($_POST['x_gg']))?$_POST['x_gg']:null );

	#Login
	if(isset($u['login'][21]) || !isset($u['login'][2]))
	{
		$error[]=$lang['eplerr'];
	}
	if(db_count('ID','users',' WHERE login="'.$u['login'].'" && ID!='.$id)!==0)
	{
		$error[]=$lang['eploginex'];
	}

	#E-mail
	if(!preg_match('/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,}$/',$u['mail']))
	{
		$error[]=$lang['eperrm'];
	}
	
	#WWW
	$u['www']=str_replace('javascript:','java_script',$u['www']);
	$u['www']=str_replace('vbscript:','vb_script',$u['www']);

	#B³¹d?
	if($error)
	{
		Info('<ul><li>'.join('</li><li>',$error).'</li></ul>');
	}
	#Zapis
	else
	{
		try
		{
			$db->prepare('UPDATE '.PRE.'users SET login=:login, mail=:mail,
			about=:about, www=:www, city=:city, icq=:icq, skype=:skype,
			tlen=:tlen, gg=:gg, gid=:gid WHERE ID='.$id) -> execute($u); //WYKONAJ

			Info($lang['u upd']); return;
		}
		catch(PDOException $e)
		{
			Info($lang['error'].$e->errorInfo[0]);
		}
	}
}

#Pobierz dane
else
{
	$u=$db->query('SELECT * FROM '.PRE.'users WHERE ID='.$id)->fetch(2);
	#Brak?
	if(!$u) { Info($lang['noex']); return; }
}

#Funkcje
require('./lib/user.php');

#FORM
echo '<form action="adm.php?a=edituser&amp;id='.$id.'" method="post">';
OpenBox($lang['editu'],2);
echo '<tr>
	<td><b>1. '.$lang['login'].':</b><br /><small>'.$lang['logind'].'</small></td>
	<td width="65%"><input name="x_l" maxlength="30" value="'.$u['login'].'" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['mail'].':</b></td>
	<td><input name="x_m" value="'.$u['mail'].'" maxlength="50" /></td>
</tr>
<tr>
	<td><b>3. Gadu-Gadu:</b></td>
	<td><input name="x_gg" maxlength="15" value="'.$u['gg'].'" /></td>
</tr>
<tr>
	<td><b>4. Tlen.pl ID:</b><br /><small>'.$lang['tlenwot'].'</small></td>
	<td><input name="x_tl" maxlength="25" value="'.$u['tlen'].'" /></td>
</tr>
<tr>
	<td><b>5. ICQ#:</b></td>
	<td><input name="x_icq" maxlength="20" value="'.$u['icq'].'" /></td>
</tr>
<tr>
	<td><b>6. Skype ID:</b></td>
	<td><input name="x_sk" maxlength="50" value="'.$u['skype'].'" /></td>
</tr>
<tr>
	<td><b>7. '.$lang['wwwp'].':</b></td>
	<td><input name="x_www" maxlength="120" value="'.$u['www'].'" /></td>
</tr>
<tr>
	<td><b>8. '.$lang['ufrom'].'</b></td>
	<td><input name="x_fr" maxlength="50" value="'.$u['city'].'" /></td>
</tr>
<tr>
	<td><b>9. '.$lang['group'].':</b></td>
	<td><select name="x_gr">'.GroupList($u['gid']).'</select></td>
</tr>
<tr>
	<td><b>10. '.$lang['abouty'].':</b></td>
	<td><textarea name="x_ab" cols="40" rows="6">'.$u['about'].'</textarea></td>
</tr>
<tr>
	<td class="eth" colspan="2">
		<input type="submit" value="'.$lang['save'].'" name="sav" />
		<input type="reset" value="'.$lang['reset'].'" />
	</td>
</tr>';
CloseBox();
?>
</form>
