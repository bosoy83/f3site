<?php include STYLE_DIR.'cat.php';?>

<?php foreach($news as &$i1){?>
<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$i1['date'];?> - <?=$i1['title'];?></b></td>
</tr>
<tr>
<td class="txt">

<?php if($i1['img']){ ?>
<img src="<?=$i1['img'];?>" alt="NEWS" />
<?php } ?>

<?=$i1['text'];?>

<?php if($i1['full_url']){ ?>
<b><a href="<?=$i1['full_url'];?>"><?=$lang['more'];?></b>
<?php } ?>

</td>
</tr>
<tr>
<td class="eth" style="font-weight: normal; padding: 1px">

<?php if($i1['comm_url']){ ?>
<span style="float: right; width: 230px"><a href="<?=$i1['comm_url'];?>"><?=$lang['comms'];?></a> (<?=$i1['comm'];?>)</span>
<?php } ?>

<?php if($i1['edit_url']){ ?>
<span style="float: left; width: 100px">
<a href="<?=$i1['edit_url'];?>"><img src="img/icon/edit.png" alt="EDIT" /></a>
</span>
<?php } ?>

<?=$lang['wrote'];?>: <a href="<?=$i1['wrote_url'];?>" style="width: 150px"><?=$i1['wrote'];?></a>

</td>
</tr>
</tbody>
</table>
<?php } ?>
