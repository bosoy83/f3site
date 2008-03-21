<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$this->title;?></b></td>
</tr>

<?php foreach($groups as &$i1){?>

<tr>
<td class="txt">
<b><a href="?co=users&gid=<?=$i1['ID'];?>"><?=$i1['name'];?></a></b>
<br /><?=nl2br($i1['dsc']);?>
</td>
</tr>

<?php } ?>

</tbody>
</table>
