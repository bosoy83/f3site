<?= cTable($file['name'],2); ?>
<tr class="txt">
 <td style="width: 30%"><b>1. <?= $lang['name'] ?>:</b></td>
 <td><?= $file['name'] ?></td>
</tr>
<tr class="txt">
 <td><b>2. <?= $lang['desc'] ?></b></td>
 <td><?= $file['dsc'] ?></td>
</tr>
<tr class="txt">
 <td><b>3. <?= $lang['size'] ?>:</b></td>
 <td><?= $file['size'] ?></td>
</tr>
<tr class="txt">
 <td><b>4. <?= $lang['added'] ?>:</b></td>
 <td><?= $adate ?></td>
</tr>
<tr class="txt">
 <td><b>5. <?= $lang['author'] ?>:</b></td>
 <td><?= Autor($file['author']) ?></td>
</tr>
<!--Ocena-->
<?php if($cfg['frate']==1) { ?>
<tr class="txt">
 <td><b>6. <?= $lang['rate'] ?>:</b></td>
 <td><?= $xrates ?></td>
</tr>
<?php }
if($cfg['fcdl']==1) { ?>
<!--Pobrania-->
<tr class="txt">
 <td><b><?= (($cfg['frate']==1)?'7. ':'6. ').$lang['numofd'] ?>:</b></td>
 <td><?= $file['dls'].' '.$lang['razy'] ?></td>
</tr>
<?php } ?>
<!--D³u¿szy opis-->
<tr>
 <td colspan="2" class="txt">
  <center>
   <input type="button" style="margin-bottom: 3px" value="<?= $lang['dl'] ?>" onclick="location='<?= $xfurl ?>'" />
  </center>
  <?= nl2br($file['fulld']) ?>
 </td>
</tr>
<?= eTable(); ?>
