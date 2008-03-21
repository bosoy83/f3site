<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$lang['pm_3'];?></b></td>
</tr>
<tr>
<td>
<div style="float: right; text-align: center; width: 50%">
<?=$new;?><br /><br />
<?=$lang['pm_15'];?> <?=$size;?> / <?=$limit;?><br /><br />
<input type="button" value="<?=$lang['write'];?>" onclick="location='?co=pms&amp;act=e'" />
</div>
<div>
<b><?=$lang['pm_4'];?>:</b>
<ul style="list-style-image: url(img/icon/folder.png); margin: 3px">
<li><a href="?co=pms"><?=$lang['pm_5'];?></a></li>
<li><a href="?co=pms&amp;id=1"><?=$lang['pm_6'];?></a></li>
<li><a href="?co=pms&amp;id=2"><?=$lang['pm_8'];?></a></li>
<li><a href="?co=pms&amp;id=3"><?=$lang['pm_7'];?></a></li>
</ul>
</div>
</td>
</tr>
</tbody>
</table>

<?php if(isset($file)) include(STYLE_DIR.$file) ?>
