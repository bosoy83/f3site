<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
	<td class="h" colspan="2"><b><?= $this->title ?></b></td>
</tr>

<?php if(isset($_GET['id'])): ?>
<tr>
	<th><?= $lang['title'] ?></th>
	<th><?= $lang['added'] ?></th>
</tr>

<?php
foreach($news as $x)
{
	echo '<tr>
	<td>'.++$num.'. <a href="'.$x['url'].'">'.$x['url'].'</a></td>
	<td align="center">'.genDate($news[2]).'</td>
</tr>';
}

else:
?>
<tr>
	<td class="txt" style="line-height: 150%; text-align: center">
	
	<?php
	foreach($dates as $x)
	{
		echo '<a href="'.$x['url'].'">'.$x['title'].'</a><br />';
	}
	?>

	</td>
</tr>

<?php endif; ?>
</tbody>
</table>
