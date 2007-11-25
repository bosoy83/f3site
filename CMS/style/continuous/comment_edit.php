<form action="<?= $c_url ?>" name="comm" method="post">
<?php
OpenBox($c_box_title,2);
echo (($c_guest)?'
<tr>
	<td><b>'.$lang['code'].':</b><br /><small>'.$lang['imgcode'].'.</small></td>
	<td>
		<img src="code.php" alt="CODE" style="margin-bottom: 5px; border: 1px solid gray" />
		<br /><input name="c_code" />
	</td>
</tr>':'') . (($c_code)?'
<tr>
	<td><b>'.$lang['author'].':</b></td>
	<td><input name="c_a" maxlength="20" value="'.$c_author.'" /></td>
</tr>':'') ?>
<tr>
	<td style="width: 110px"><b><?= $lang['title'] ?>:</b></td>
	<td><input name="c_n" value="<?= $c_name ?>" maxlength="40" /></td>
</tr>
<tr>
  <td colspan="2" align="center">
		<textarea name="c_t" id="c_t" rows="7" cols="60"><?= $c_text ?></textarea>
	</td>
</tr>
<tr>
	<td colspan="2" class="eth">
		<input type="submit" value="<?= $lang['preview'] ?>" name="prev" />
		<input type="submit" value="<?= $lang['save'] ?>" name="save" />
		<input type="submit" value="<?= $lang['more'] ?>" name="more" />
	</td>
</tr>
<?php CloseBox() ?>
</form>