<?php cTable($art['name'],1); ?>
<tr>
 <td class="txt"><?php art(); ?></td>
</tr>
<tr class="eth">
 <td style="font-weight: normal">
  <!--Ocena-->
  <?= $xrates ?>
  &nbsp;&middot;&nbsp;
  <!--Autor-->
  <?= $lang['author'].':</b> '.Autor($art['author']) ?>
  &nbsp;&middot;&nbsp;
  <!--Wy¶wietlenia-->
  <?= $disptxt; ?>
 </td>
</tr>
<?= eTable() ?>
