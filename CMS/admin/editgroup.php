<?php
if(iCMSa!=1 || !Admit('UG')) return;
$id=isset($_GET['id'])?$_GET['id']:0;

#Zapis
if($_POST)
{
	#Dane
	$group=array(
	'name'=>Clean($_POST['g_n']),
	'dsc' =>$_POST['g_d'],
	'access'=>Clean($_POST['g_a']),
	'opened'=>isset($_POST['g_o'])?1:0 );

	#Edycja
	if($id)
	{
		$q=$db->prepare('UPDATE '.PRE.'groups SET name=:name, dsc=:dsc,
			access=:access, opened=:opened WHERE ID='.$id);
	}
	#Nowy
	else
	{
		$q=$db->prepare('INSERT INTO '.PRE.'groups (name,dsc,access,opened)
			VALUES (:name,:dsc,:access,:opened)');
	}
	#OK?
	try
	{
		$q->execute($group); Info($lang['saved']); return;
	}
	catch(PDOException $e)
	{
		Info($lang['error'].$e->errorInfo[0]);
	}
}

#Odczyt
else
{
	#Edycja
	if($id)
	{
		$group=$db->query('SELECT * FROM '.PRE.'groups WHERE ID='.$id)->fetch(2);
		if(!$group)
		{
			Info($lang['noex']); return;
		}
	}
	#Nowy
	else
	{
		$group=array('name'=>'','ison'=>1,'opened'=>0,'dsc'=>'');
	}
}

#Edytor JS + jêzyk
Init('lib/editor.js');
Init($catl.'edit.js');
require($catl.'adm_o.php');

#FORM
echo '<form action="?a=editgroup'.(($id)?'&amp;id='.$id:'').'" method="post">';
OpenBox($lang[ (($id)?'gredit':'gradd') ],2);

echo '<tr>
	<td style="width: 30%"><b>1. '.$lang['name'].':</b></td>
	<td><input name="g_n" maxlength="50" value="'.$group['name'].'" size="30" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['ap_acc'].':</b></td>
	<td>
		<select name="g_a">
		<option value="1">'.$lang['ap_isaon'].'</option>'.
		ListBox('lang',1, (($id)?$group['access']:null)).
		'<option value="2"'.(($id && $group['access']==2)?' selected="selected"':'').'>'.$lang['ap_isahid'].'</option>
		</select>
	</td>
</tr>
<tr>
	<td><b>3. '.$lang['opened'].'?</b></td>
	<td><input name="g_o" type="checkbox"'.(($group['opened']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>4. '.$lang['desc'].':</b></td>
	<td>
		<textarea id="g_d" name="g_d" rows="6" style="width: 95%">'.Clean($group['dsc']).'</textarea>
		<script type="text/javascript">var ed=new Editor("g_d")</script>
	</td>
</tr>
<tr>
	<td class="eth" colspan="2"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
CloseBox();
?>
</form>