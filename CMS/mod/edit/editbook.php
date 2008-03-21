<?php
if(EC!=1) return;

#Zapis?
if($_POST)
{
	$book=array(
	'cat'=>(int)$_POST['x_c'],
	'aut'=>Clean($_POST['x_au']),
	'dsc'=>$_POST['x_d'],
	'img'=>Clean($_POST['x_f']),
	'min'=>Clean($_POST['x_fm']),
	'str'=>(int)$_POST['x_st'],
	'name'=>Clean($_POST['x_n']),
	'ceny'=>Clean($_POST['x_ce']),
	'isbn'=>Clean($_POST['x_i']),
	'type'=>((isset($_POST['x_t']))?2:1),
	'access'=>((isset($_POST['x_a']))?1:0) );

	$e=new Saver($book,$id,'books','cat,aut,access');

	if($e->hasRight('BOOK'))
	{
		if($id)
		{
			$q=$db->prepare('UPDATE '.PRE.'books SET cat=:cat, aut=:aut, dsc=:dsc, img=:img, min=:min, str=:str, name=:name, ceny=:ceny, isbn=:isbn, type=:type, access=:access WHERE ID='.$id);
		}
		else
		{
			$q=$db->prepare('INSERT INTO '.PRE.'books (cat,aut,dsc,img,min,str,name,ceny,isbn,type,access) VALUES (:cat,:aut,:dsc,:img,:min,:str,:name,:ceny,:isbn,:type,:access)');
		}
		$q->execute($book);
		$nid=$id?$id:$db->lastInsertId();

		#OK?
		if($e->apply())
		{
			$e->info( array(
				'?co=edit&amp;act=book'=>'Dodaj now± ksi±¿kê',
				'?co=edit&amp;act=6'	=> 'Lista ksi±¿ek',
				'?co=book&amp;id='.$nid=>'Wy¶wietl ksi±¿kê'));
			unset($e,$book);
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
		$book=$db->query('SELECT * FROM '.PRE.'books WHERE ID='.$id)->fetch(2);

		if(!$book || !Admit('BOOK') || !Admit($book['cat'],'CAT',$book['author']))
		{
			Info($lang['noex']);
			return;
		}
	}
	else
	{
		$book=array('cat'=>$lastCat,'aut'=>'','dsc'=>'Zachêcamy do zakupu tej pozycji.','img'=>'img/','min'=>'img/','str'=>48,'name'=>'','ceny'=>'','isbn'=>'','type'=>1,'access'=>1);
	}
}

#Edytor
Init(LANG_DIR.'edit.js');
Init('lib/editor.js');

echo '<form action="?co=edit&amp;act=book'.(($id)?'&amp;id='.$id:'').'" method="post">';
OpenBox( (($id)?'Edytuj ksi±¿kê':'Dodaj now± ksi±¿kê') ,0);
echo '<tr>
  <td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
  <td><select name="x_c">
		<option value="0">'.$lang['choose'].'</option>'.Slaves(6,$book['cat'],'BOOK').'
	</select></td>
</tr>
<tr>
  <td><b>2. '.$lang['title'].':</b></td>
  <td><input maxlength="50" name="x_n" value="'.$book['name'].'" /></td>
</tr>
<tr>
  <td><b>3. ISBN:</b></td>
  <td><input maxlength="30" name="x_i" value="'.$book['isbn'].'" /></td>
 </tr>
<tr>
  <td><b>4. '.$lang['published'].':</b></td>
  <td><input type="checkbox" name="x_a"'.(($book['access']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
  <td><b>5. '.$lang['img'].':</b><div class="txtm">'.$lang['ap_filed'].'</div></td>
  <td><input name="x_f" id="x_f" maxlength="200" value="'.$book['img'].'" /> <input type="button" value="'.$lang['preview'].'" onclick="Okno(x_f.value,500,400,100,100)" />'.((Admit('FM'))?' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?mode=adm&amp;x=fm&amp;ff=x_f\',580,400,150,150)" />':'').'</td>
</tr>
<tr>
  <td><b>6. '.$lang['minimg'].':</b><div class="txtm">'.$lang['minimgd'].'</div></td>
  <td><input name="x_fm" maxlength="50" value="'.$book['min'].'" /> <input type="button" value="'.$lang['preview'].'" onclick="Okno(x_fm.value,500,400,100,100)" />'.((Admit('FM'))?' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?mode=adm&amp;x=fm&amp;ff=x_fm\',580,400,150,150)" />':'').'</td>
</tr>
<tr>
  <td><b>7. '.$lang['author'].':</b><div class="txtm">'.$lang['nameid'].'</div></td>
  <td><input name="x_au" maxlength="30" value="'.$book['aut'].'" /></td>
</tr>
<tr>
  <td><b>8. Stron:</b></td>
  <td><input name="x_st" value="'.$book['str'].'" style="width: 50px" maxlength="5" /> <input type="checkbox" name="x_t"'.(($book['type']==2)?' checked="checked"':'').' /> Twarda ok³adka</td>
</tr>
<tr>
  <td><b>9. Cena:</b></td>
  <td><b>$</b> <input name="x_ce" value="'.$book['ceny'].'" style="width: 57px" maxlength="8" /></td>
</tr>
<tr>
	<th colspan="2">10. Opis ksi±¿eczki</th>
</tr>
<tr>
  <td colspan="2" align="center">
		<textarea name="x_d" id="x_d" rows="12" style="width: 100%">'.
		Clean($book['dsc']).'</textarea>
	</td>
</tr>
<tr class="eth">
	<td colspan="2"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
CloseBox();
?>
</form>
<script type="text/javascript">
var ed=new Editor('x_d');
</script>
