<table class="all" cellspacing="1" align="center">
<tbody style="background-color: #E8E8E6">
<tr>
 <td colspan="2" id="header">
  <img src="style/continuous/img/bnr.png" alt="Logo" />
 </td>
</tr>
<tr>
 <td id="gm" colspan="2">
  <div style="float: left"><?= $adgmenu ?></div>
  <div style="float: right"><?= $date ?></div>
 </td>
</tr>
<tr>
 <td style="width: 20%; padding: 9px" valign="top"><?php require($catl.'admmenu.php'); ?></td>
 <td style="background-color: #EDEDEB; padding: 9px" valign="top"><?php include($amod); ?></td>
</tr>
<tr>
 <td colspan="3" align="center" style="background-color: #DCDCD6"><?= $cfg['footer'] ?></td>
</tr>
</tbody>
</table>
