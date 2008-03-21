<form action="<?= $url ?>" method="post">
<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="3"><b><?= $this->title ?></b></td>
</tr>
<tr>
<th><?= $lang['title'] ?></th>
<th><?= $who ?></th>
<th style="width: 7%"><input type="checkbox" onclick="for(var i in document.getElementsByTagName(\'input\')) { if(x[i].name.indexOf(\'pmdel\')==0) x[i].checked=this.checked }" /></th>
</tr>

<?php foreach($pms as $pm)
{
echo '<tr>
<td>'.$pm['num'].'. <a href="'.$pm['url'].'">'.$pm['topic'].'</a></td>
<td align="center"><a href="'.$pm['user_url'].'">'.$pm['login'].'</a></td>
<td align="center"><input type="checkbox" name="pmdel['.$pm['ID'].']" /></td>
</tr>';
}
?>

<tr class="eth">
<td>
<input type="button" onclick="javascript: if(confirm(\'<?= $lang['pm_26'] ?>\')) this.form.submit()" value="<?= $lang['delch'] ?>" />
</td>
<td colspan="2">
<?= $lang['page'].': '.$pages ?>
</td>
</tr>
</tbody>
</table>
</form>
