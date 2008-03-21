<div class="cs">

<?php if($art['edit']){ ?>
<div style="float: right">
<img src="img/icon/edit.png" alt="&rarr;" /> <a href="<?=$art['edit'];?>"><?=$lang['edit'];?></a>
</div>
<?php } ?>

<a href="?co=cats&amp;id=1"><?=$lang['arts'];?></a> &raquo; <?=$path;?>
</div>

<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h"><b><?=$art['date'];?> - <?=$art['name'];?></b></td>
</tr>
<tr>
<td class="txt">
<?=$art['text'];?>

<?php if($pages){ ?><br /><br /><div class="pages"><?=$pages;?></div><?php } ?>

</td>
</tr>
<tr>
<td class="eth" style="font-weight: normal">

<?php if($art['rate']){ ?>
<span style="float: right; width: 150px"><?=$lang['rate'];?>: <?=$art['rate'];?></span>
<?php } ?>

<?php if($art['ent']){ ?>
<span style="float: left; width: 150px"><?=$lang['disps'];?>: <?=$art['ent'];?></span>
<?php } ?>

<span style="width: 150px"><?=$lang['wrote'];?>: <?=$art['author'];?></span>
</td>
</tr>
</tbody></table>
