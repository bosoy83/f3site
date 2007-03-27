<?php
define('ARTIMG','img/icon/art.png');
define('HARTIMG','img/icon/hart.png');
define('LINKIMG','img/icon/link.png');
define('HLINKIMG','img/icon/hlink.png');
define('HIMGFILE','img/icon/file.png');
define('IMGFILE','img/icon/file.png');
define('SCIMG','img/icon/fldm.gif');
define('CATIMG','img/icon/folder.gif');
function cTable($t,$cs) { #Tabela
?>
<table class="tb" cellspacing="1">
<tbody class="bg">
<tr class="th">
 <td style="padding: 1px" colspan="<?= $cs.'"><b>'.$t ?></b></td>
</tr>

<?php }
function eTable() { #Koniec tabeli
?>
</tbody>
</table>

<?php }
function Info($co) {
 global $lang;
 cTable($lang['info'],1);
 echo('<tr><td style="padding: 10px" class="txt">'.$co.'</td></tr>');
 eTable();
}

#Link menu
function mlink($txt,$url,$t)
{
  echo '<span style="font-size: 11px">&raquo;</span> <a href="'.$url.'"'.(($t==1)?' target="_blank"':'').'>'.$txt.'</a><br />';
}
function mnew($title,$bg) {
?>
<div class="mnag"><b><?= $title ?></b></div>
<div class="menu"<?= $bg ?>>
 
<?php } function mend() { #KONIEC ?>
</div>
<?php } ?>
