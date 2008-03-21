<?php
if(iCMSa!=1 || !Admit('C')) exit;
require(LANG_DIR.'adm_o.php');
require('./lib/categories.php');
$id=isset($_GET['id'])?$_GET['id']:0;

#Zapis
if($_POST)
{
	#Wy¿sza kat.
	$up=(int)$_POST['x_sc'];

	$db->beginTransaction(); //START

	$cat=array(
	'sc'=>$up,
	'dsc'=>Clean($_POST['x_d']),
	'name'=>Clean($_POST['x_n']),
	'text'=>$_POST['x_txt'],
	'type'=>(int)$_POST['x_t'],
	'sort'=>(int)$_POST['x_sort'],
	'opt' =>((isset($_POST['xc1']))?1:0)+((isset($_POST['xc2']))?2:0)+
		((isset($_POST['xc3']))?4:0)+((isset($_POST['xc4']))?8:0),
	'access'=>Clean($_POST['x_vis']) );
	
	#Edytuj
	if($id)
	{
		$q=$db->prepare('UPDATE '.PRE.'cats SET name=:name,dsc=:dsc,access=:access,
			type=:type,sc=:sc,sort=:sort,text=:text,opt=:opt WHERE ID='.$id);
		$old=$db->query('SELECT ID,access,sc,lft,rgt FROM '.PRE.'cats WHERE ID='.$id)->fetch(3); //NUM
	}
	#Nowa
	else
	{
		#Zapis
		$q=$db->prepare('INSERT INTO '.PRE.'cats (name,dsc,access,type,sc,sort,text,opt,lft,rgt)
			VALUES (:name,:dsc,:access,:type,:sc,:sort,:text,:opt,:lft,:rgt)');

		#LFT i RGT
		$cat['lft']=(int)db_get('rgt','cats',(($up)?' WHERE ID='.$up:' ORDER BY lft DESC LIMIT 1'));
		if($up)
		{
			$db->exec('UPDATE '.PRE.'cats SET lft=lft+2 WHERE lft>='.$cat['lft']);
			$db->exec('UPDATE '.PRE.'cats SET rgt=rgt+2 WHERE rgt>='.$cat['lft']);
		}
		else
		{
			++$cat['lft'];
		}
		$cat['rgt']=$cat['lft']+1;
	}

	/* SKOMPLIKOWANE ALGORYTMY INNYM RAZEM!!!!!!???? NA RAZIE PE£NA PRZEBUDOWA DRZEWA */

	#ZatwierdŸ
	$q->execute($cat);

	#Pobierz ID lub dokonaj zmian LFT i RGT
	if(!$id)
	{
		$id=$db->lastInsertId();
	}
	elseif($up!=$old[2])
	{
		RebuildTree();
	}

	#OK
	try
	{
		$db->commit();
		UpdateCatPath($id);
		Info('<center>'.$lang['saved'].' ID: '.$id.'<br /><br /><a href="?a=editcat">'.$lang['ap_kaddc'].'</a></center>');
		return;
	}
	catch(PDOException $e)
	{
		Info($e->getMessage());
	}
}

#FORMULARZ: Odczyt
elseif($id)
{
	$res=$db->query('SELECT * FROM '.PRE.'cats WHERE ID='.$id);
	$cat=$res->fetch(2);
	$res=null;
	if(empty($cat['ID'])) { Info('Kategoria nie istnieje!'); return; }
}
#Domyœlne dane
else
{
	$cat=array('name'=>'','dsc'=>'','access'=>1,'type'=>5,'sc'=>0,'text'=>'','sort'=>2,'opt'=>'');
}
#Edytor JS
Init(LANG_DIR.'edit.js');
Init('lib/editor.js');

#Form
echo '<form action="adm.php?a=editcat'.(($id)?'&amp;id='.$id:'').'" method="post">';
OpenBox($lang[ (($id)?'ap_editc':'ap_kaddc') ],2);

echo '<tr>
	<td style="width: 30%"><b>1. '.$lang['name'].':</b></td>
	<td><input name="x_n" value="'.$cat['name'].'" maxlength="50" size="50" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['desc'].':</b></td>
	<td><input name="x_d" value="'.$cat['dsc'].'" maxlength="200" size="50" /></td>
</tr>
<tr>
	<td><b>3. '.$lang['ap_acc'].':</b></td>
	<td>
		<select name="x_vis">
			<option value="1">'.$lang['ap_isaon'].'</option>'
			.ListBox('lang',1,$cat['access']).
			'<option value="2"'.(($cat['access']==2)?' selected="selected"':'').'>'.$lang['ap_ishidden'].'</option>
			<option value="3"'.(($cat['access']==3)?' selected="selected"':'').'>'.$lang['ap_isaoff'].'</option>
		</select>
	</td>
</tr>
<tr>
	<td><b>4. '.$lang['ap_type'].':</b></td>
	<td>
		<input type="radio" name="x_t" id="c1" value="1" '.(($cat['type']==1)?' checked="checked"':'').'/> <label for="c1">'.$lang['arts'].'</label>
		<input type="radio" name="x_t" id="c2" value="2" '.(($cat['type']==2)?' checked="checked"':'').'/> <label for="c2">'.$lang['files'].'</label>
		<input type="radio" name="x_t" id="c3" value="3" '.(($cat['type']==3)?' checked="checked"':'').'/> <label for="c3">'.$lang['imgs'].'</label>
		<input type="radio" name="x_t" id="c4" value="4" '.(($cat['type']==4)?' checked="checked"':'').'/> <label for="c4">'.$lang['links'].'</label>
		<input type="radio" name="x_t" id="c5" value="5" '.(($cat['type']==5)?' checked="checked"':'').'/> <label for="c5">'.$lang['news'].'</label>
	</td>
</tr>
<tr>
	<td>
		<b>5. '.$lang['ap_wtxt'].':</b>
		<small>'.$lang['ap_wtxtd'].'</small>
	</td>
	<td>
		<span onclick="Show(\'box\'); var ed=new Editor(\'x_txt\'); this.style.display=\'none\'" style="cursor: crosshair; color: brown">&raquo; '.$lang['clickedit'].' &laquo;</span>
		<div id="box" style="display: none">
			<textarea name="x_txt" id="x_txt" style="width: 95%" cols="30" rows="9">'.Clean($cat['text']).'</textarea>
		</div>
	</td>
</tr>
<tr>
	<td><b>6. '.$lang['upcat'].':</b></td>
	<td>
		<select name="x_sc">
			<option value="0">'.$lang['scno'].'</option>'.Slaves(0,$cat['sc'],$id).'
		</select>
	</td>
</tr>
<tr>
	<td>
		<b>7. '.$lang['ap_sort'].':</b><br />
		<small>'.$lang['ap_nnews'].'</small>
	</td>
	<td>
		<input type="radio" name="x_sort" value="1" '.(($cat['sort']==1)?'checked="checked"':'').'/> '.$lang['sortid'].'<br />
		<input type="radio" name="x_sort" value="2" '.(($cat['sort']==2)?'checked="checked"':'').'/> '.$lang['sortid2'].'<br />
		<input type="radio" name="x_sort" value="3" '.(($cat['sort']==3)?'checked="checked"':'').'/> '.$lang['sortn'].'
	</td>
</tr>
<tr>
	<td>
		<b>8. '.$lang['opt'].':</b><br />
		<small>'.$lang['ap_disd'].'</small>
	</td>
	<td>
		<input type="checkbox" name="xc4"'.(($cat['opt']&8)?' checked="checked"':'').' /> '.$lang['ap_dis4'].'<br />
		<input type="checkbox" name="xc1"'.(($cat['opt']&1)?' checked="checked"':'').' /> '.$lang['ap_dis1'].'<br />
		<input type="checkbox" name="xc2"'.(($cat['opt']&2)?' checked="checked"':'').' /> '.$lang['ap_dis2'].'<br />
		<input type="checkbox" name="xc3"'.(($cat['opt']&4)?' checked="checked"':'').' /> '.$lang['ap_dis3'].'</td>
</tr>
<tr class="eth">
	<td colspan="2">
		<input type="submit" value="'.$lang['save'].'" />
		<input type="reset" value="'.$lang['reset'].'" />
	</td>
</tr>';
CloseBox(); ?>
</form>
