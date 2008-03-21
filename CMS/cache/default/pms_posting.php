<form action="<?=$url;?>" method="post" name="pm">
<table cellspacing="1" class="tb">
<tbody class="bg">
<tr>
<td class="h" colspan="2"><b><?=$this->title;?></b></td>
</tr>
<tr>
<td style="width: 25%"><b>1. <?=$lang['pm_13'];?>:</b></td>
<td><input name="pm_to" value="<?=$pm_to;?>" maxlength="30" /></td>
</tr>
<tr>
<td><b>2. <?=$lang['topic'];?>:</b></td>
<td><input name="pm_th" value="<?=$pm_th;?>" size="20" maxlength="40" /></td>
</tr>
<tr>
<td><b>3. <?=$lang['opt'];?>:</b></td>
<td>
<input type="checkbox" name="pm_s" onclick="setCookie('<?=PRE;?>pm_s',1,(this.checked)?3000:-3000)"<?php if($pm_copy){ ?> checked="checked"<?php } ?> /> <?=$lang['pm_17'];?><br />
<input type="checkbox" name="pm_bbc"<?php if($pm_bbc){ ?> checked="checked"<?php } ?> /> <?=$lang['pm_19'];?>
</td>
</tr>
<tr>
<th colspan="2"><b><?=$lang['text'];?></b></th>
</tr>
<tr>
<td colspan="2" align="center" id="pmbox">
<textarea rows="15" id="pm_txt" name="pm_txt" style="width: 95%" cols="50"><?=$pm_txt;?></textarea>
</td>
</tr>
<tr>
<td colspan="2" class="eth">
<input type="submit" value="<?=$lang['preview'];?>" />
<input type="submit" value="OK" name="save" />
</td>
</tr>
</tbody>
</table>
</form>

<?php if($bbcode){ ?>

<script type="text/javascript">
var pme=new Editor("pm_txt");
pme.bbcode=1;
pme.Emots()
</script>

<?php }else{?>

<script type="text/javascript">
document.forms['pm'].items['pm_bbc'].disabled=true
</script>

<?php } ?>