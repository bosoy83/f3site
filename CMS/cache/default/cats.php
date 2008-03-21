<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td colspan="2" class="h"><b><?=$lang['arts'];?></b></td>
</tr>

<?php foreach($cats as &$i1){?>
<tr>
<td style="width: 15px">
<img src="img/icon/folder.gif" alt="CAT" />
</td>
<td>
<a class="listlink" href="<?=$i1['url'];?>"><?=$i1['title'];?></a> (<?=$i1['num'];?>)
<br />
<small><?=$i1['desc'];?></small>
</td>
</tr>
<?php } ?>

</tbody>
</table>
