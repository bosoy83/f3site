<?php if(iCMS!='E123') exit; ?>
<form action="index.php" method="post">
<table align="center" style="width: 500px">
<tbody>
<tr>
 <th><b><?= $lang['etp1'] ?></b></th>
</tr>
<tr>
 <td><?= $lang['e1txt'] ?></td>
</tr>
</tbody>
</table>
<table align="center" style="width: 500px">
<tbody>
<tr>
 <th colspan="2"><b><?= $lang['etp1b'] ?></b></th>
</tr>
<tr>
 <td style="width: 280px">1. <?= $lang['type'] ?>:</td><td>
  <select name="db_db">
   <option value="mysql">MySQL</option>
  </select>
 </td>
</tr>
<tr>
 <td>2. <?= $lang['host'] ?>:</td><td><input name="db_h" value="localhost" /></td>
</tr>
<tr>
 <td>3. <?= $lang['login'] ?>:</td><td><input name="db_u" /></td>
</tr>
<tr>
 <td>4. <?= $lang['pass'] ?>:</td><td><input type="password" name="db_p" /></td>
</tr>
<tr>
 <td>5. <?= $lang['n'] ?>:<div class="txtm"></div></td><td><input name="db_d" /></td>
</tr>
<tr>
 <td>6. <?= $lang['prefix'] ?>:<div class="txtm"><?= $lang['prd'] ?></div></td><td><input name="db_pre" value="f3s_" onblur="if(this.value=='') this.value='f3s_'" /></td>
</tr>
<tr>
 <th colspan="2"><b><?= $lang['account'] ?></b></th>
</tr>
<tr>
 <td>7. <?= $lang['adml'] ?>:</td><td><input name="uadml" /></td>
</tr>
<tr>
 <td>8. <?= $lang['admh'] ?>:<br /><span class="txtm"><?= $lang['use'] ?> <b>A-Z</b>, <b>a-z</b>, <b>1-9</b>, '<b>_</b>', '<b>-</b>'.</span></td><td><input name="uadmh" type="password" /></td>
</tr>
<tr>
 <td>9. <?= $lang['admh2'] ?>:</td><td><input name="uadmh2" type="password" /></td>
</tr>
<tr>
 <td colspan="2"><?= $lang['other'] ?></td>
</tr>
<tr>
 <th colspan="2" align="center"><input type="submit" value="OK" /></th>
</tr>
</tbody>
</table>
<input type="hidden" name="etap" value="2" />
<input type="hidden" name="lng" value="<?= $_POST['lng'] ?>" />
</form>
