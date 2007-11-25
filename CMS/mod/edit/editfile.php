<?php
if(EC!=1) exit;
require($catl.'files.php');

#Zapis?
if($_POST)
{
	#Dane
	$file=array(
	'cat' =>(int)$_POST['x_c'],
	'dsc' =>Clean($_POST['x_d']),
	'name'=>Clean($_POST['x_n']),
	'file'=>Clean($_POST['x_f']),
	'size'=>Clean($_POST['x_s']),
	'fulld'=>$_POST['x_fd'],
	'author'=>Clean($_POST['x_au']),
	'access'=>((isset($_POST['x_a']))?1:2),
	'priority'=>(int)$_POST['x_p']);

	$e=new Saver($file,$id,'files');

	if($e->hasRight('F'))
	{
		#Zapytanie
		if($id)
		{
			$q=$db->prepare('UPDATE '.PRE.'files SET cat=:cat, name=:name, author=:author, dsc=:dsc,
				file=:file, access=:access, size=:size, priority=:priority, fulld=:fulld WHERE ID='.$id);
		}
		else
		{
			$q=$db->prepare('INSERT INTO '.PRE.'files (cat,name,author,dsc,file,access,size,
				priority,fulld) VALUES (:cat,:name,:author,:dsc,:file,:access,:size,:priority,:fulld)');
		}
		$q->execute($file);
		$nid=$id ? $id : $db->lastInsertId();

		#OK?
		if($e->apply())
		{
			$e->info( array(
				'?co=edit&amp;act=file'=> $lang['add2'],
				'?co=edit&amp;act=2'	 => $lang['files'],
				'?co=file&amp;id='.$nid=> $lang['seeit']));
			unset($e,$file);
			return;
		}
	}

	#B³±d?
	$e->showError();
}

#Form
else
{
	#Odczyt
	if($id)
	{
		$file=$db->query('SELECT * FROM '.PRE.'files WHERE ID='.$id)->fetch(2);

		if(!$file || !Admit('F') || !Admit($file['cat'],'CAT',$file['author']))
		{
			Info($lang['noex']);
			return;
		}
	}
	else
	{
		$file=array('cat'=>$last_cat,'name'=>'','dsc'=>'','priority'=>2,'file'=>'files/',
			'size'=>'','author'=>UID,'fulld'=>'','access'=>1);
	}
}

Init($catl.'edit.js');
Init('lib/editor.js');

echo '<form action="?co=edit&amp;act=file&amp;id='.$id.'" method="post">';
OpenBox($lang[ (($id)?'edit':'add').'2' ],2);
echo '
<tr>
	<td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
	<td><select name="x_c">
		<option value="0">'.$lang['choose'].'</option>'.Slaves(2,$file['cat'],'F').'
	</select></td>
</tr>
<tr>
	<td><b>2. '.$lang['name'].':</b></td>
	<td><input maxlength="50" size="30" name="x_n" value="'.$file['name'].'" /></td>
</tr>
<tr>
	<td><b>3. '.$lang['published'].'?</b></td>
	<td><input type="checkbox" name="x_a"'.(($file['access']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>4. '.$lang['priot'].':</b></td>
	<td><select name="x_p">
		<option value="1">'.$lang['high'].'</option>
		<option value="2"'.(($file['priority']==2)?' selected="selected"':'').'>'.$lang['normal'].'</option>
		<option value="3"'.(($file['priority']==3)?' selected="selected"':'').'>'.$lang['low'].'</option>
	</select></td>
</tr>
<tr>
	<td><b>5. '.$lang['desc'].':</b></td>
	<td><textarea name="x_d" style="width: 95%">'.$file['dsc'].'</textarea></td>
</tr>
<tr>
	<td><b>7. '.$lang['file'].':</b></td>
	<td><input name="x_f" maxlength="230" value="'.$file['file'].'" size="30" />'.
		((Admit('FM'))?' <input type="button" value="'.$lang['files'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=x_f&amp;dir=./files/\',580,400,150,150)"':'').'</td>
</tr>
<tr>
	<td><b>8. '.$lang['size'].':</b></td>
	<td><input name="x_s" maxlength="20" value="'.$file['size'].'" /></td>
</tr>
<tr>
	<td><b>9. '.$lang['author'].':</b><br /><small>'.$lang['nameid'].'</small></td>
	<td><input name="x_au" maxlength="30" value="'.$file['author'].'" /></td>
</tr>
<tr>
	<th colspan="2">'.$lang['fulld'].'</th>' ?>
</tr>
<tr>
	<td colspan="2" align="center">
		<textarea name="x_fd" id="fd" rows="8" style="width: 100%"><?= Clean($file['fulld']) ?></textarea>
	</td>
</tr>
<tr class="eth">
	<td colspan="2"><input type="submit" value="<?php echo $lang['save'].'" /></td>
</tr>';
CloseBox();
?>
</form>
<script type="text/javascript">
var ed=new Editor("fd");
</script>
