<?php
if(iCMSa!='X159E' || !ChPrv('U')) exit;
require($catl.'adm_o.php');
require($catl.'profile.php');
if(ChPrv('DEL')) { $del=1; } else { $del=2; }
?>
<script type="text/javascript">
<!--
function Del(id)
{
	if(confirm("<?= $lang['ap_delc'] ?>"))
	{
		if(confirm("<?= $lang['ap_userdel2'] ?>")) c=1; else c=2;
		del=new Request("adm.php?x=del&id="+co+"&all="+c,'i'+id);
		del.method='POST';
		del.add('co','user');
		del.run();
 }
}
-->
</script>
<?php
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

#Zapytania
$w='';
if($_GET['gid']) $w.=' WHERE gid='.$_GET['gid'];
if($_GET['s']) $w.=(($w=='')?' WHERE ':' AND ').'login LIKE "%'.db_esc(TestForm($_GET['s'],1,1,0)).'%"';

db_read('ID,login,lv','users','xuser','tn',$w.' ORDER BY lv DESC, ID DESC LIMIT '.$st.',30');
$countu=db_count('*','users',$w);
$ile=count($xuser);

cTable($lang['ap_users'],4);
echo '
<tr>
 <th>'.$lang['login'].'</th>
 <th style="width: 50px">ID</th>
 <th>'.$lang['rights'].'</th>
 <th>'.$lang['opt'].'</th>
</tr>';

for($i=0,$ii=1+$st;$i<$ile;++$i,++$ii)
{
	$xid=$xuser[$i][0];
	echo '
 <tr>
  <td id="i'.$xid.'">'.(($cfg['num']==1)?$ii.'. ':'').'<a href="index.php?co=user&amp;id='.$xid.'">'.$xuser[$i][1].'</a></td>
  <td align="center">'.$xid.'</td>
  <td align="center">';
	#Kim jest
	switch($xuser[$i][2])
	{
		case 1: echo $lang['user']; break;
		case 2: echo $lang['admin']; break;
		case 3: echo $lang['owner']; break;
		case 4: echo $lang['editor']; break;
		case 5: echo $lang['locker']; break;
		default: echo 'ERR!';
	}
	echo '</td>
  <td align="center"><a href="?a=edituser&amp;id='.$xid.'">'.$lang['profile'].'</a> &middot; <a href="?a=editadm&amp;id='.$xid.'">'.$lang['privs'].'</a>'.(($del==1)?' &middot; <a href="javascript:Del('.$xid.')">'.$lang['del'].'</a>':'').'</td>
 </tr>';
}
echo '
<tr class="eth">
	<td colspan="2">
		<form action="adm.php" method="get" style="margin: 0">'.$lang['search'].':
		<input name="s" value="'.$_GET['s'].'" style="height: 14px" />
		<input type="hidden" value="users" name="a" />
		</form>
	</td>
	<td colspan="2">'.$lang['page'].': '.Pages($page,$countu,30,'adm.php?a=users&amp;s='.$_GET['s'].(($_GET['gid'])?'&amp;gid='.$_GET['gid']:''),1).'</td>
</tr>';
eTable();
?>
