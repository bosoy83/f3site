<table cellspacing="0" cellpadding="2" class="tb1" style="width: 770px" align="center">
<tbody class="bg" style="background-image: url(style/newf3/img/12.png)">
<tr>
 <td>
  <table cellspacing="0" cellpadding="0" width="100%"><tbody>
   <tr>
    <td style="width: 1px"><img src="style/newf3/img/bnr.png" alt="Logo" /></td>
    <td align="center"><?= Banners(1) ?></td>
   </tr>
  </tbody></table>
 </td>
</tr>
<tr>
 <td align="right">
  <table cellspacing="0" cellpadding="0" width="100%"><tbody>
   <tr>
    <td class="gm">&nbsp;<?= $cfg['gmenu'] ?></td>
    <td align="right"><?= $date ?>&nbsp;</td>
   </tr>
  </tbody></table>
 </td>
</tr>
</tbody>
</table>
<span style="font-size: 5px"><br /></span>
<table cellpadding="0" cellspacing="0" style="background: none; width: 770px" align="center">
<tbody valign="top">
<tr>
 <td class="g" width="20%"><?php newnav(1); ?></td>
 <td class="g" style="width: 10px"></td>
 <td class="g"><?php include('d.php'); ?></td>
 <td class="g" style="width: 10px"></td>
 <td class="g" width="20%"><?php newnav(2); ?></td>
</tr>
</tbody>
</table>
<!--Stopka-->
<div style="padding: 3px" align="center"><?= $cfg['footer'] ?></div>
<div align="center" style="font-size: 10px; margin-top: 5px">Powered by <a href="http://compmaster.prv.pl">F3Site</a>. SQL: <?= $sqlc ?></div>
