<?php /* Lista pozycji */
if(EC!=1) exit;
require($catl.'content.php');

#Akcja
$act=(int)$_GET['act'];

#Mo¿e usuwaæ i edytowaæ?
$del=Admit('DEL')?1:0;

#Typ
switch($act)
{
	case 5:
		$global='N';
		$type=$lang['news'];
		$name='new';
		break;
	case 4:
		$global='L';
		$type=$lang['links'];
		$name='link';
		break;
	case 3:
		$global='G';
		$type=$lang['images'];
		$name='img';
		break;
	case 2:
		$global='F';
		$type=$lang['files'];
		$name='file';
		break;
	case 1:
		$global='A';
		$type=$lang['arts'];
		$name='art';
		break;
	default: echo 'Undefined type of items!'; return;
}

#Parametry - ID kategorii lub typ
if(isset($_GET['id']))
{
	$param=' && cat='.$_GET['id'].' && ';
}
else
{
	$param='';
}

#Prawa
if(Admit($global))
{
	$join='';
}
else
{
	$join=' c LEFT JOIN '.PRE.'acl a ON c.cat=a.CatID';
	$param.=' && a.UID='.UID;
}

#Masowe zmiany?
/* DO NOT WORK YET
if($_POST && count($_POST['chk'])>0)
{
	$ids=GetIDs($_POST['chk']);
	if(isset($_POST['xu_d']))
	{
		if(Admit('DEL'))
		{
			$db->exec('DELETE FROM '.PRE.$xco.'s'.$join.' WHERE ID IN ('.join(',',$ids).') '.$param);
			$db->exec('DELETE FROM '.PRE.'comms WHERE type='.$co.' AND CID IN ('.join(',',$ids).')');
			if($co==1||$co==5) db_q('DELETE FROM '.PRE.''.(($co==1)?'artstxt':'fnews').' WHERE ID='.join(' || ID=',$ids));
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
} */

#Informacja
Info('<center>'.$lang['i'.$act].'<br /><br /><a href="javascript:Se()">'.$lang['search'].'</a> | <a href="?co=edit&amp;act='.$name.'">'.$lang['add'.$act].'</a>'.((Admit('C'))?' | <a href="adm.php?a=cats&amp;co='.$act.'">'.$lang['cats'].': '.$type.'</a>':'').'</center>');

#Strona
if(isset($_GET['page']) && $_GET['page']!=1)
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
$find=isset($_GET['find']) ? Clean($_GET['find'],30) : '';
if($find) $param.=' && name LIKE '.$db->quote($find.'%');

#Czêœæ URL
$url='?co=edit&amp;act='.$name.(($id)?'&amp;id='.$id:'');

?>
<script type="text/javascript">
<!--
function Del(id)
{
	if(confirm("<?=$lang['delc']?>"))
	{
		del=new Request("adm.php?x=del&id="+id,'i'+id);
		del.method='POST';
		del.add('co','<?=$type?>')
		del.run()
	}
}
function Se()
{
	if(a=prompt("<?=$lang['searp']?>")) location="?co=edit&act=<?=$act?>&find="+a;
}
-->
</script>
<?php

echo '<form action="'.$url.'&amp;page='.$page.'&amp;find='.$find.'" method="post">';

#Iloœæ wszystkich
$total=db_count('ID',$name.'s'.$join,(($param)?' WHERE'.substr($param,3):''));

#Nag³ówek
OpenBox($type,4);
echo '<tr>
	<th>'.$lang['name'].'</th>
	<th style="width: 50px">'.$lang['ison'].'</th>
	<th>'.$lang['opt'].'</th>
	<th style="width: 25px">&nbsp;</th>
</tr>';

#Pobierz pozycje
$res=$db->query('SELECT ID,name,access FROM '.PRE.$name.'s'.$join.
	(($param)?' WHERE'.substr($param,3):'').' ORDER BY ID DESC LIMIT '.$st.',25');
$res->setFetchMode(3);

#Lista
$ile=0;
foreach($res as $i)
{
	echo '<tr align="center">
	<td align="left" id="i'.$i[0].'">'.++$ile.'. <a href="?co='.$name.'&amp;id='.$i[0].'">'.$i[1].'</a></td>
	<td>';
	switch($i[2])
	{
		case 1: echo $lang['yes']; break;
		case 2: echo $lang['no']; break;
		default: echo $i[2];
	}
	echo '</td>
	<td nowrap="nowrap">
		<a href="?co=edit&amp;act='.$name.'&amp;id='.$i[0].'">'.$lang['edit'].'</a>
		&middot; <a href="javascript:Del('.$i[0].')">'.$lang['del'].'</a>
	</td>
	<td><input type="checkbox" name="chk['.$i[0].']" /></td>
</tr>';
}

#Strona
echo '<tr>
	<td class="eth"><a href="javascript:Show(\'mo\')">'.$lang['chopt'].' &raquo;</a></td>
	<td class="eth" colspan="3">
		<b>'.$lang['page'].':</b> '.
		Pages($page,$total,25,$url.'&amp;find='.$find,1).
		'</td>
</tr>';
CloseBox();

#Masowe zmiany
echo '<div id="mo" style="display: none">';

OpenBox($lang['chopt'],2);
echo '<tr>
	<td style="width: 35%"><b>1. '.$lang['cat'].':</b></td>
	<td><select name="xu_c">
		<option value="N">'.$lang['nochg'].'</option>'.
		Slaves($act).
		'<option value="0">'.$lang['lack'].'</option></select></td>
</tr>
<tr>
	<td><b>2. '.$lang['published'].'?</b></td>
	<td><select name="xu_a">
		<option value="N">'.$lang['nochg'].'</option>
		<option value="1">'.$lang['yes'].'</option>
		<option value="2">'.$lang['no'].'</option>
	</select></td>
</tr>
<tr>
	<td><b>3. '.$lang['opt'].':</b></td>
	<td><input type="checkbox" name="xu_d" id="xu_d" /> '.$lang['chdel'].'</td>
</tr>
<tr>
	<td class="eth" colspan="2"><input type="submit" value="OK" /></td>
</tr>';
CloseBox();
?>
</div>
</form>
<script type="text/javascript">
d("xu_d").checked=0
</script>
