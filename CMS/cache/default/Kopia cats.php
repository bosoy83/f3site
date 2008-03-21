<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
	<td colspan="2" class="h"><b><?= $lang['cats'] ?></b></td>
</tr>

<? foreach($cats as &$cat): ?>
<tr>
	<td style="width: 15px">
		<img src="img/icon/folder.gif" alt="CAT" />
	</td>
	<td>
		<a class="listlink" href="<?= $cat['url'] ?>"><?= $cat['name'] ?></a> (<?= $cat['num'] ?>)
		<br />
		<small><?= $cat['desc'] ?></small>
	</td>
</tr>
<? endforeach; ?>

</tbody>
</table>
