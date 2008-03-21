<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td colspan="2" class="h"><b><?=$lang['polls'];?></b></td>
</tr>
<tr>
<th><?=$lang['title'];?></th>
<th style="width: 10%"><?=$lang['title'];?></th>
<th style="width: 25%"><?=$lang['added'];?></th>
</tr>

<?php foreach($polls as &$i1){?>
<tr>
<td><?=$i1['num'];?>. <a href="<?=$i1['url'];?>"><?=$i1['title'];?></a></td>
<td align="center"><?=$i1['votes'];?></td>
<td align="center"><?=$i1['date'];?></td>
</tr>
<?php } ?>

</tbody>
</table>
