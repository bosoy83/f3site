<script type="text/javascript">
//<![CDATA[
function Del(id)
{
if(confirm("<?=$lang['delc'];?>"))
{
del=new Request("adm.php?x=del&id="+id,'i'+id);
del.method='POST';
del.add('co','<?=$type;?>')
del.run()
}
}
function Se()
{
if(a=prompt("<?=$lang['searp'];?>")) location="?co=edit&act=<?=$act;?>&find="+a;
}
//]]>
</script>

<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$lang['info'];?></b>
</tr>
<tr>
<td align="center" class="info">
<?=$intro;?><br /><br />
<a href="javascript:Se()"><?=$lang['search'];?></a> |
<a href="<?=$add_url;?>"><?=$lang['additem'];?></a> |
<?php if($cats_url){ ?><a href="<?=$cats_url;?>"><?=$lang['cats'];?>: <?=$type;?></a><?php } ?>
</td>
</tr>
</tbody>
</table>

<!--LISTA-->
<form action="<?=$url;?>" method="post">
<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="4"><b><?=$type;?></b>
</tr>
<tr>
<th><?=$lang['name'];?></th>
<th style="width: 50px"><?=$lang['ison'];?></th>
<th><?=$lang['opt'];?></th>
<th style="width: 25px">&nbsp;</th>
</tr>

<?php foreach($item as &$i1){?>

<tr>
<td id="i<?=$i1['ID'];?>"><?=$i1['num'];?>. <a href="<?=$i1['url'];?>"><?=$i1['title'];?></a></td>
<td align="center"><?=$i1['on'];?></td>
<td align="center" nowrap="nowrap">
<a href="<?=$i1['edit_url'];?>"><?=$lang['edit'];?></a> &middot; <a href="<?=$i1['del_url'];?>"><?=$lang['del'];?></a>
</td>
<td><input type="checkbox" name="chk[<?=$i1['ID'];?>]" /></td>
</tr>

<?php } ?>

<tr>
<td class="eth">
<a href="javascript:Show('mo')"><?=$lang['chopt'];?> &raquo;</a>
</td>
<td class="eth" colspan="3">
<b><?=$lang['page'];?>:</b> <?=$pages;?>
</td>
</tr>
</tbody>
</table>

<!--Masowe zmiany-->
<table cellspacing="1" class="tb" id="mo" style="display: none">
<tbody class="bg">
<tr>
<td class="h" colspan="2">ID</th>
</tr>
<tr>
<td style="width: 35%"><b>1. <?=$lang['cat'];?>:</b></td>
<td><select name="xu_c">
<option value="N"><?=$lang['nochg'];?></option>
<?=$cats;?>
<option value="0"><?=$lang['lack'];?></option>
</select></td>
</tr>
<tr>
<td><b>2. <?=$lang['published'];?>?</b></td>
<td><select name="xu_a">
<option value="N"><?=$lang['nochg'];?></option>
<option value="1"><?=$lang['yes'];?></option>
<option value="2"><?=$lang['no'];?></option>
</select></td>
</tr>
<tr>
<td><b>3. <?=$lang['opt'];?>:</b></td>
<td><input type="checkbox" name="xu_d" id="xu_d" /> <?=$lang['chdel'];?></td>
</tr>
<tr>
<td class="eth" colspan="2"><input type="submit" value="OK" /></td>
</tr>
</tbody>
</table>
</form>
<script type="text/javascript">
d("xu_d").checked=0
</script>
