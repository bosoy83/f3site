<?php
if($_POST)
{
	
}
else
{
	require($catl.'adm_cfguser.php');
	echo '<form action="?a=cfguser" method="post">';
	cTable($lang['opt'].' :: '.$lang['users'],2);

	echo '
<tr>
	<td style="width: 35%">
		<b>'.$lang['reg_on'].'?</b><br />
	</td>
	<td>
		<input type="checkbox" name="u_reg_on"'.(($cfg['reg_on']==1)?' checked="checked"':'').' />
	</td>
</tr>
<tr>
	<td>
		<b>'.$lang['domainban'].':</b><br /><br />
		<small>'.$lang['domainex'].'</small>
	</td>
	<td>
		<textarea cols="30" rows="4" name="u_mailban">'.$cfg['mailban'].'</textarea>
	</td>
</tr>
<tr>
	<td>
		<b>'.$lang['nickban'].':</b><br /><br />
		<small>'.$lang['nickex'].'</small>
	</td>
	<td>
		<textarea cols="30" rows="4" name="u_nickban">'.$cfg['nickban'].'</textarea>
	</td>
</tr>';
	
	eTable();
	echo '</form>';
}
?>