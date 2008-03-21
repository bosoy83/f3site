<?php if(iCMS!=1) exit; ?>

<table cellspacing="0" cellpadding="2" style="width: 100%" align="center">
<tbody>
<tr>
	<td><?= $lang['skin'] ?></td>
	<td>
	<select name="ch_s" onchange="setCookie('<?= PRE ?>tstyle',this.options[this.selectedIndex].value,7200)">
		<?= ListBox('style',1,$GLOBALS['nstyl']) ?>
	</select>
	</td>
</tr>
<tr>
	<td><?= $lang['lang'] ?></td>
	<td>
	<select name="ch_l" onchange="setCookie('<?= PRE ?>tlang',this.options[this.selectedIndex].value,7200)" style="text-transform: uppercase">
		<?= ListBox('lang',1,$GLOBALS['nlang']) ?>
	</select>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" value="OK" onclick="location.reload()" />
	</td>
</tr>
</tbody>
</table>
