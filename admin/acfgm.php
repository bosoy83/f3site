<?php
if(iCMSa!='X159E' || !ChPrv('CFG')) exit;
if($_POST)
{
 define('WHS','cfg/mail.php');
 define('CFGA','cfg+');
 require('admin/zc.php');
 Info($lang['saved']);
}
else
{
 include($catl.'adm_cfgm.php');
 include('cfg/mail.php');
?>
<form action="?a=cfgm" method="post">
<?php
cTable($lang['ap_cfgm'],2);
echo('
<tr>
 <th colspan="2">'.$lang['opt'].'</th>
</tr>
<tr>
 <td><b>1. '.$lang['ap_mailo'].'?</b></td>
 <td><input type="checkbox" name="u_mailon"'.(($cfg['mailon']==1)?' checked="checked"':'').' /></td>
</tr>
</tr>
<tr>
 <td style="width: 40%"><b>2. '.$lang['ap_mail'].':</b><div class="txtm">'.$lang['ap_maild'].'</div></td>
 <td><input name="u_mail" value="'.$cfg['mail'].'" /></td>
</tr>
<tr>
 <td><b>3. '.$lang['ap_adr'].' WWW:</b><div class="txtm">'.$lang['ap_mvwd'].'</div></td>
 <td><input name="u_adr" value="'.$cfg['adr'].'" /></td>
</tr>
<tr>
 <td><b>4. '.$lang['ap_mmet'].':</b><div class="txtm">'.$lang['ap_mmetd'].'</div></td>
 <td><input type="radio" name="u_mailh"'.(($cfg['mailh']!=2)?' checked="checked"':'').' /> mail() &nbsp;<input type="radio" name="u_mailh" value="2"'.(($cfg['mailh']==2)?' checked="checked"':'').' /> '.$lang['ap_rsmtp'].'</td>
</tr>
<tr>
 <th colspan="2">'.$lang['ap_rsvr'].'</th>
</tr>
<tr>
 <td><b>5. '.$lang['ap_smtp'].':</b></td>
 <td><input name="u_smtp" value="'.$cfg['smtp'].'" /></td>
</tr>
<tr>
 <td><b>6. Port:</b></td>
 <td><input name="u_mailport" value="'.$cfg['mailport'].'" /></td>
</tr>
<tr>
 <td><b>7. '.$lang['login'].' SMTP:</b><div class="txtm">'.$lang['ap_mailn'].'</div></td>
 <td><input name="u_smtpl" value="'.$cfg['smtpl'].'" /></td>
</tr>
<tr>
 <td><b>8. '.$lang['pass'].' SMTP:</b><div class="txtm">'.$lang['ap_mailn'].'</div></td>
 <td><input type="password" name="u_smtph" value="'.$cfg['smtph'].'" /></td>
</tr>
<tr>
 <td class="eth" colspan="2"><input type="submit" value="'.$lang['save'].'" /> <input type="reset" value="'.$lang['reset'].'" /></td>
</tr>
');
eTable();
?>
</form>
<?php } ?>
