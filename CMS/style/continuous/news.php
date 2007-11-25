<?php function News() { global $xdate,$news,$wrote,$more,$c,$edit; ?>

<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
 <td class="th"><b><?= $xdate.' - '.$news['name'] ?></b></td>
</tr>
<tr>
 <td class="txt"><?= $news['txt'].' '.$more ?></td>
</tr>
<tr>
 <td class="eth" style="font-weight: normal; padding: 1px; white-space: pre"><?=
 $wrote.'    '.$edit.'    '.$c ?>
 </td>
</tr>
</tbody>
</table>

<?php } ?>