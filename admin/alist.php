<?php
if(iCMSa!='X159E') exit;
if(isset($_GET['co'])) { $co=$_GET['co']; } else { $co=1; }
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

Info('<center>'.$xinfo.'<br /><br /><a href="javascript:Se()">'.$lang['search'].'</a> | <a href="?a=e'.$xco.'">'.$xadd.'</a> | <a href="?a=cats&amp;co='.$co.'">'.$lang['cats'].': '.$xtype.'</a></center>');

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
$find=(($_GET['find'] && strlen($_GET['find'])<30)? db_esc(TestForm($_GET['find'],1,1,0)) : '');

echo '<script type="text/javascript">
<!--
function Del(ex)
{
 a=confirm("'.$lang['ap_delc'].'");
 if(a) location="?x=del&co='.$xco.'&id="+ex;
}
function Se()
{
 a=prompt("'.$lang['searp'].'");
 if(a) location="?a=list&co='.$co.'&find="+a;
}
-->
</script>
<form action="?a=list&amp;co='.$co.'&amp;find='.$_GET['find'].(($page!=1)?'&amp;page='.$page:'').'" method="post">';

#Masowe zmiany?
if($_POST && count($_POST['chk'])>0)
{
 $ids=GetIDs($_POST['chk']);
 if($_POST['xu_d'])
 {
  if(ChPrv('DEL'))
	{
	 db_q('DELETE FROM {pre}'.$xco.'s WHERE ID='.join(' || ID=',$ids));
	 db_q('DELETE FROM {pre}comms WHERE th="'.$co.'_'.join(' OR th='.$co.'_',$ids).'"');
	 if($co==1||$co==5) db_q('DELETE FROM {pre}'.(($co==1)?'artstxt':'fnews').' WHERE ID='.join(' || ID=',$ids));
	}
 }
 else
 {
  $_q=Array();
  if($_POST['xu_c']!='N') $_q[]='cat='.db_esc($_POST['xu_c']);
  if($_POST['xu_a']!='N') $_q[]='access='.db_esc($_POST['xu_a']);
	if(count($_q)>0) db_q('UPDATE '.$db_pre.$xco.'s SET '.join(', ',$_q).' WHERE ID='.join(' || ID=',$ids));
 }
}

#Iloœæ
$countu=db_count('*',$xco.'s',' WHERE access!=3'.(($_GET['id'])?' && cat='.$_GET['id']:'').(($find)?' && name LIKE "%'.$find.'%"':''));

cTable($xtype,5);
echo '<tr><th>'.$lang['name'].'</th><th style="width: 50px">ID</th><th style="width: 50px">'.$lang['ap_ison'].'</th><th>'.$lang['opt'].'</th><th>&nbsp;</th></tr>';

#SQL
db_read('ID,name,access',$xco.'s','item','ta',(($_GET['id'])?' WHERE cat='.$_GET['id']:'').(($find)?(($_GET['id'])?' && ':' WHERE ').'name LIKE "%'.$find.'%"':'').' ORDER BY ID DESC LIMIT '.$st.',20');

#Lista
$ile=count($item);
for($i=0;$i<$ile;$i++)
{
 echo '<tr align="center">
 <td align="left">'.(($cfg['num']==1)?($i+1+$st).'. ':'').(($_GET['co']!=4)?'<a href="?co='.$xco.'&amp;id='.$item[$i]['ID'].'">'.$item[$i]['name'].'</a>':$item[$i]['name']).'</td>
 <td>'.$item[$i]['ID'].'</td>
 <td>'; switch($item[$i]['access']) { case 1: echo $lang['yes']; break; case 2: echo $lang['no']; break; default: echo $item[$i]['access']; } echo '</td>
 <td><a href="?a=e'.$xco.'&amp;id='.$item[$i]['ID'].'">'.$lang['edit'].'</a>'.((ChPrv('DEL'))?' &middot; <a href="javascript:Del('.$item[$i]['ID'].')">'.$lang['del'].'</a>':'').'</td>
 <td style="width: 25px"><input type="checkbox" name="chk['.$item[$i]['ID'].']" /></td>
 </tr>';
}
echo '
<tr class="eth">
 <td><a href="javascript:Show(\'mo\')" style="font-weight: normal">'.$lang['chopt'].' &raquo;</a></td>
 <td colspan="4">'.$lang['page'].': '.Pages($page,$countu,20,'adm.php?a=list&amp;co='.$co.(($find)?'&amp;find='.$_GET['find']:''),1).'</td>
</tr>
';
eTable();

#Masowe zmiany
echo '<div id="mo" style="display: none">';
db_read('ID,name','cats','cat','tn',' WHERE type='.$co.' ORDER BY name');
$ile=count($cat);
cTable($lang['chopt'],2);
echo '<tr>
 <td style="width: 30%"><b>1. '.$lang['cat'].':</b></td>
 <td><select name="xu_c"><option value="N">'.$lang['nochg'].'</option>'; for($i=0;$i<$ile;$i++) { echo '<option value="'.$cat[$i][0].'">'.$cat[$i][1].'</option>'; } echo '<option value="0">'.$lang['lack'].'</option></select></td>
</tr>
<tr>
 <td><b>2. '.$lang['ap_acc'].':</b></td>
 <td><select name="xu_a"><option value="N">'.$lang['nochg'].'</option><option value="1">'.$lang['ap_isaon'].'</option><option value="2">'.$lang['ap_isaoff'].'</option></select></td>
</tr>
<tr>
 <td><b>3. '.$lang['opt'].':</b></td>
 <td><input type="checkbox" name="xu_d" id="xu_d" /> '.$lang['chdel'].'</td>
</tr>
<tr>
 <td class="eth" colspan="2"><input type="submit" value="OK" /></td>
</tr>';
eTable();
unset($cat);
echo '</div></form><script type="text/javascript">d("xu_d").checked=0</script>';
?>
