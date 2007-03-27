<?php
if(iCMS!='E123') exit;
global $lang,$nlang,$nstyl;
echo '<form action="index.php">
<table cellspacing="0" cellpadding="2" style="width: 100%" align="center">
<tbody>
<tr>
 <td>'.$lang['skin'].':</td>
 <td><select name="ch_s">'.sListBox('style',1,$nstyl).'</select></td>
</tr>
<tr>
 <td>'.$lang['lang'].':</td>
 <td><select name="ch_l" style="text-transform: uppercase">'.sListBox('lang',1,$nlang).'</select></td>
</tr>
<tr>
 <td colspan="2" align="center"><input type="submit" value="OK" /><input type="hidden" name="mode" value="sets" /></td>
</tr>
</tbody>
</table>
</form>'; ?>
