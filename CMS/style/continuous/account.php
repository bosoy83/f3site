<form action="index.php?co=account" method="post">

<?php
if(iCMS!=1) exit;
OpenBox($lang['editu'],2);
?>

<tr>
	<th colspan="2"><?= $lang['editup'] ?></th>
</tr>

<?php if(LOGD==2) { ?>
<tr>
	<td><b><?= $lang['login'] ?>:</b><div class="txtm"><?= $lang['logind'] ?></div></td>
	<td><input name="xu_l" maxlength="30" value="<?= $xu_l ?>" /></td>
</tr>
<?php } ?>

<tr>
	<td><b><?= $lang['newpass'] ?>:</b><div class="txtm"><?= $lang['passd'] ?></div></td>
	<td><input type="password" name="xu_p" maxlength="30" /></td>
</tr>
<tr>
	<td width="45%"><b><?= $lang['retpass'] ?>:</b></td>
	<td><input maxlength="30" type="password" name="xu_p2" /></td>
</tr>
<tr>
	<td><b><?= $lang['mail'] ?>:</b></td>
	<td><input name="xu_m" value="<?= $data['mail'] ?>" maxlength="50" /></td>
</tr>

<?php if($cfg['imgsec']==1 && LOGD==2) { ?>
<tr>
	<td><b><?= $lang['imgcode'] ?>:</b></td>
	<td>
		<img src="code.php" alt="test" style="margin-bottom: 5px; border: 1px solid gray" />
		<br /><input name="xu_code" />
	</td>
</tr>
<?php } ?>

<tr>
	<th colspan="2"><?= $lang['editus'] ?></th>
</tr>
<tr>
	<td><b><?= $lang['ufrom'] ?></b></td>
	<td><input name="xu_fr" maxlength="50" value="<?= $data['city'] ?>" /></td>
</tr>
<tr>
	<td><b>Gadu-Gadu:</b></td>
	<td><input name="xu_gg" maxlength="15" value="<?= $data['gg'] ?>" /></td>
</tr>
<tr>
	<td><b>Tlen.pl ID:</b><div class="txtm"><?= $lang['tlenwot'] ?></div></td>
	<td><input name="xu_tl" maxlength="50" value="<?= $data['tlen'] ?>" /></td>
</tr>
<tr>
	<td><b>ICQ:</b></td>
	<td><input name="xu_icq" maxlength="20" value="<?= $data['icq'] ?>" /></td>
</tr>
<tr>
	<td><b>Skype ID:</b></td>
	<td><input name="xu_sk" maxlength="50" value="<?= $data['sk'] ?>" /></td>
</tr>
<tr>
	<td><b><?= $lang['wwwp'] ?>:</b></td>
	<td><input name="xu_w" size="30" maxlength="120" value="<?= $data['www'] ?>" /></td>
</tr>
<tr>
	<td><b><?= $lang['opt'] ?>:</b></td>
	<td>
		<input type="checkbox" name="xu_mvis" <?= $data['mvis'] ?> /> <?= $lang['vismail'] ?><br />
		<input type="checkbox" name="xu_mails" <?= $data['mails'] ?> /> <?= $lang['getmails'] ?>
	</td>
</tr>
<tr>
	<th colspan="2"><?= $lang['abouty'] ?></th>
</tr>
<tr>
	<td colspan="2" align="center">
		<textarea name="xu_ab" rows="7" cols="45"><?= $data['about'] ?></textarea>
	</td>
</tr>
<tr class="eth">
	<td colspan="2"><input type="submit" value="OK" /></td>
</tr>
</tbody>
</table>
</form>
