<?php
function StNews() {  } #Przed nowo¶ciami
function EndNews() {  } #Po...
function NewNews() { #1 nowo¶æ
 global $xnews,$xdate,$xntext,$xnlink,$xauth;
 cTable($xdate.' - '.$xnews['name'],2); ?>
<tr>
 <td colspan="2" class="txt"><?= $xntext ?></td>
</tr>
<tr class="eth">
 <td style="font-weight: normal; padding: 1px"><?= $xauth ?></td>
 <td style="font-weight: normal; padding: 1px"><?= $xnlink ?></td>
</tr>

<?php eTable(); } ?>
