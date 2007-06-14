<?php

#Ikony
define('CATIMG','img/icon/folder.gif');
define('ARTIMG','img/icon/art.png');
define('HARTIMG','img/icon/hart.png');
define('LINKIMG','img/icon/link.png');
define('HLINKIMG','img/icon/hlink.png');
define('HIMGFILE','img/icon/file.png');
define('IMGFILE','img/icon/file.png');
define('SCIMG','img/icon/fldm.gif');

#Nowa tabela
function cTable($t,$cs) {
?>
<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr class="th">
 <td style="padding: 1px" colspan="<?= $cs.'"><b>'.$t ?></b></td>
</tr>

<?php }

#Koniec tabeli
function eTable() {
?>
</tbody>
</table>

<?php }

#Informacja
function Info($co)
{
 global $lang;
 cTable($lang['info'],1);
 echo '<tr><td style="padding: 10px" class="txt">'.$co.'</td></tr>';
 eTable();
}

#Pocz±tek menu
function mnew($title,$bg) {
?>
<div class="mnag">
 <b><?= $title ?></b>
</div>
<div class="menu"<?= $bg ?>>
 
<?php }

#Koniec menu
function mend() { echo '</div>'; }
?>