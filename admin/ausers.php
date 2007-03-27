<?php
if(iCMSa!='X159E' || !ChPrv('U')) exit;
require($catl.'adm_o.php');
require($catl.'profile.php');
?>
<script type="text/javascript">
<!--
function uDel(co) {
 if(confirm("<?= $lang['ap_delc'] ?>")) {
   b=confirm("<?= $lang['ap_userdel2'] ?>");
   if(b) { c=1; } else { c=2; }
   location="adm.php?x=del&co=user&id="+co+"&all="+c;
 }
 else { void(0); }
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
</tr>
';
for($i=0;$i<$ile;$i++) {
 $ii=$i+1+$st;
 echo '
 <tr>
  <td>'.(($cfg['num']==1)?$ii.'. ':'').'<a href="index.php?co=user&amp;id='.$xuser[$i][0].'">'.$xuser[$i][1].'</a></td>
  <td align="center">'.$xuser[$i][0].'</td>
  <td align="center">'; switch($xuser[$i][2]) { case 1: echo $lang['user']; break; case 2: echo $lang['admin']; break; case 3: echo $lang['owner']; break; case 4: echo $lang['editor']; break; case 5: echo $lang['locker']; break; default: echo 'ERR!'; } echo '</td>
  <td align="center"><a href="adm.php?a=uedit&amp;id='.$xuser[$i][0].'">'.$lang['profile'].'</a> &middot; <a href="?a=eadm&amp;id='.$xuser[$i][0].'">'.strtolower($lang['privs']).'</a>'.((ChPrv('DEL'))?' &middot; <a href="javascript:uDel('.$xuser[$i][0].')">'.$lang['del'].'</a>':'').'</td>
 </tr>
 ';
}
echo '
<tr class="eth">
 <td colspan="2"><form action="adm.php" method="get" style="margin: 0">'.$lang['search'].': <input name="s" value="'.$_GET['s'].'" style="height: 14px" /><input type="hidden" value="users" name="a" /></form></td>
 <td colspan="2">'.$lang['page'].': '.Pages($page,$countu,30,'adm.php?a=users&amp;s='.$_GET['s'].(($_GET['gid'])?'&amp;gid='.$_GET['gid']:''),1).'</td>
</tr>
';
eTable();
?>
