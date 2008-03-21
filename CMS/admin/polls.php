<?php
if(iCMSa!=1 || !Admit('f3s')) exit;
require(LANG_DIR.'f3s.php');

#Operacje
if($_POST)
{
	if(count($_POST['chk'])>0 && Admit('DEL'))
	{
		$_q=GetIDs($_POST['chk']);
		if($_POST['delp'])
		{
			$db->exec('DELETE FROM '.PRE.'polls WHERE ID IN ('.join(',',$_q).')');
			$db->exec('DELETE FROM '.PRE.'answers WHERE IDP IN ('.join(',',$_q).')');
			$db->exec('DELETE FROM '.PRE.'comms WHERE th="12_'.join('" || th="12_',$_q).'"');
			$db->exec('DELETE FROM '.PRE.'pollvotes WHERE ID IN ('.join(',',$_q).')');
		}
		elseif($_POST['zerp'])
		{
			$db->exec('UPDATE '.PRE.'answers SET num=0 WHERE IDP IN ('.join(',',$_q).')');
			$db->exec('UPDATE '.PRE.'polls SET num=0 WHERE ID IN ('.join(',',$_q).')');
			$db->exec('DELETE FROM '.PRE.'pollvotes WHERE ID IN ('.join(',',$_q).')');
		}
		unset($_q);
	}
}

#Info
Info($lang['ap_ipoll'].'<br /><br /><center><a href="?a=editpoll">'.$lang['addpoll'].'</a></center>');

#Odczyt
$res=$db->query('SELECT ID,name,num,access FROM '.PRE.'polls ORDER BY ID DESC');
$res->setFetchMode(2); //Assoc

#Lista
echo '<form action="?a=poll" method="post">';
OpenBox($lang['polls'],5);

echo '<tr>
	<th>'.$lang['name'].'</th>
	<th style="width: 55px">'.$lang['votes'].'</th>
	<th style="width: 60px">'.$lang['lang'].'</th>
	<th>'.$lang['opt'].'</th>
	<th style="width: 30px"></th>
</tr>';

$ile=0;
foreach($res as $poll)
{
	echo '<tr>
	<td>'.++$ile.'. <a href="index.php?co=poll&amp;id='.$poll['ID'].'">'.$poll['name'].'</a></td>
  <td align="center">'.$poll['num'].'</td>
  <td align="center">'.$poll['access'].'</td>
  <td align="center"><a href="?a=editpoll&amp;id='.$poll['ID'].'">'.$lang['edit'].'</a></td>
  <td align="center"><input type="checkbox" name="chk['.$poll['ID'].']" /></td>
 </tr>';
}

#Usuwanie/zerowanie
if(Admit('DEL'))
	echo '<tr>
	<td colspan="5" class="eth">
		<input type="submit" style="display: none" />
		<input type="submit" name="delp" value="'.$lang['del'].'" />
		<input type="submit" name="zerp" value="'.$lang['zerp'].'" />
	</td>
</tr>';

$res=null;
CloseBox();
echo '</form>';
?>
