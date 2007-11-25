<?php
if(iCMS!=1) exit;

#Do³±cz
/* Niepiezpieczne, kiedy akcjê wywo³ujemy zmienn± z GET
if(isset($_GET['join_now']) && $_GET['id'])
{
	if(LOGD==1 && db_count('ID','groups',' WHERE opened=1 && access!=3 && ID='.$_GET['id'])==1)
	{
		$db->exec('UPDATE '.PRE.'users SET gid='.$_GET['id'].' WHERE ID='.UID);
	}
}*/

#Lista
$res=$db->query('SELECT ID,name,dsc,opened FROM '.PRE.'groups WHERE (access=1 || access="'.$nlang.'")');
$res->setFetchMode(3); //NUM

OpenBox($lang['groups']);

foreach($res as $group)
{
	echo '<tr>
	<td class="txt">
		<b><a href="?co=users&amp;id='.$group[0].'">'.$group[1].'</a></b>
		<br />'.nl2br($group[2]).'
	</td>
</tr>';
}
CloseBox();
unset($res,$group);
?>
