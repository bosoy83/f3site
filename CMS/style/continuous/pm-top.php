<?php
if(iCMS!=1) exit;
OpenBox($lang['pm_3']);
?>
<tr>
	<td style="padding: 3px">
	<table width="100%" cellspacing="0" cellpadding="0"><tbody>
	<tr>
		<td colspan="2"><b><?= $lang['pm_4'] ?>:</b></td>
		<td rowspan="5" style="width: 60%" align="center">
			<?= $pm_new ?> <br /><br />
			<?= $pm_ile ?> <br /><br />
		<input type="button" value="<?= $lang['write'] ?>" onclick="location='?co=pms&amp;act=e'" />
		</td>
	</tr>
	<tr>
		<td style="width: 23px"><img src="<?= SCIMG ?>" alt="[f]" /></td>
		<td><a href="?co=pms"><?= $lang['pm_5'] ?></a></td>
	</tr>
	<tr>
		<td><img src="<?= SCIMG ?>" alt="[f]" /></td>
		<td><a href="?co=pms&amp;id=1"><?=$lang['pm_6'] ?></a></td>
	</tr>
	<tr>
		<td><img src="<?= SCIMG ?>" alt="[f]" /></td>
		<td><a href="?co=pms&amp;id=2"><?=$lang['pm_8'] ?></a></td>
	</tr>
	<tr>
		<td><img src="<?= SCIMG ?>" alt="[f]" /></td>
		<td><a href="?co=pms&amp;id=3"><?=$lang['pm_7'] ?></a></td>
	</tr>
	</tbody></table>
	</td>
</tr>
<?php CloseBox() ?>
