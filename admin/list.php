<?php
if(iCMSa!='X159E') exit;
if(isset($_GET['co'])) { $co=$_GET['co']; } else { $co=1; }
if(ChPrv('DEL')) { $del=1; } else { $del=2; }
require($catl.'adm_z.php');

#Typ
switch($co)
{
 case 5: if(!ChPrv('N')) { exit; } $xtype=&$lang['news']; $xadd=&$lang['addnews']; $xco='new'; $xinfo=&$lang['ap_ninfo']; break;
 case 4: if(!ChPrv('L')) { exit; } $xtype=&$lang['links']; $xadd=&$lang['addlink']; $xco='link'; $xinfo=&$lang['ap_linfo']; break;
 case 3: if(!ChPrv('G')) { exit; } $xtype=&$lang['images']; $xadd=&$lang['addimg']; $xco='img'; $xinfo=&$lang['ap_iinfo']; break;
 case 2: if(!ChPrv('F')) { exit; } $xtype=&$lang['files']; $xadd=&$lang['addfile']; $xco='file'; $xinfo=&$lang['ap_finfo']; break;
 default: if(!ChPrv('A')) { exit; } $xtype=&$lang['arts']; $xadd=&$lang['addart']; $xco='art'; $co=1; $xinfo=&$lang['ap_artinfo'];
}

Info('<center>'.$xinfo.'<br /><br /><a href="javascript:Se()">'.$lang['search'].'</a> | <a href="?a=edit'.$xco.'">'.$xadd.'</a> | <a href="?a=cats&amp;co='.$co.'">'.$lang['cats'].': '.$xtype.'</a></center>');

#Strona
if($_GET['page'] && $_GET['page']!=1)
{
	$page=$_GET['page'];
	$st=($page-1)*20;
}
else
{
	$page=1;
	$st=0;
}

#Szukaj
$find=($_GET['find'] && strlen($_GET['find'])<30)? TestForm($_GET['find'],1,1,0) : '';

#Cz�� URL
$url='?a=list&amp;co='.$co.'&amp;'.(($_GET['id'])?'id='.$id:'');
?>
<script type="text/javascript">
<!--
function Del(id)
{
 if(confirm("<?=$lang['ap_delc']?>"))
 {
	del=new Request("adm.php?x=del&id="+id,'i'+id);
	del.method='POST';
	del.add('co','<?=$xco?>')
	del.run()
 }
}
function Se()
{
 a=prompt("<?=$lang['searp']?>");
 if(a) location="?a=list&co=<?=$url?>&find="+a;
}
-->
</script>
<?php
echo '<form action="'.$url.'&amp;page='.$page.'&amp;find='.$find.'" method="post">';

#Masowe zmiany?
if($_POST && count($_POST['chk'])>0)
{
 $ids=GetIDs($_POST['chk']);
 if($_POST['xu_d'])
 {
  if(ChPrv('DEL'))
	{
	 db_q('DELETE FROM {pre}'.$xco.'s WHERE ID IN '.join(',',$ids).')');
	 db_q('DELETE FROM {pre}comms WHERE th="'.$co.'_'.join(' OR th='.$co.'_',$ids).'"');
	 if($co==1||$co==5) db_q('DELETE FROM {pre}'.(($co==1)?'artstxt':'fnews').' WHERE ID='.join(' || ID=',$ids));
	}
 }
 else
 {
  $_q=Array();
  if($_POST['xu_c']!='N') $_q[]='cat='.(int)$_POST['xu_c'];
  if($_POST['xu_a']!='N') $_q[]='access='.(int)$_POST['xu_a'];
	if(count($_q)>0) db_q('UPDATE '.PRE.$xco.'s SET '.join(', ',$_q).' WHERE ID IN ('.join(',',$ids).')');
 }
 unset($ids,$_q);
}

#Ilo��
$countu=db_count('*',$xco.'s',' WHERE access!=3'.(($_GET['id'])?' && cat='.$_GET['id']:'').(($find)?' && name LIKE "%'.db_esc($find).'%"':''));

#Nag��wek
cTable($xtype,5);
echo '<tr>
 <th>'.$lang['name'].'</th>
 <th style="width: 50px">ID</th>
 <th style="width: 50px">'.$lang['ap_ison'].'</th>
 <th>'.$lang['opt'].'</th>
 <th>&nbsp;</th>
</tr>';

#SQL
db_read('ID,name,access',$xco.'s','item','ta',(($_GET['id'])?' WHERE cat='.$_GET['id']:'').(($find)?(($_GET['id'])?' && ':' WHERE ').'name LIKE "%'.$find.'%"':'').' ORDER BY ID DESC LIMIT '.$st.',20');

#Lista
$ile=count($item);
for($i=0,$ii=1+$st;$i<$ile;++$i,++$ii)
{
 $xid=$item[$i]['ID'];
 echo '<tr align="center">
 <td align="left" id="i'.$xid.'">'.(($cfg['num']==1)?$ii.'. ':'').(($_GET['co']!=4)?'<a href="?co='.$xco.'&amp;id='.$xid.'">'.$item[$i]['name'].'</a>':$item[$i]['name']).'</td>
 <td>'.$xid.'</td>
 <td>';
 switch($item[$i]['access'])
 {
	case 1: echo $lang['yes']; break;
	case 2: echo $lang['no']; break;
	default: echo $item[$i]['access'];
 }
 echo '</td>
 <td><a href="?a=edit'.$xco.'&amp;id='.$xid.'">'.$lang['edit'].'</a>'.(($del==1)?' &middot; <a href="javascript:Del('.$xid.')">'.$lang['del'].'</a>':'').'</td>
 <td style="width: 25px"><input type="checkbox" name="chk['.$xid.']" /></td>
</tr>';
}
echo '
<tr class="eth">
 <td><a href="javascript:Show(\'mo\')">'.$lang['chopt'].' &raquo;</a></td>
 <td colspan="4"><b>'.$lang['page'].':</b> '.Pages($page,$countu,20,$url.'&amp;find='.$find,1).'</td>
</tr>';
eTable();

#Masowe zmiany
echo '<div id="mo" style="display: none">';

#Kategorie
db_read('ID,name','cats','cat','tn',' WHERE type='.$co.' ORDER BY name');
$ile=count($cat);

cTable($lang['chopt'],2);
echo '<tr>
 <td style="width: 30%"><b>1. '.$lang['cat'].':</b></td>
 <td><select name="xu_c"><option value="N">'.$lang['nochg'].'</option>';
 for($i=0;$i<$ile;++$i)
 {
	echo '<option value="'.$cat[$i][0].'">'.$cat[$i][1].'</option>';
 }
 echo '<option value="0">'.$lang['lack'].'</option></select></td>
</tr>
<tr>
 <td><b>2. '.$lang['ap_acc'].':</b></td>
 <td><select name="xu_a"><option value="N">'.$lang['nochg'].'</option><option value="1">'.$lang['ap_isaon'].'</option><option value="2">'.$lang['ap_isaoff'].'</option></select></td>
</tr>
'.(($del==1)?'
<tr>
 <td><b>3. '.$lang['opt'].':</b></td>
 <td><input type="checkbox" name="xu_d" id="xu_d" /> '.$lang['chdel'].'</td>
</tr>
<tr>
 <td class="eth" colspan="2"><input type="submit" value="OK" /></td>
</tr>':'');
eTable();
unset($cat);
?>
</div>
</form>
<script type="text/javascript">
d("xu_d").checked=0
</script>