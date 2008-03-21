<?php if($del){ ?>
<script type="text/javascript">
//<![CDATA[
function Del(id)
{
if(confirm("<?=$lang['ap_delcat'];?>"))
{
del=new Request("adm.php?x=del&id="+id,'i'+id);
del.method='POST';
del.add('co','cat')
del.run()
}
}
//]]>
</script>
<?php } ?>

<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="5"><b><?=$lang['ap_dnagz'];?></b></td>
</tr>
<tr>
<th><?=$lang['name'];?></th>
<th style="width: 30px">ID</th>
<th style="width: 50px"><?=$lang['ap_disp'];?></th>
<th><?=$lang['ap_type'];?></th>
<th><?=$lang['opt'];?></th>
</tr>

<?php foreach($cat as &$i1){?>

<tr>
<td id="i<?=$i1['ID'];?>"><?= str_repeat('&raquo; &nbsp;',$i1['depth']) ?>
<a href="<?=$i1['url'];?>"><?=$i1['name'];?></a> (<?=$i1['num'];?>)
</td>
<td align="center"><?=$i1['ID'];?></td>
<td align="center"></td>
<td align="center"><?=$i1['type'];?></td>
<td align="center">
<a href="adm.php?a=editcat&amp;id=<?=$i1['ID'];?>"><?=$lang['edit'];?></a>
<?php if($i1['del']){ ?>&middot; <a href="javascript:Del(<?=$i1['ID'];?>)"><?=$lang['del'];?></a><?php } ?>
</td>
</tr>
<?php } ?>

</tbody>
</table>