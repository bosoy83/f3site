<div align="center">
<div align="center" style="width: 300px">
 <form action="login.php" method="post">
 <h1>F3Site &rarr; admin</h1>
 <?php
 OpenBox($lang['user'],2);
 ?>
 <tr>
  <td style="width: 30%"><b>1. <?= $lang['login'] ?>:</b></td>
  <td><input name="snduser" /></td>
 </tr>
 <tr>
  <td><b>2. <?= $lang['pass'] ?>:</b></td>
  <td><input type="password" name="sndpass" /></td>
 </tr>
 <tr class="eth">
  <td><input name="fromadm" type="hidden" value="1" /><input type="submit" value="OK" /></td>
  <td><input type="checkbox" name="sndr" value="1" /> <?= $lang['remlog'] ?></td>
 </tr>
 <?php
 CloseBox();
 ?>
 </form>
</div></div>
