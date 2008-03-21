<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h"><b><?=$lang['info'];?></b></td>
</tr>
<tr>
<td class="txt" style="padding: 8px">
<?=$info;?>
<?php if($links){ ?>
<ul style="list-style-image: url(img/icon/go.png)">
<?php foreach($links as $key=>&$i1){?>
<li><a href="<?=$key;?>"><?=$i1;?></a></li>
<?php } ?>
</ul>
<?php } ?>
</td>
</tr>
</tbody>
</table>
