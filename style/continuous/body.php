<table class="all" cellspacing="1" align="center">
<tbody style="background-color: #E8E8E6">
<tr>
 <td colspan="3" id="header">
  <table><tbody>
  <tr>
   <td><img src="style/continuous/img/bnr.png" alt="Logo" /></td>
   <td><?= Banners(1) ?></td>
  </tr>
  </tbody></table>
 </td>
</tr>
<tr>
 <td id="gm" colspan="3">
  <div style="float: left"><?= $cfg['gmenu'] ?></div>
  <div style="float: right"><?= $date ?></div>
 </td>
</tr>
<tr>
 <td class="nav" valign="top"><?php newnav(1); ?></td>
 <td class="mid" valign="top"><?php include(MOD); ?></td>
 <td class="nav" valign="top"><?php newnav(2); ?></td>
</tr>
</tbody>
</table>
<center>
 <div id="footer"><?= $cfg['footer'] ?></div>
 <div style="font-size: 10px">Powered by <a href="http://compmaster.prv.pl">F3Site</a>. SQL: <?= $sqlc ?></div>
</center>
