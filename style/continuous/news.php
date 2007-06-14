<?php function News() { global $xdate,$xnews,$wrote,$more,$c,$edit; ?>

<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
 <td class="th"><b><?= $xdate.' - '.$xnews['name'] ?></b></td>
</tr>
<tr>
 <td class="txt"><?= $xnews['txt'].' '.$more ?></td>
</tr>
<tr>
 <td class="eth" style="font-weight: normal; padding: 1px; white-space: pre"><?=
 $wrote.'    '.$edit.'    '.$c ?>
 </td>
</tr>
</tbody>
</table>

<?php } ?>