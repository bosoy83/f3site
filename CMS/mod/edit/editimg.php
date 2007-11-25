<?php
if(EC!=1) exit;

#Zapis?
if($_POST)
{
	$img=array(
	'cat' =>(int)$_POST['x_c'],
	'name'=>Clean($_POST['x_n']),
	'author'=>Clean($_POST['x_au']),
	'dsc' 	=>Clean($_POST['x_d']),
	'file'	=>Clean($_POST['x_f']),
	'filem' =>Clean($_POST['x_fm']),
	'access'=>((isset($_POST['x_a']))?1:0),
	'priority'=>(int)$_POST['x_p'],
	'type'	=>(int)$_POST['x_t'],
	'size'  =>(($_POST['x_s1'])?$_POST['x_s1'].'|'.$_POST['x_s2']:'') );

	$e=new Saver($img,$id,'imgs');

	if($e->hasRight('I'))
	{
		#Zapytanie
		if($id)
		{
			$q=$db->prepare('UPDATE '.PRE.'imgs SET cat=:cat, name=:name, author=:author,
				dsc=:dsc, file=:file, filem=:filem, access=:access, priority=:priority,
				type=:type, size=:size WHERE ID='.$id);
		}
		else
		{
			$q=$db->prepare('INSERT INTO '.PRE.'imgs (cat,name,dsc,type,date,priority,
				access,author,file,filem,size) VALUES (:cat,:name,:dsc,:type,"'.NOW.'",
				:priority,:access,:author,:file,:filem,:size)');
		}
		$q->execute($img);
		$nid=$id?$id:$db->lastInsertId();

		#OK?
		if($e->apply())
		{
			$e->info( array(
				'?co=edit&amp;act=img'=> $lang['add3'],
				'?co=edit&amp;act=3'	=> $lang['imgs'],
				'?co=img&amp;id='.$nid=> $lang['seeit']));
			unset($e,$img);
			return;
		}
	}
	#B³±d?
	$e->showError();
}

#Odczyt
else
{
	if($id)
	{
		$img=$db->query('SELECT * FROM '.PRE.'imgs WHERE ID='.$id)->fetch(2);

		if(!$img || !Admit('I') || !Admit($img['cat'],'CAT',$img['author']))
		{
			Info($lang['noex']);
			return;
		}
	}
	else
	{
		$img=array('cat'=>$last_cat,'name'=>'','dsc'=>'','priority'=>2,'file'=>'img/',
			'filem'=>'img/','size'=>'','author'=>UID,'access'=>1,'type'=>1);
	}
}

#Rozmiar
$xsize=$img['size'] ? explode('|',$img['size']) : array('','');

#Edytor
Init($catl.'edit.js');
Init('lib/editor.js');

echo '<form action="index.php?co=edit&amp;act=img&amp;id='.$id.'" method="post">';
OpenBox($lang[ (($id)?'edit':'add').'3' ],2);
echo '
<tr>
  <td style="width: 30%"><b>1. '.$lang['cat'].':</b></td>
  <td><select name="x_c">
		<option value="0">'.$lang['choose'].'</option>'.
		Slaves(3,$img['cat'],'I').
	'</select></td>
</tr>
<tr>
  <td><b>2. '.$lang['title'].':</b></td>
  <td><input maxlength="50" size="30" name="x_n" value="'.$img['name'].'" /></td>
</tr>
<tr>
  <td><b>3. '.$lang['published'].':</b></td>
  <td><input type="checkbox" name="x_a"'.(($img['access']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
  <td><b>4. '.$lang['priot'].':</b></td>
  <td><select name="x_p">
		<option value="1">'.$lang['high'].'</option>
		<option value="2"'.(($img['priority']==2)?' selected="selected"':'').'>'.$lang['normal'].'</option>
		<option value="3"'.(($img['priority']==3)?' selected="selected"':'').'>'.$lang['low'].'</option>
	</select></td>
</tr>
<tr>
  <td><b>5. '.$lang['img'].':</b></td>
  <td>
		<input name="x_f" id="x_f" maxlength="200" value="'.$img['file'].'" />
		<input type="button" value="'.$lang['preview'].'" onclick="Okno(x_f.value,500,400,100,100)" />'.
		((Admit('FM'))?' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=x_f\',580,400,150,150)" />':'').'
	</td>
</tr>
<tr>
  <td><b>6. '.$lang['minimg'].':</b><br /><small>'.$lang['minimgd'].'</small></td>
  <td>
		<input name="x_fm" maxlength="50" value="'.$img['filem'].'" />
		<input type="button" value="'.$lang['preview'].'" onclick="Okno(x_fm.value,500,400,100,100)" />'.
		((Admit('FM'))?' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=x_fm\',580,400,150,150)" />':'').'
	</td>
</tr>
<tr>
  <td><b>7. '.$lang['author'].':</b><br /><small>'.$lang['nameid'].'</small></td>
  <td><input name="x_au" maxlength="30" value="'.$img['author'].'" /></td>
</tr>
<tr>
  <td><b>8. '.$lang['type'].':</b></td>
  <td>
		<input type="radio" name="x_t" value="1"'.(($img['type']==1)?' checked="checked"':'').' /> '.$lang['img'].' &lt;img&gt; &nbsp;
		<input type="radio" name="x_t" value="2"'.(($img['type']==2)?' checked="checked"':'').' /> Flash &nbsp;
		<input type="radio" name="x_t" value="3"'.(($img['type']==3)?' checked="checked"':'').' /> QuickTime
	</td>
</tr>
<tr>
  <td><b>9. '.$lang['isize'].':</b><div class="txtm">'.$lang['isized'].'</div></td>
  <td>
		<input name="x_s1" value="'.$xsize[0].'" size="2" maxlength="4" /> x
		<input name="x_s2" value="'.$xsize[1].'" size="2" maxlength="4" /> (px)
	</td>
</tr>
<tr>
	<th colspan="2">'.$lang['desc'].'</th>
</tr>
<tr>
  <td colspan="2" align="center">
		<textarea name="x_d" id="x_d" rows="8" style="width: 100%">'.
		Clean($img['dsc']).'</textarea>
	</td>
</tr>
<tr>
  <td colspan="2" class="eth"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
CloseBox();
?>
</form>
<script type="text/javascript">
var ed=new Editor('x_d');
</script>
