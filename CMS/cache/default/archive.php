<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$this->title;?></b></td>
</tr>

<?php if($newslist){ ?>
<tr>
<th><?=$lang['title'];?></th>
<th><?=$lang['added'];?></th>
</tr>

<?php foreach($news as &$i2){?>
<tr>
<td><?=$i2['num'];?>. <a href="<?=$i2['url'];?>"><?=$i2['title'];?></a></td>
<td align="center"><?=$i2['date'];?></td>
</tr>
<?php } ?>

<?php }else{?>

<tr>
<td class="txt" style="line-height: 150%; text-align: center">

<?php foreach($dates as &$i1){?>
<?php if($i1['url']){ ?><a href="<?=$i1['url'];?>"><?=$i1['title'];?></a><br /><?php }else{?><br /><?php } ?>
<?php } ?>

</td>
</tr>

<?php } ?>

</tbody>
</table>