<?php cTable($img['name'],2); ?>
<tr>
 <td colspan="2" align="center" style="padding: 4px"><?= $ximg ?></td>
</tr>
<tr>
 <td style="width: 30%; padding: 2px"><b>1. <?= $lang['desc'] ?>:</b></td>
 <td><?= $img['dsc'] ?></td>
</tr>
<tr>
 <td style="padding: 2px"><b>2. <?= $lang['added'] ?>:</b></td>
 <td><?= $xdate ?></td>
</tr>
<tr>
 <td style="padding: 2px"><b>3. <?= $lang['author'] ?>:</b></td>
 <td><?= Autor($img['author']) ?></td>
</tr>
<!--Oceny-->
<?php if($cfg['irate']==1) { ?>
<tr>
 <td style="padding: 2px"><b>4. <?= $lang['rate'] ?>:</b></td>
 <td><?= $xrates ?></td>
</tr>

<?php }
eTable(); ?>
