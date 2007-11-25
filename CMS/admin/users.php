<?php
if(iCMSa!=1 || !Admit('U')) exit;
require($catl.'adm_o.php');
require($catl.'profile.php');

#Prawa
$del=Admit('DEL')?1:0;
$rig=Admit('AD')?1:0;

if($del) {
?>
<script type="text/javascript">
<!--
function Del(id)
{
	if(confirm("<?= $lang['ap_delc'] ?>"))
	{
		if(confirm("<?= $lang['ap_userdel2'] ?>\n"+'i'+id.innerHTML)) c=1; else c=2;
		del=new Request("adm.php?x=del&id="+id+"&all="+c,'i'+id,'test');
		del.loadText='abc';
		del.method='POST'
		del.add('co','user')
		del.run();
 }
}
-->
</script>
<?php
}

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
{
 $page=$_GET['page'];
 $st=($page-1)*30;
}
else
{
 $page=1;
 $st=0;
}

#Grupa
$w='';
if(isset($_GET['gid']))
{
	$gid=(int)$_GET['gid'];
	$w.=' WHERE gid='.$_GET['gid'];
}
else { $gid=null; }

#Szukaj
if(isset($_GET['s']))
{
	$s=str_replace(array('"','\'','%'),'',Clean($_GET['s'],30));
	$w.=(($w=='')?' WHERE ':' AND ').'login LIKE "%'.$s.'%"';
}
else { $s=''; }

#Wszystkich
$total=db_count('ID','users',$w);

#Pobierz
$res=$db->query('SELECT ID,login,lv FROM '.PRE.'users'.$w.' ORDER BY lv DESC, ID DESC LIMIT '.$st.',30');
$res->setFetchMode(3); //NUM

OpenBox($lang['ap_users'],4);
echo '
<tr>
	<th>'.$lang['login'].'</th>
	<th style="width: 50px">ID</th>
	<th>'.$lang['rights'].'</th>
	<th>'.$lang['opt'].'</th>
</tr>';

$ile=0;
foreach($res as $user)
{
	echo '
<tr>
	<td id="i'.$user[0].'">'.++$ile.'. <a href="index.php?co=user&amp;id='.$user[0].'">'.$user[1].'</a></td>
	<td align="center">'.$user[0].'</td>
	<td align="center">';
	#Kim jest
	switch($user[2])
	{
		case 0: echo $lang['locked']; break;
		case 1: echo $lang['user']; break;
		case 2: echo $lang['editor']; break;
		case 3: echo $lang['admin']; break;
		case 4: echo $lang['owner']; break;
		default: echo 'ERR!';
	}
	echo '</td>
	<td align="center">'.
		(($user[2]==4 || $user[0]==UID)?'':'
			<a href="?a=edituser&amp;id='.$user[0].'">'.$lang['profile'].'</a>'.
			(($rig)?' &middot; <a href="?a=editadm&amp;id='.$user[0].'">'.$lang['privs'].'</a>':'').
			(($del)?' &middot; <a href="javascript:Del('.$user[0].')">'.$lang['del'].'</a>':'')).'
	</td>
</tr>';
}
echo '
<tr class="eth">
	<td colspan="2">
		<form action="adm.php" method="get" style="margin: 0">'.$lang['search'].':
		<input name="s" value="'.$s.'" style="height: 14px" />
		<input type="hidden" value="users" name="a" />
		</form>
	</td>
	<td colspan="2">'.$lang['page'].': '.Pages($page,$total,30,'adm.php?a=users&amp;s='.$s.(($gid)?'&amp;gid='.$gid:''),1).'</td>
</tr>';
CloseBox();
?>
