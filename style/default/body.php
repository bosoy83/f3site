<table class="tb" cellspacing="1" style="width: 770px" align="center">
<tbody class="bg">
<tr>
 <td class="top">
  <table cellpadding="0" width="100%"><tbody>
   <tr>
    <td style="width: 1px"><img src="style/default/img/bnr.png" alt="Logo" /></td>
    <td align="center"><?= Banners(1) ?></td>
   </tr>
  </tbody></table>
 </td>
</tr>
<tr>
 <td id="gm">
  <div style="float: left"><?= $cfg['gmenu'] ?></div>
  <div style="float: right"><?= $date ?></div>
 </td>
</tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" style="width: 770px" align="center">
<tbody valign="top">
<tr>
 <td width="155px"><?php newnav(1); ?></td>
 <td style="width: 10px"></td>
 <td><?php include(MOD); ?></td>
 <td style="width: 10px"></td>
 <td width="155px"><?php newnav(2); ?></td>
</tr>
</tbody>
</table>
<table class="tb" cellspacing="1" style="width: 770px" align="center">
<tbody class="eth">
<tr>
 <td align="center"><?= $cfg['footer'] ?></td>
</tr>
</tbody>
</table>
<div align="center" style="font-size: 10px">Powered by <a href="http://compmaster.prv.pl">F3Site</a>. SQL: <?= $sqlc ?></div>
