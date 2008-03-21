<form action="index.php?co=account" method="post">

<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$this->title;?></b></td>
</tr>

<tr>
<th colspan="2"><?=$lang['editup'];?></th>
</tr>

<?php if(LOGD $is 2){ ?>
<tr>
<td><b><?=$lang['login'];?>:</b><div class="txtm"><?=$lang['logind'];?></div></td>
<td><input name="x_l" maxlength="30" value="<?=$u['login'];?>" /></td>
</tr>
<?php } ?>


<tr>
<td><b><?=$lang['newpass'];?>:</b><div class="txtm"><?=$lang['passd'];?></div></td>
<td><input type="password" name="x_p" maxlength="30" /></td>
</tr>
<tr>
<td width="45%"><b><?=$lang['retpass'];?>:</b></td>
<td><input maxlength="30" type="password" name="x_p2" /></td>
</tr>
<tr>
<td><b><?=$lang['mail'];?>:</b></td>
<td><input name="x_m" value="<?=$u['mail'];?>" maxlength="50" /></td>
</tr>

<?php if($code){ ?>
<tr>
<td><b><?=$lang['imgcode'];?>:</b></td>
<td>
<img src="code.php" alt="test" style="margin-bottom: 5px; border: 1px solid gray" />
<br /><input name="x_code" />
</td>
</tr>
<?php } ?>

<tr>
<th colspan="2"><?=$lang['editus'];?></th>
</tr>
<tr>
<td><b><?=$lang['ufrom'];?></b></td>
<td><input name="x_city" maxlength="50" value="<?=$u['city'];?>" /></td>
</tr>
<tr>
<td><b>Gadu-Gadu:</b></td>
<td><input name="x_gg" maxlength="15" value="<?=$u['gg'];?>" /></td>
</tr>
<tr>
<td><b>Tlen.pl ID:</b><div class="txtm"><?=$lang['tlenwot'];?></div></td>
<td><input name="x_tl" maxlength="50" value="<?=$u['tlen'];?>" /></td>
</tr>
<tr>
<td><b>ICQ:</b></td>
<td><input name="x_icq" maxlength="20" value="<?=$u['icq'];?>" /></td>
</tr>
<tr>
<td><b>Skype ID:</b></td>
<td><input name="x_sk" maxlength="50" value="<?=$u['skype'];?>" /></td>
</tr>
<tr>
<td><b><?=$lang['wwwp'];?>:</b></td>
<td><input name="x_w" size="30" maxlength="120" value="<?=$u['www'];?>" /></td>
</tr>
<tr>
<td><b><?=$lang['opt'];?>:</b></td>
<td>
<input id="mv" type="checkbox" name="x_mvis" <?php if($u['mvis']){ ?>checked="checked"<?php } ?> />
<label for="mv"><?=$lang['vismail'];?></label><br />
<input id="ms" type="checkbox" name="x_mails" <?php if($u['mails']){ ?>checked="checked"<?php } ?> />
<label for="ms"><?=$lang['getmails'];?></label>
</td>
</tr>
<tr>
<th colspan="2"><?=$lang['abouty'];?></th>
</tr>
<tr>
<td colspan="2" align="center">
<textarea name="x_ab" rows="7" cols="45" style="width: 99%"><?=$u['about'];?></textarea>
</td>
</tr>
<tr>
<td colspan="2" class="eth"><input type="submit" value="OK" /></td>
</tr>
</tbody>
</table>
</form>
