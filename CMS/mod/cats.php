<?php
if(iCMS!=1) exit;
$id=isset($_GET['id'])?$_GET['id']:5;

#Odczyt
$res=$db->query('SELECT ID,name,dsc,nums FROM '.PRE.'cats WHERE sc=0
	&& type='.$id.' && (access=1 || access="'.$nlang.'") ORDER BY lft');
$res->setFetchMode(3); //NUM

OpenBox($lang['cats'],2);

$ile=0;
foreach($res as $cat)
{
	echo '
	<tr>
		<td style="width: 40px" align="center"><img src="'.CATIMG.'" alt="CAT" /></td>
		<td><a class="listlink" href="?d='.$cat[0].'">'.$cat[1].'</a> ('.$cat[3].')<br /><small>'.$cat[2].'</small></td>
  </tr>';
	++$ile;
}

#Brak?
if(!$ile) echo '<tr><td class="txt">'.$lang['nocats'].'</td></tr>';

CloseBox();
$res=null;
unset($cat,$ile,$id);
?>
