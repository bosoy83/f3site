<?php include STYLE_DIR.'cat.php';?>

<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h"><b><?=$lang['files'];?></b></td>
</tr>

<?php foreach($files as &$i1){?>
<tr>
<td style="background: url(img/icon/file.png) no-repeat 9px 50%; padding-left: 35px">
<a class="listlink" href="<?=$i1['url'];?>"><?=$i1['title'];?></a> (<?=$i1['date'];?>)<br />
<small><?=nl2br($i1['desc']);?></small>
</td>
</tr>
<?php } ?>

<?php if($pages){ ?>
<tr><td class="pages"><?=$pages;?></td></tr>
<?php } ?>

</tbody>
</table>
