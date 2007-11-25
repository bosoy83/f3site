<?php
if(iCMS!=1) exit;
OpenBox($pm['topic'],2);
?>
<tr>
	<td class="pth" align="right" style="width: 25%"><?= $lang['author'] ?>:&nbsp;</td>
	<td><?= $pm_user ?></td>
</tr>
<tr>
	<td class="pth" align="right"><?= $lang['sent'] ?>:&nbsp;</td>
	<td><?= $pm_date ?></td>
</tr>
<tr>
	<td colspan="2"><?= $pm['txt'] ?></td>
</tr>
<tr>
	<td class="eth" colspan="2" id="pm_foot">
		<input type="button" value="<?= $pm_edit ?>" onclick="PM_Edit()" />
		<input type="button" value="<?= $lang['del'] ?>" onclick="PM_Del()" />
		<?= (($pm['st']==2)?'<input type="button" value="'.$lang['pm_25'].'" onclick="PM_Arch()" />':'') ?>
	</td>
</tr>
<?php CloseBox(); ?>
