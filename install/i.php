<?php if(iCMS!=1) exit; ?>
<form action="index.php" method="post">
<input type="hidden" name="etap" value="1" />
<table cellspacing="1" cellpadding="5" align="center">
<tbody>
<tr>
 <td>Jêzyk / language: </td>
 <td><select name="lng">
 <?php
 $tmp='';
 $f=opendir('../lang');
 while(false!==($ff=readdir($f)))
 {
  if(is_dir('../lang/'.$ff) && $ff!='..' && $ff!='.')
	{
	 $tmp.='<option>'.$ff.'</option>';
	}
 }
 echo $tmp;
 unset($tmp,$ff,$f); ?>
 </select></td>
</tr>
<tr>
 <td colspan="2" align="center"><input type="submit" value="OK" /></td>
</tr>
</tbody>
</table>
</form>
