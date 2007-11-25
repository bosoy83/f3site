<?php
if(EC!=1) exit;

//Funkcja zapisu
if($_POST)
{
	//Nowe dane
	$news=array(
	'opt' =>(($_POST['x_br'])?1:0)+(($_POST['x_emo'])?2:0)+(($_POST['x_fn'])?4:0),
	'name'=>Clean($_POST['x_n']),
	'img' =>Clean($_POST['x_i']),
	'txt' =>&$_POST['x_txt'],
	'cat'	=>(int)$_POST['x_c'],
	'access'=>((isset($_POST['x_a']))?1:2));

	$e=new Saver($news,$id,'news');

	if($e->hasRight('N'))
	{
		//Query
		if($id)
		{
			$q=$db->prepare('UPDATE '.PRE.'news SET cat=:cat, name=:name, txt=:txt,
				img=:img, access=:access, opt=:opt WHERE ID='.$id);
		}
		else
		{
			$q=$db->prepare('INSERT INTO '.PRE.'news (cat,name,txt,date,author,img,access,opt)
				VALUES (:cat,:name,:txt,"'.NOW.'",'.UID.',:img,:access,:opt)');
		}
		$q->execute($news);

		//Nowe ID
		$nid = $id ? $id : $db->lastInsertId();

		//Pe³ny tekst
		$news['text']=&$_POST['x_ftxt'];

		$q=$db->prepare('REPLACE INTO '.PRE.'fnews (id,cat,text) VALUES ('.$nid.',?,?)');
		$q->bindValue(1,$news['cat'],1); //INT
		$q->bindParam(2,$news['text']);
		$q->execute();

		//OK?
		if($e->apply())
		{
			$e->info( array(
				'?co=edit&amp;act=news'=> $lang['add5'],
				'?co=edit&amp;act=5'	 => $lang['news'],
				'?co=news&amp;id='.$nid=> $lang['seeit']));
			unset($e,$news);
			return;
		}
	}

	#B³±d?
	$e->showError();
}

//Formularz
else
{
	//Odczyt
	if($id)
	{
		$res=$db->query('SELECT n.*,f.text FROM '.PRE.'news n LEFT JOIN '.
			PRE.'fnews f ON n.ID=f.ID WHERE n.ID='.$id);
		$news=$res->fetch(2); //ASSOC
		$res=null;

		#Prawa
		if(!$news || !Admit('N') || !Admit($news['cat'],'CAT',$news['author']))
		{
			Info($lang['noex']);
			return;
		}
	}
	else
	{
		$news=array('cat'=>$last_cat,'name'=>'','txt'=>'','text'=>'','access'=>1,'img'=>'','opt'=>1);
	}
}

//Edytor JS
Init($catl.'edit.js');
Init('cache/emots.js');
Init('lib/editor.js');

echo '<form action="?co=edit&amp;act=new&amp;id='.$id.'" method="post">';
OpenBox($lang[ (($id)?'edit5':'add5') ],2);
echo '
<tr>
	<td style="width: 31%"><b>1. '.$lang['cat'].':</b></td>
	<td><select name="x_c">
		<option value="0">'.$lang['choose'].'</option>'.
		Slaves(5,$news['cat'],'N').
	'</select></td>
</tr>
<tr>
	<td><b>2. '.$lang['title'].':</b></td>
	<td><input maxlength="50" name="x_n" value="'.$news['name'].'" /></td>
</tr>
<tr>
	<td><b>3. '.$lang['published'].'?</b></td>
	<td><input type="checkbox" name="x_a"'.(($news['access']==1)?' checked="checked"':'').' /></td>
</tr>
<tr>
	<td><b>4. '.$lang['img'].':</b></td>
	<td>
		<input name="x_i" id="x_i" value="'.$news['img'].'" />'.
		((Admit('FM'))?' <input type="button" value="'.$lang['images'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=xu_i\',580,400,150,150)" />':'').'
	</td>
</tr>
<tr>
	<td><b>5. '.$lang['opt'].':</b></td>
	<td>
		<input type="checkbox" name="x_br"'.(($news['opt']&1)?' checked="checked"':'').' /> '.$lang['e_br'].'<br />
		<input type="checkbox" name="x_emo"'.(($news['opt']&2)?' checked="checked"':'').' /> '.$lang['emoon'].'<br />
		<input type="checkbox" id="fn" name="x_fn"'.(($news['opt']&4)?' checked="checked"':'').' onclick="FN()" /> '.$lang['ftxt'].'
	</td>
</tr>';
CloseBox();

#Tre¶æ
OpenBox($lang['text'],1);
echo '
<tr>
	<td align="center">
		<textarea style="width: 100%" rows="10" id="x_txt" name="x_txt">'
		.Clean($news['txt']).'</textarea>
	</td>
</tr>
<tr class="eth">
	<td>
		<input type="button" value="'.$lang['preview'].'" onclick="Prev()" />
		<input type="submit" name="sav" value="'.$lang['save'].'" />
	</td>
</tr>';
CloseBox();

#Pe³na tre¶æ
echo '<div id="full" style="display: none">';
OpenBox($lang['ftxt'],1);
echo '
<tr>
  <td align="center">
		<textarea style="width: 100%" id="x_ftxt" rows="13" name="x_ftxt">'
		.Clean($news['text']).'</textarea>
	</td>
</tr>';
CloseBox();
?>
</div>
</form>
<script type="text/javascript">
<!--
var ed=new Editor('x_txt');
ed.Emots();
var done=(d('fn').checked)?1:0;

function FN(x)
{
	Show('full');
	if(done!=1 || x==1)
	{
		var ed2=new Editor('x_ftxt');
		ed2.Emots(emots);
		done=1;
	}
}
if(done==1) FN(1);
-->
</script>
