<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
 <td class="th"><b><?= $date.' - '.$art['name'] ?></b></td>
</tr>
<tr>
 <td class="txt">
 <?= $art['text'] ?>
 </td>
</tr>
<tr>
 <td class="eth" style="font-weight: normal; white-space: pre"><?=
 $lang['rate'].': '.$rate.'   '.
 $lang['disps'].': '.$disp.'    '.
 $lang['wrote'].': '.$wrote
 ?>
 </td>
</tr>
</tbody></table>