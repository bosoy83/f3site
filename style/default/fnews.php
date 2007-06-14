<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr>
 <td class="th"><b><?= $xdate.' - '.$news['name'] ?></b></td>
</tr>
<tr>
 <td class="txt">
  <?= $text ?>
	<br /><br />
  <div align="right"><?= $lang['wrote'].': '.Autor($news['author']) ?></div>
 </td>
</tr>
</tbody>
</table>