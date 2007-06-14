<?php
define('ARTIMG',$catst.'img/art.png');
define('HARTIMG',$catst.'img/hart.png');
define('LINKIMG',$catst.'img/link.png');
define('HLINKIMG',$catst.'img/hlink.png');
define('HIMGFILE','img/icon/file.png');
define('IMGFILE','img/icon/file.png');
define('SCIMG','img/icon/fldm.gif');
define('CATIMG','img/icon/folder.gif');
function cNag($t,$cs) { #Nag. tabeli
?>
<table cellspacing="0" cellpadding="0" style="width: 100%">
<tbody>
<tr>
 <td class="th1"></td>
 <td style="padding: 1px" class="th" colspan="<?= $cs.'">'.$t ?></td>
 <td class="th2"></td>
</tr>
</tbody>
</table>

<?php
}
function nTable($t,$cs) {
 cNag($t,$cs); #Tab. tylko z zew. obr.
?>
<table class="tb1" cellspacing="0" cellpadding="2">
<tbody class="bg">
<?php
}
function cTable($t,$cs) { #Tabela
 cNag($t,$cs);
?>
<table class="tb" cellspacing="1" cellpadding="3">
<tbody class="bg">

<?php }
function eTable() { #Koniec tabeli
?>
</tbody>
</table>
<span style="font-size: 7px"><br /></span>

<?php }
function Info($co) {
 global $lang;
 nTable($lang['info'],1);
 echo('<tr><td style="padding: 10px">'.$co.'</td></tr>');
 eTable();
}

#Link menu
function mlink($txt,$url,$t)
{
  echo '&middot; <a href="'.$url.'"'.(($t==1)?' target="_blank"':'').'>'.$txt.'</a><br />';
}
function mnew($title,$bg) {
 nTable($title,1); #MENU
?>
<tr>
 <td class="menu" style="padding: 1px; background-image: <?= $bg ?>">
<?php } function mend() { #KONIEC ?>
 </td>
</tr>
</tbody>
</table>
<span style="font-size: 5px"><br /></span>
<?php } ?>
