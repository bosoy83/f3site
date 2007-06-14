<?php cTable($xuser['login'],2) ?>
<tr>
 <td><b><?= $lang['joined'] ?>:</b></td>
 <td class="txt"><?= $xdate ?></td>
</tr>
<tr>
 <td><b><?= $lang['lastv'] ?>:</b></td>
 <td class="txt"><?= $xdate2 ?></td>
</tr>
<tr>
 <td style="width: 30%"><b><?= $lang['ufrom'] ?></b></td>
 <td class="txt"><?= $ustat['fr'] ?></td>
</tr>
<tr>
 <td><b><?= $lang['wwwp'] ?>:</b></td>
 <td class="txt"><?= $ustat['www'] ?></td>
</tr>
<tr>
 <td><b><?= $lang['mail'] ?>:</b></td>
 <td class="txt"><?= $ustat['m'] ?></td>
</tr>
<tr>
 <td><b>Gadu-Gadu:</b></td>
 <td class="txt"><?= $ustat['gg'] ?></td>
</tr>
<tr>
 <td><b>Tlen.pl:</b></td>
 <td class="txt"><?= $ustat['t'] ?></td>
</tr>
<tr>
 <td><b>ICQ:</b></td>
 <td class="txt"><?= $ustat['icq'] ?></td>
</tr>
<tr>
 <td><b>Skype:</b></td>
 <td class="txt"><?= $ustat['s'] ?></td>
</tr>
<tr>
 <td style="text-transform: capitalize"><b><?= $lang['opt'] ?>:</b></td>
 <td class="txt" style="padding: 10px"><?= $ustat['o'] ?></td>
</tr>

<?php eTable();
if(!empty($xuser['about'])) {
 cTable($lang['abouty'],1);
 echo '<tr><td class="txt">'.$xuser['about'].'</td></tr>';
 eTable();
} ?>
