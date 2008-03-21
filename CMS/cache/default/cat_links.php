<?php include STYLE_DIR.'cat.php';?>

<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h"><b><?=$lang['links'];?></b></td>
</tr>

<?php foreach($links as &$i1){?>
<tr>
<td style="background: url(img/icon/link.png) no-repeat 3px 50%; padding-left: 40px">
<a class="listlink" href="<?=$i1['url'];?>"><?=$i1['title'];?></a>
<?php if($i1['views']){ ?> (<?=$lang['disps'];?>: <?=$i1['views'];?>)<?php } ?><br />
<small><?=$i1['desc'];?></small>
</td>
</tr>
<?php } ?>

<?php if($pages){ ?>
<tr><td class="pages"><?=$pages;?></td></tr>
<?php } ?>

</tbody>
</table>
