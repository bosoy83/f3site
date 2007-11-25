<?php OpenBox($img['name'],1); ?>
<tr>
 <td style="padding: 7px">
  <center><?= $img ?></center><br />
  <div style="line-height: 20px">
   <div style="float: left; width: 50%"><b><?= $lang['added'] ?>:</b> <?= $date ?></div>
   <div style="float: right; width: 50%"><b><?= $lang['whoadd'] ?>:</b> <?= Autor($img['author']) ?></div>
   <b><?= $lang['rate'] ?>:</b> <?= $rate ?>
  </div>
  <?= $content['dsc'] ?>

<?php CloseBox(); ?>
