<?php
if(iCMSa!=1 || !Admit('CFG')) exit;

#Zapisz
if($_POST)
{
	require('./lib/config.php');
	$f=new Config('mail');
	if($f->save($_POST['o']))
	{
		Info($lang['saved']);
		include('./admin/config.php');
	}
	else
	{
		Info('Error: Cannot write to cfg/mail.php.');
	}
	$f=null; return;
}

include($catl.'adm_cfgm.php');
include('cfg/mail.php');
?>
<form action="?a=cfgm&amp;file=mail" method="post">
<?php
OpenBox($lang['ap_cfgm'],2);
echo '
<tr>
	<th colspan="2">'.$lang['opt'].'</th>
</tr>
<tr>
	<td style="width: 40%"><b>'.$lang['ap_mailo'].'?</b></td>
	<td><input type="checkbox" name="mailon"'.(($cfg['mailon']==1)?' checked="checked"':'').' /></td>
</tr>
</tr>
<tr>
	<td><b>'.$lang['ap_mail'].':</b><br /><small>'.$lang['ap_maild'].'</small></td>
	<td><input name="o[mail]" value="'.htmlentities($cfg['mail']).'" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_adr'].' WWW:</b><br /><small>'.$lang['ap_mvwd'].'</small></td>
	<td><input name="o[adr]" value="'.htmlentities($cfg['adr']).'" /></td>
</tr>
<tr>
	<td><b>'.$lang['ap_mmet'].':</b><br /><small>'.$lang['ap_mmetd'].'</small></td>
	<td>
		<input type="radio" name="o[mailh]"'.(($cfg['mailh']!=2)?' checked="checked"':'').' /> mail() <small style="color: green">'.$lang['recomm'].'</small> &nbsp;
		<input type="radio" name="o[mailh]" value="2"'.(($cfg['mailh']==2)?' checked="checked"':'').' /> '.$lang['ap_rsmtp'].'
	</td>
</tr>
<tr>
	<th colspan="2">'.$lang['ap_rsvr'].'</th>
</tr>
<tr>
	<td><b>5. '.$lang['ap_smtp'].':</b></td>
	<td><input name="o[smtp]" value="'.htmlentities($cfg['smtp']).'" /></td>
</tr>
<tr>
	<td><b>6. Port:</b></td>
	<td><input name="o[mailport]" value="'.htmlentities($cfg['mailport']).'" /></td>
</tr>
<tr>
	<td><b>7. '.$lang['login'].' SMTP:</b><br /><small>'.$lang['ap_mailn'].'</small></td>
	<td><input name="o[smtpl]" value="'.htmlentities($cfg['smtpl']).'" /></td>
</tr>
<tr>
	<td><b>8. '.$lang['pass'].' SMTP:</b><br /><small>'.$lang['ap_mailn'].'</small></td>
	<td><input type="password" name="o[smtph]" value="'.htmlentities($cfg['smtph']).'" /></td>
</tr>
<tr>
	<td class="eth" colspan="2"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
CloseBox();
?>
</form>
