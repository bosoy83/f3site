<?php
function StNews() {  } #Przed nowo¶ciami
function EndNews() {  } #Po...
function NewNews() {
 global $xauth,$xnews,$xdate,$xntext,$xnlink;
 nTable($xdate.' - '.$xnews['name'],2); #1 nowo¶æ
?>
<tr>
 <td style="border" colspan="2" class="txt"><?= $xntext ?></td>
</tr>
<tr class="eth1">
 <td style="font-weight: normal; padding: 1px; margin: -1px"><?= $xauth ?></td>
 <td style="font-weight: normal; padding: 1px"><?= $xnlink ?></td>
</tr>

<?php eTable(); } ?>
