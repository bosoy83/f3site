<?= cTable($xdate.' - '.$news['name'],1); ?>
<tr>
 <td class="txt">
  <?= $text ?><br /><br />
  <div align="right"><?= $lang['wrote'].': '.Autor($news['author']) ?></div>
 </td>
</tr>
<?= eTable(); ?>
