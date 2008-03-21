<div class="cs">

<?php if($news['edit']){ ?>
<div style="float: right">
<img src="img/icon/edit.png" alt="&rarr;" /> <a href="<?=$news['edit'];?>"><?=$lang['edit'];?></a>
</div>
<?php } ?>

<a href="?co=cats&amp;id=5"><?=$lang['news'];?></a> &raquo; <?=$path;?>
</div>

<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$news['date'];?> - <?=$news['name'];?></b></td>
</tr>
<tr>
<td class="txt">

<?php if($news['img']){ ?>
<img src="<?=$news['img'];?>" alt="NEWS" />
<?php } ?>

<div><?=$news['txt'];?></div><br />
<div><?=$full;?></div><br />

<div align="right"><?=$lang['wrote'];?>: <?=$news['wrote'];?></div>
</td>
</tr>
</tbody>
</table>
