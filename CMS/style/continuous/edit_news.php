<form action="<?= $form_url ?>" method="post">
<?= OpenBox($lang[ (($id)?'edit5':'add5') ],2); ?>
<tr>
	<td style="width: 31%"><b>1. <?= $lang['cat'] ?>:</b></td>
	<td><select name="x_c"><?= Slaves(5,$news['cat']) ?></select></td>
</tr>
<tr>
	<td><b>2. <?= $lang['title'] ?>:</b></td>
	<td><input maxlength="50" name="x_n" value="<?= $news['name'] ?>" /></td>
</tr>
<tr>
	<td><b>3. <?= $lang['published'] ?>?</b></td>
	<td><input type="checkbox" name="x_a"<?= (($news['access']==1)?' checked="checked"':'') ?> /></td>
</tr>
<tr>
	<td><b>4. <?= $lang['img'] ?>:</b></td>
	<td>
		<input name="x_i" id="x_i" value="<?= $news['img'] ?>" /><?= 
		((Admit('FM'))?' <input type="button" value="<?= $lang['images'] ?> &raquo;" onclick="Okno(\'?x=fm&amp;ff=xu_i\',580,400,150,150)" />':'') ?>
	</td>
</tr>
<tr>
	<td><b>5. <?= $lang['opt'] ?>:</b></td>
	<td>
		<input type="checkbox" name="x_br"<?= (($news['opt']&1)?' checked="checked"':'') ?> /> <?= $lang['e_br'] ?><br />
		<input type="checkbox" name="x_emo"<?= (($news['opt']&2)?' checked="checked"':'') ?> /> <?= $lang['emoon'] ?><br />
		<input type="checkbox" id="fn" name="x_fn"<?= (($news['opt']&4)?' checked="checked"':'') ?> onclick="FN()" /> <?= $lang['ftxt'] ?>
	</td>
</tr>';
CloseBox();

#Tre¶æ
OpenBox($lang['text'],1);
echo '
<tr>
	<td align="center">
		<textarea style="width: 100%" rows="9" id="x_txt" name="x_txt">'
		.Clean($news['txt']) ?></textarea>
	</td>
</tr>
<tr class="eth">
	<td>
		<input type="button" value="<?= $lang['preview'] ?>" onclick="Prev()" />
		<input type="submit" name="sav" value="<?= $lang['save'] ?>" />
	</td>
</tr>
<?= CloseBox(); ?>

<div id="full"<?= (($news['opt']&4)?'':' style="display: none"') ?>
<?= OpenBox($lang['ftxt'],1); ?>
<tr>
  <td align="center">
		<textarea style="width: 100%" id="x_ftxt" rows="12" name="x_ftxt">
		<?= Clean($news['text']) ?></textarea>
	</td>
</tr>
<?= CloseBox(); ?>
</div>
</form>
