<?php
if(iCMS!=1) exit;
global $lang,$cfg,$nlang,$nstyl;
echo '<table cellspacing="0" cellpadding="2" style="width: 100%" align="center">
<tbody>
<tr>
 <td>'.$lang['skin'].':</td>
 <td><select name="ch_s" onchange="setCookie(\''.$cfg['c'].'tstyle\',this.options[this.selectedIndex].value,7200)">'.sListBox('style',1,$nstyl).'</select></td>
</tr>
<tr>
 <td>'.$lang['lang'].':</td>
 <td><select name="ch_l" onchange="setCookie(\''.$cfg['c'].'tlang\',this.options[this.selectedIndex].value,7200)" style="text-transform: uppercase">'.sListBox('lang',1,$nlang).'</select></td>
</tr>
<tr>
 <td colspan="2" align="center"><input type="button" value="OK" onclick="location.reload()" /></td>
</tr>
</tbody>
</table>'; ?>
