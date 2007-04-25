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
<table cellpadding="3" cellspacing="1" class="tb">
<tbody class="bg">
<tr class="th">
 <td style="padding: 1px" colspan="<?= $cs.'">'.$t ?></td>
</tr>

<?php }
#Koniec tabeli
function eTable() {
?>
</tbody>
</table>

<?php }
#Informacja
function Info($co) {
 global $lang;
 cTable($lang['info'],1);
 echo '<tr><td style="padding: 10px" class="txt">'.$co.'</td></tr>';
 eTable();
}

#Link menu
function mlink($txt,$url,$t)
{
  echo '&middot; <a href="'.$url.'"'.(($t==1)?' target="_blank"':'').'>'.$txt.'</a><br />';
}

#MENU
function mnew($title,$bg)
{
 echo '<div class="mnag"'.$bg.'>'.$title.'</div><div class="menu">';
}

#Koniec
function mend()
{ 
echo '</div>';
} ?>