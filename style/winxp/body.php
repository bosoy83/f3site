<table cellspacing="1" style="width: 880px; background-color: #728FA7" align="center">
<tbody style="background-color: #B3C4D7">
<tr>
 <td colspan="3">
  <table cellspacing="0" cellpadding="0" width="100%"><tbody>
   <tr>
    <td style="width: 1px"><img src="style/winxp/img/bnr.png" alt="Logo" /></td>
    <td align="center"><?= Banners(1) ?></td>
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
 <td style="width: 175px; padding: 9px" valign="top"><?php newnav(1); ?></td>
 <td style="background-color: #C4D0DF; padding: 9px" valign="top"><?php include('d.php'); ?></td>
 <td style="width: 175px; padding: 9px" valign="top"><?php newnav(2); ?></td>
</tr>
</tbody>
</table>
<center><div align="center" style="background-color: #AABCCA; padding: 2px 0px 2px 0px; width: 878px; border: 1px #728FA7 solid; border-top: 0"><?= $cfg['footer'] ?></div></center>
<span style="font-size: 5px"><br /></span>
<div align="center" style="font-size: 10px">Powered by <a href="http://compmaster.prv.pl">F3Site</a>. SQL: <?= $sqlc ?></div>
