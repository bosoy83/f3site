<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
 <td colspan="2" class="th"><b><?= $xuser['login'] ?></b></td>
</tr>
<tr>
 <td><b><?= $lang['joined'] ?>:</b></td>
 <td><?= $xdate ?></td>
</tr>
<tr>
 <td><b><?= $lang['lastv'] ?>:</b></td>
 <td><?= $xdate2 ?></td>
</tr>
<tr>
 <td style="width: 30%"><b><?= $lang['ufrom'] ?></b></td>
 <td><?= $ustat['fr'] ?></td>
</tr>
<tr>
 <td><b><?= $lang['wwwp'] ?>:</b></td>
 <td><?= $ustat['www'] ?></td>
</tr>
<tr>
 <td><b><?= $lang['mail'] ?>:</b></td>
 <td><?= $ustat['m'] ?></td>
</tr>
<tr>
 <td><b>Gadu-Gadu:</b></td>
 <td><?= $ustat['gg'] ?></td>
</tr>
<tr>
 <td><b>Tlen.pl:</b></td>
 <td><?= $ustat['t'] ?></td>
</tr>
<tr>
 <td><b>ICQ:</b></td>
 <td><?= $ustat['icq'] ?></td>
</tr>
<tr>
 <td><b>Skype:</b></td>
 <td><?= $ustat['s'] ?></td>
</tr>
<tr>
 <td><b><?= $lang['opt'] ?>:</b></td>
 <td style="padding: 10px"><?= $ustat['o'] ?></td>
</tr>
</tbody>
</table>

<?php
if(!empty($xuser['about']))
{
	OpenBox($lang['abouty'],1);
	echo '<tr><td class="txt">'.$xuser['about'].'</td></tr>';
	CloseBox();
} ?>
