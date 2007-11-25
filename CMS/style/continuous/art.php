<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
 <td class="th"><b><?= $date.' - '.$content['name'] ?></b></td>
</tr>
<tr>
 <td class="txt">
 <?= $content['text'] ?>
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