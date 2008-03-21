<?php include STYLE_DIR.'cat.php';?>

<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h"><b><?=$lang['arts'];?></b></td>
</tr>

<?php foreach($arts as &$i1){?>
<tr>
<td style="background: url(img/icon/art.png) no-repeat 6px 50%; padding-left: 40px">
<a class="listlink" href="<?=$i1['url'];?>"><?=$i1['title'];?></a> (<?=$i1['date'];?>)<br />
<small><?=$i1['desc'];?></small>
</td>
</tr>
<?php } ?>

<?php if($pages){ ?>
<tr><td class="pages"><?=$pages;?></td></tr>
<?php } ?>

</tbody>
</table>
