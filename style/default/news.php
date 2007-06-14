<?php
function StNews() {  } #Przed nowo¶ciami
function EndNews() {  } #Po...
function NewNews() {
 global $xauth,$xnews,$xdate,$xntext,$xnlink;
 cTable($xdate.' - '.$xnews['name'],2); #1 nowo¶æ
?>
<tr>
 <td colspan="2" class="txt"><?= $xntext ?></td>
</tr>
<tr class="eth">
 <td style="font-weight: normal; padding: 1px"><?= $xauth ?></td>
 <td style="font-weight: normal; padding: 1px"><?= $xnlink ?></td>
</tr>

<?php eTable(); } ?>
