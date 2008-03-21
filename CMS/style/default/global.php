<?php /* Kod PHP skórki - dla zaawansowanych */

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
function OpenBox($t,$cs=1) {
?>
<table cellpadding="4" cellspacing="1" class="tb">
<tbody class="bg">
<tr class="h">
 <td style="padding: 1px" colspan="<?= $cs.'"><b>'.$t ?></b></td>
</tr>

<?php }

#Koniec tabeli
function CloseBox() {
?>
</tbody>
</table>

<?php }

#Informacja
function Info($co)
{
	global $lang;
	OpenBox($lang['info'],1);
	echo '<tr><td style="padding: 10px" class="txt">'.$co.'</td></tr>';
	CloseBox();
}

#Pocz±tek menu
function mnew($title,$bg='') {
?>
<div class="mh">
 <b><?= $title ?></b>
</div>
<div class="menu"<?= $bg ?>>
 
<?php }

#Koniec menu
function mend() { echo '</div>'; }
?>
