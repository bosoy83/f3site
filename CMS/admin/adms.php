<?php
if(iCMSa!=1 || !Admit('AD')) exit;
require($catl.'rights.php');

#Odczyt
$res=$db->query('SELECT ID,login,adm FROM '.PRE.'users WHERE lv>1');
$res->setFetchMode(3); //NUM

#Info
Info($lang['ap_iadms']);
OpenBox($lang['admins'],3);

echo '
<tr>
	<th>'.$lang['login'].'</th>
	<th style="width: 40%">'.$lang['privs'].'</th>
	<th>'.$lang['opt'].'</th>
</tr>';

$ile=0;
foreach($res as $admin)
{
	echo '<tr>
	<td>'.++$ile.'. <a href="index.php?co=user&amp;id='.$admin[0].'">'.$admin[1].'</a></td>
	<td align="center">'.str_replace('|',' ',$admin[2]).'</td>
	<td align="center">
		'.$lang['edit'].':
		<a href="?a=editadm&amp;id='.$admin[0].'">'.$lang['privs'].'</a> &middot; 
		<a href="?a=edituser&amp;id='.$admin[0].'">'.$lang['profile'].'</a>
	</td>
</tr>';
}

$res=null;
CloseBox();
?>
