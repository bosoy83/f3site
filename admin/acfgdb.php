<?php
if(iCMSa!='X159E' || !ChPrv('CDB')) exit;
if($_POST)
{
 define('WHS','cfg/db.php');
 $_POST['u_db_db']=$db_db;
 require('admin/zc.php');
 Info($lang['saved']);
}
else
{
 include($catl.'adm_cfgdb.php');
 Info($lang['ap_dbwarn'].'<br /><br /><center><a href="?a=db">'.$lang['adb_t'].'</a></center>');
?>
<form action="?a=cfgdb" method="post">
<?php
cTable($lang['ap_cfgdb'],2);
echo('
<tr>
 <th colspan="2">'.$lang['ap_cfglang'].'</th>
</tr>
<tr>
 <td><b>1. '.$lang['ap_lang'].':</b></td>
 <td><select name="u_cms_lang">'.sListBox('lang',1,$cms_lang).'</select></td>
</tr>
<tr>
 <th colspan="2">'.$lang['ap_dbsets'].':</th>
</tr>
<tr>
 <td><b>2. '.$lang['ap_dbh'].':</b></td>
 <td><input name="u_db_h" value="'.$db_h.'" /></td>
</tr>
<tr>
 <td><b>3. '.$lang['user'].':</b></td>
 <td><input name="u_db_u" value="'.$db_u.'" /></td>
</tr>
<tr>
 <td><b>4. '.$lang['pass'].':</b></td>
 <td><input name="u_db_p" value="'.$db_p.'" type="password" /></td>
</tr>
<tr>
 <td><b>5. '.$lang['ap_dbd'].':</b></td>
 <td><input name="u_db_d" value="'.$db_d.'" /></td>
</tr>
<tr>
 <td><b>6. '.$lang['ap_dbpre'].':</b></td>
 <td><input name="u_db_pre" value="'.$db_pre.'" /></td>
</tr>
<tr class="eth">
 <td colspan="2"><input type="submit" value="'.$lang['save'].'" /> <input type="reset" value="'.$lang['reset'].'" /></td>
</tr>
');
eTable();
?>
</form>
<?php } ?>
