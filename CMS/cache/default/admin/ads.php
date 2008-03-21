<script type="text/javascript">
<!--
function Del(id)
{
if(confirm("<?=$lang['ap_delc']?>"))
{
del=new Request('adm.php?x=del&id='+id,'i'+id);
del.method='POST';
del.add('co','b')
del.run();
}
}
-->
</script>

<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="4"><b><?= $lang['banners'] ?></b></td>
</tr>
<tr>
<th><?= $lang['name'] ?></th>
<th style="width: 80px"><?= $lang['ap_disp'] ?></th>
<th style="width: 50px">GID</th>
<th><?= $lang['opt'] ?></th>
</tr>

<?php $num = 0; foreach($ads as $ad): echo '
<tr>
<td id="i'.$ad['ID'].'">'.++$num.'. '.$ad['name'].'</td>
<td align="center">'.(($ad['access']==1) ? $lang['ap_ison'] : $lang['ap_isoff']).'</td>
<td align="center">'.$ad['GID'].'</td>
<td align="center">
<a href="?a=editad&amp;id='.$ad['ID'].'">'.$lang['edit'].'</a>'.
(($del)?' &middot; <a href="javascript:Del('.$ad['ID'].')">'.$lang['del'].'</a>':'').'
</td>
</tr>';
endforeach ?>

</tbody>
</table>