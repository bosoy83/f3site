<form action="<?=$url;?>" name="art" method="post">
<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$this->title;?></b></td>
</tr>
<tr>
<td style="width: 31%"><b>1. <?=$lang['cat'];?>:</b></td>
<td><select name="x_c">
<option value="0"><?=$lang['choose'];?></option><?=$cats;?>
</select></td>
</tr>
<tr>
<td><b>2. <?=$lang['name'];?>:</b></td>
<td><input maxlength="50" size="30" name="x_n" value="<?=$file['name'];?>" /></td>
</tr>
<tr>
<td><b>3. <?=$lang['published'];?>?</b></td>
<td><input type="checkbox" name="x_a"<?php if($file['access']){ ?> checked="checked"<?php } ?> /></td>
</tr>
<tr>
<td><b>4. <?=$lang['priot'];?>:</b></td>
<td><select name="x_p">
<option value="1"><?=$lang['high'];?></option>
<option value="2"<?php if($link['priority'] = 2){ ?> selected="selected"<?php } ?>><?=$lang['normal'];?></option>
<option value="3"<?php if($link['priority'] = 3){ ?> selected="selected"<?php } ?>><?=$lang['low'];?></option>
</select></td>
</tr>
<tr>
<td><b>5. <?=$lang['desc'];?>:</b></td>
<td><textarea name="x_d" style="width: 95%"><?=$file['dsc'];?></textarea></td>
</tr>
<tr>
<td><b>7. <?=$lang['file'];?>:</b></td>
<td>
<input name="x_f" maxlength="230" value="<?=$file['file'];?>" size="30" />
<?php if(Admit('FM')): ?>

<input type="button" value="<?=$lang['files'];?> &raquo;" onclick="Okno('?x=fm&amp;ff=x_f&amp;dir=./files/',580,400,150,150)" />

<?php endif ?>
</td>
</tr>
<tr>
<td><b>8. <?=$lang['size'];?>:</b></td>
<td><input name="x_s" maxlength="20" value="<?=$file['size'];?>" /></td>
</tr>
<tr>
<td><b>9. <?=$lang['author'];?>:</b><br /><small><?=$lang['nameid'];?></small></td>
<td><input name="x_au" maxlength="30" value="<?=$file['author'];?>" /></td>
</tr>
<tr>
<th colspan="2"><?=$lang['fulld'];?></th>
</tr>
<tr>
<td colspan="2" align="center">
<textarea name="x_fd" id="fd" rows="8" style="width: 100%"><?= Clean($file['fulld']) ?></textarea>
</td>
</tr>
<tr class="eth">
<td colspan="2"><input type="submit" value="<?=$lang['save'];?>" /></td>
</tr>
</tbody>
</table>
</form>
<script type="text/javascript">
var ed=new Editor("fd");
</script>
