<form action="<?=$url;?>" name="art" method="post">
<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$this->title;?></b></td>
</tr>
<tr>
  <td style="width: 30%"><b>1. <?=$lang['cat'];?>:</b></td>
  <td><select name="x_c">
<option value="0"><?=$lang['choose'];?></option><?=$cats;?>
</select></td>
</tr>
<tr>
  <td><b>2. <?=$lang['title'];?>:</b></td>
  <td><input maxlength="50" size="30" name="x_n" value="<?=$img['name'];?>" /></td>
</tr>
<tr>
  <td><b>3. <?=$lang['published'];?>:</b></td>
  <td><input type="checkbox" name="x_a"<?php if($img['access'] = 1){ ?> checked="checked"<?php } ?> /></td>
</tr>
<tr>
  <td><b>4. <?=$lang['priot'];?>:</b></td>
  <td><select name="x_p">
<option value="1"><?=$lang['high'];?></option>
<option value="2"<?php if($img['priority'] = 2){ ?> selected="selected"<?php } ?>><?=$lang['normal'];?></option>
<option value="3"<?php if($img['priority'] = 3){ ?> selected="selected"<?php } ?>><?=$lang['low'];?></option>
</select></td>
</tr>
<tr>
  <td><b>5. <?=$lang['img'];?>:</b></td>
  <td>
<input name="x_f" id="x_f" maxlength="200" value="<?=$img['file'];?>" />
<input type="button" value="<?=$lang['preview'];?>" onclick="Okno(x_f.value,500,400,100,100)" />
<?php
if(Admit('FM'))
{
echo ' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=x_f\',580,400,150,150)" />';
}
?>
</td>
</tr>
<tr>
  <td><b>6. <?=$lang['minimg'];?>:</b><br /><small><?=$lang['minimgd'];?></small></td>
  <td>
<input name="x_fm" maxlength="50" value="<?=$img['filem'];?>" />
<input type="button" value="<?=$lang['preview'];?>" onclick="Okno(x_fm.value,500,400,100,100)" />
<?php
if(Admit('FM'))
{
echo ' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=x_fm\',580,400,150,150)" />';
}
?>
</td>
</tr>
<tr>
  <td><b>7. <?=$lang['author'];?>:</b><br /><small><?=$lang['nameid'];?></small></td>
  <td><input name="x_au" maxlength="30" value="<?=$img['author'];?>" /></td>
</tr>
<tr>
  <td><b>8. <?=$lang['type'];?>:</b></td>
  <td>
<input type="radio" name="x_t" value="1"<?php if($img['type'] = 1){ ?> checked="checked"<?php } ?> /> <?=$lang['img'];?> &lt;img&gt; &nbsp;
<input type="radio" name="x_t" value="2"<?php if($img['type'] = 2){ ?> checked="checked"<?php } ?> /> Flash &nbsp;
<input type="radio" name="x_t" value="3"<?php if($img['type'] = 3){ ?> checked="checked"<?php } ?> /> QuickTime
</td>
</tr>
<tr>
  <td><b>9. <?=$lang['isize'];?>:</b><div class="txtm"><?=$lang['isized'];?></div></td>
  <td>
<input name="x_s1" value="{size.0}" size="2" maxlength="4" /> x
<input name="x_s2" value="<?=$size['1'];?>" size="2" maxlength="4" /> (px)
</td>
</tr>
<tr>
<th colspan="2"><?=$lang['desc'];?></th>
</tr>
<tr>
  <td colspan="2" align="center">
<textarea name="x_d" id="x_d" rows="8" style="width: 100%"><?= 
Clean($img['dsc']) ?></textarea>
</td>
</tr>
<tr>
  <td colspan="2" class="eth"><input type="submit" value="<?=$lang['save'];?>" /></td>
</tr>
</tbody>
</table>
</form>
<script type="text/javascript">
var ed=new Editor('x_d');
</script>
