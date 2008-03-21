<?php if($path){ ?>
<div class="cs">

<?php if($options){ ?>
<div style="float: right">

<a href="javascript:Hint('catmenu',0,0,1)"><?=$lang['opt'];?><img src="img/icon/d.png" alt="v" /></a>

<ul class="hint" style="left: inherit; top: inherit" id="catmenu">
<li onclick="location='<?=$add_url;?>'"><?=$lang['additem'];?></li>
<li onclick="location='<?=$list_url;?>'"><?=$lang['itemlist'];?></li>
</ul>

</div>
<?php } ?>

<a href="<?=$cats_url;?>"><?=$cat_type;?></a> &raquo; <?=$path;?>

</div>
<?php } ?>

<?php if($cat['text'] OR $subcats){ ?>
<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$cat['name'];?></b></td>
</tr>
<tr>
<td class="txt">

<?php if($cat['text']){ ?>
<p><?=nl2br($cat['text']);?></p>
<?php } ?>

<?php if($subcats){ ?>
<ul>
<?php foreach($subcats as &$i1){?>
<li><a href="<?=$i1['url'];?>"><?=$i1['name'];?></a> (<?=$i1['nums'];?>)</li>
<?php } ?>
</ul>
<?php } ?>
</td>
</tr>

</tbody>
</table>
<?php } ?>
