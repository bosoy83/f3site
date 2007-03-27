<?php if(iCMS!='E123') exit;
#Strona
if($_GET['page'] && $_GET['page']!=1)
{
 $page=$_GET['page'];
 $st=($page-1)*30;
}
else
{
 $page=1;
 $st=0;
}
#Szukanie
$x='';
$y=Array();
if($cfg['ufind']==1)
{
 if($_GET['sl']) { $y[]='login LIKE "%'.db_esc(TestForm($_GET['sl'],1,1,0)).'%"'; $x.='&amp;sl='.$_GET['sl']; }
 if($_GET['pl']) { $y[]='city LIKE "%'.db_esc(TestForm($_GET['pl'],1,1,0)).'%"'; $x.='&amp;pl='.$_GET['pl']; }
 if($_GET['id']) { $id=$_GET['id']; $y[]='gid='.$id; $x.='&amp;id='.$id; }
 if($_GET['www']) { $y[]='www LIKE "%'.db_esc(TestForm($_GET['www'],1,1,0)).'%"'; $x.='&amp;www='.$_GET['www']; }
}
#Odczyt
db_read('ID,login,lv,regt','users','xuser','ta',(($x=='')?'':' WHERE '.join(' && ',$y)).' ORDER BY '.(($_GET['sort']==1)?'login':'ID DESC').' LIMIT '.$st.',30');
$countu=db_count('*','users',(($x=='')?'':' WHERE '.join(' && ',$y)));
$ile=count($xuser);
require($catl.'profile.php');
if($ile>0)
{
 cTable($lang['users'].' ('.$countu.')',4);
 echo '
 <tr>
  <th><a href="?co=users&amp;sort=1'.$x.'">'.$lang['login'].'</a></th>
  <th><a href="?co=users'.$x.'">'.$lang['joined'].'</a></th>
  <th>'.$lang['rights'].'</th>
 </tr>
 ';
 for($i=0;$i<$ile;$i++) {
  echo '
  <tr>
   <td>'.(($cfg['num']==1)?($i+1+$st).'. ':'').'<a href="?co=user&amp;id='.$xuser[$i]['ID'].'">'.$xuser[$i]['login'].'</a></td>
   <td align="center">'.genDate($xuser[$i]['regt']).'</td>
   <td align="center">'; switch($xuser[$i]['lv']) { case 1: echo $lang['user']; break; case 2: echo $lang['admin']; break; case 3: echo $lang['owner']; break; case 4: echo $lang['editor']; break; case 5: echo $lang['locker']; break; default: echo 'ERR!'; } echo '</td>
  </tr>';
 }
 echo '
 <tr class="eth">
  <td style="font-weight: normal">'.(($cfg['ufind']==1)?'<a href="javascript:d(\'sf\').style.display=\'block\'; void(0)">'.$lang['search'].' &raquo;</a>':'').'</td>
  <td colspan="2">'.$lang['page'].': '.Pages($page,$countu,30,'?co=users'.$x.(($_GET['sort'])?'&amp;sort=1':''),1).'</td>
 </tr>';
 eTable();
 #Szukaj
 if($cfg['ufind']==1)
 {
 echo '<form method="get" id="sf" action="index.php" style="display: none">';
 cTable($lang['search'],1);
 echo '<tr>
 <td>
  <table style="width: 100%" align="center"><tbody>
  <tr>
   <td>'.$lang['login'].$lang['cont'].':<br /><input name="sl" maxlength="40" value="'.$_GET['sl'].'" /></td>
   <td>'.$lang['ufrom'].'<br /><input name="pl" maxlength="50" value="'.$_GET['pl'].'" /></td>
  </tr>
  <tr>
   <td>'.$lang['wwwp'].':<br /><input name="l" maxlength="40" value="'.$_GET['l'].'" /></td>
   <td><br /><input type="submit" value="OK" /></td>
  </tr>
 </tbody></table></td>
 </tr>';
 eTable();
 echo (($id)?'<input type="hidden" name="id" value="'.$id.'" />':'').'<input type="hidden" name="co" value="users" /></form>';
 }
}
else
{
 Info($lang['nousers']);
}
unset($y,$x,$xuser);
