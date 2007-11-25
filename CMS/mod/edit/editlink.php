<?php
if(EC!=1) exit;

//Zapisz
if($_POST)
{
	//Dane
	$link=array(
	'cat' =>(int)$_POST['x_c'],
	'dsc' =>Clean($_POST['x_d']),
	'adr' =>Clean( str_replace(array('javascript:','vbscript:'),'',$_POST['x_adr']) ),
	'name'=>Clean($_POST['x_n']),
	'nw'  =>(isset($_POST['x_nw'])?1:0),
	'access'=>(isset($_POST['x_a'])?1:2),
	'priority'=>(int)$_POST['x_p'] );

	$e=new Saver($link,$id,'links','cat,access');

	//Ma prawa?
	if($e->hasRight('L'))
	{
		//Zapytanie
		$q=$db->prepare('REPLACE INTO '.PRE.'links (ID,cat,name,dsc,access,adr,priority,nw)
			VALUES ('.(($id)?$id:'null').',:cat,:name,:dsc,:access,:adr,:priority,:nw)');
		$q->execute($link);

		//OK?
		if($e->apply())
		{
			$e->info( array(
				'?co=edit&amp;act=link'	=> $lang['add4'],
				'?co=edit&amp;act=4'		=> $lang['links'],
				$link['adr'] => $lang['seeit']));
			unset($e,$link);
			return;
		}
	}

	//B³¹d
	$e->showError();
}

//Odczyt
else
{
	if($id)
	{
		$res=$db->query('SELECT * FROM '.PRE.'links WHERE ID='.$id);
		$link=$res->fetch(2); //ASSOC
		$res=null;

		//Prawa
		if(!$link || (!Admit('L') && !Admit($link['cat'],'CAT')))
		{
			Info($lang['noex']);
			return;
		}
	}
	else
	{
		$link=array('cat'=>$last_cat,'name'=>'','dsc'=>'','access'=>1,'nw'=>0,'priority'=>2,'adr'=>'http://');
	}
}

//Form
echo '<form action="?co=edit&amp;act=link&amp;id='.$id.'" method="post">';
OpenBox($lang[ (($id)?'edit4':'add4') ],2);
echo '
<tr>
  <td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
  <td><select name="x_c">
		<option value="0">'.$lang['choose'].'</option>'.Slaves(4,$link['cat'],'L').'
	</select></td>
</tr>
<tr>
  <td><b>2. '.$lang['name'].':</b></td>
  <td><input maxlength="50" name="x_n" size="40" value="'.$link['name'].'" /></td>
</tr>
<tr>
  <td><b>3. '.$lang['published'].'?</b></td>
  <td><input type="checkbox" name="x_a"'.(($link['access']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
  <td><b>4. '.$lang['priot'].':</b></td>
  <td><select name="x_p">
		<option value="1">'.$lang['high'].'</option>
		<option value="2"'.(($link['priority']==2)?' selected="selected"':'').'>'.$lang['normal'].'</option>
		<option value="3"'.(($link['priority']==3)?' selected="selected"':'').'>'.$lang['low'].'</option>
	</select></td>
</tr>
<tr>
  <td><b>5. '.$lang['desc'].':</b></td>
  <td><textarea name="x_d" style="width: 95%">'.$link['dsc'].'</textarea></td>
</tr>
<tr>
  <td><b>6. '.$lang['adr'].':</b></td>
  <td><input size="40" maxlength="200" name="x_adr" value="'.$link['adr'].'" /></td>
</tr>
<tr>
  <td><b>7. '.$lang['opt'].':</b></td>
  <td><input type="checkbox" name="x_nw"'.(($link['nw']==1)?' checked="checked"':'').' /> '.$lang['openinnw'].'</td>
</tr>
<tr>
  <td colspan="2" class="eth"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
CloseBox();
?>
</form>
