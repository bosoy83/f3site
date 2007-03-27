<?php cTable($img['name'],1); ?>
<tr>
 <td style="padding: 7px">
  <center><?= $ximg ?></center><br />
  <div style="line-height: 20px">
   <div style="float: left; width: 50%"><b><?= $lang['added'] ?>:</b> <?= $xdate ?></div>
   <div style="float: right; width: 50%"><b><?= $lang['author'] ?>:</b> <?= Autor($img['author']) ?></div>
   <b><?= $lang['rate'] ?>:</b> <?= $xrates ?>
  </div>
  <?= $img['dsc'] ?>

<?php eTable(); ?>
