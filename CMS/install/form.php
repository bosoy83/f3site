<script type="text/javascript">
//<![CDATA[
function Change()
{
	var f=document.forms[0].elements;
	var v=(f[0].checked)?'':'disabled';
	f[2].disabled=v;
	f[3].disabled=v;
	f[4].disabled=v;
	if(f[5].value=='' && v) f[5].value='./cfg/db.db';
	if(f[5].value=='./cfg/db.db' && !v) f[5].value='';
}
//]]>
</script>
<form action="index.php" method="post">
<table cellspacing="1">
<tbody>
<tr>
	<th colspan="2"><b><?= $lang[17] ?></b></th>
</tr>
<tr>
	<td colspan="2"><?= $lang[16].$_SERVER['HTTP_HOST'] ?></td>
</tr>
<tr>
	<td style="width: 55%">1. <?= $lang[18] ?></td><td>
		<input type="radio" value="mysql" name="db_db" checked="checked" onclick="Change()" id="m" />
			<label for="m">MySQL 5+</label><br />
		<input type="radio" value="sqlite" name="db_db" onclick="Change()" id="s" />
			<label for="s">SQLite 3+</label>
		</select>
	</td>
</tr>
<tr>
	<td>2. <?= $lang[19] ?></td><td><input name="db_h" value="localhost" /></td>
</tr>
<tr>
	<td>3. <?= $lang[20] ?></td><td><input name="db_u" /></td>
</tr>
<tr>
	<td>4. <?= $lang[21] ?></td><td><input type="password" name="db_p" /></td>
</tr>
<tr>
	<td>5. <?= $lang[22] ?><br /><small><?= $lang[28] ?> <b>A-Z, a-z, 1-9, _, -</b>. <?= $lang[52] ?></small></td>
	<td><input name="db_d" /></td>
</tr>
<tr>
	<td>6. <?= $lang[23] ?><br /><small><?= $lang[24] ?></small></td>
	<td><input name="db_pre" value="f3s_" onblur="if(this.value=='') this.value='f3s_'" /></td>
</tr>
<tr>
	<td>7. <?= $lang[41] ?></td>
	<td><input type="checkbox" name="db_del" id="del" /> <label for="del"><?= $lang[51] ?></label></td>
</tr>
<tr>
 <th colspan="2"><b><?= $lang[25] ?></b></th>
</tr>
<tr>
 <td>8. <?= $lang[26] ?></td><td><input name="u_login" /></td>
</tr>
<tr>
 <td>9. <?= $lang[27] ?><br /><small><?= $lang[28] ?> <b>A-Z, a-z, 1-9, _, -</b>.</small></td>
 <td><input name="u_pass" type="password" /></td>
</tr>
<tr>
 <td>10. <?= $lang[29] ?></td><td><input name="u_pass2" type="password" /></td>
</tr>
<tr>
 <td colspan="2"><?= $lang[30] ?></td>
</tr>
<tr>
 <th colspan="2" align="center"><input type="submit" value="OK" /></th>
</tr>
</tbody>
</table>
</form>