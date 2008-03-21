<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="3"><b><?=$lang['users'];?> (<?=$total;?>)</b></td>
</tr>
<tr>
<th><a href="<?=$login_url;?>"><?=$lang['login'];?></a></th>
<th><a href="<?=$joined_url;?>"><?=$lang['joined'];?></a></th>
<th><?=$lang['rights'];?></th>
</tr>

<?php foreach($users as &$i1){?>

<tr>
<td><?=$i1['num'];?>. <a href="<?=$i1['url'];?>"><?=$i1['login'];?></a></td>
<td align="center"><?=$i1['date'];?></td>
<td align="center"><?=$i1['level'];?></td>
</tr>

<?php } ?>

<tr class="eth">
<td style="font-weight: normal">
<?php if($find){ ?>
<a href="javascript:Show('sf')"><?=$lang['search'];?> &raquo;</a>
<?php } ?>
</td>
<td colspan="2"><?=$lang['page'];?>: <?=$pages;?></td>
</tr>
</tbody>
</table>

<?php if($find){ ?>

<form method="get" id="sf" action="index.php" style="display: none">
<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="3"><b><?=$lang['search'];?></b></td>
</tr>
<tr>
<td>
<table style="width: 100%" align="center"><tbody>
<tr>
<td><?=$lang['login'];?> <?=$lang['cont'];?>:<br />
<input name="sl" maxlength="40" value="<?=$find_login;?>" />
</td>
<td><?=$lang['ufrom'];?><br />
<input name="pl" maxlength="50" value="<?=$find_place;?>" />
</td>
</tr>
<tr>
<td><?=$lang['wwwp'];?>:<br />
<input name="l" maxlength="40" value="<?=$find_www;?>" />
</td>
<td>
<br /><input type="submit" value="OK" />
</td>
</tr>
</tbody></table>
</td>
</tr>
</tbody>
</table>
<input type="hidden" name="id" value="<?=$id;?>" />
<input type="hidden" name="co" value="users" />
</form>

<?php } ?>
