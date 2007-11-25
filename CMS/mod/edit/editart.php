<?php
if(EC!=1) exit;

if($_POST)
{
	#Ilo¶æ stron
	$ile=count($_POST['x_txt']);

	#Nowe dane
	$art=array(
	'pages'=>$ile,
	'cat' =>(int)$_POST['x_c'],
	'dsc' =>Clean($_POST['x_d']),
	'name'=>Clean($_POST['x_n']),
	'author'=>Clean($_POST['x_au']),
	'access'=>((isset($_POST['x_a']))?1:2),
	'priority'=>(int)$_POST['x_p']);

	#Klasa
	$e=new Saver($art,$id,'arts');

	if($e->hasRight('A'))
	{
		#Zapytanie
		if($id)
		{
			$q=$db->prepare('UPDATE '.PRE.'arts SET cat=:cat, name=:name, dsc=:dsc,
			author=:author, access=:access, priority=:priority, pages=:pages WHERE ID='.$id);
		}
		else
		{
			$q=$db->prepare('INSERT INTO '.PRE.'arts (cat,name,dsc,date,author,access,priority,pages)
			VALUES (:cat,:name,:dsc,"'.NOW.'",:author,:access,:priority,:pages)');
		}
		$q->execute($art);

		#Nowe ID
		$nid=$id ? $id : $db->lastInsertId();

		#Pe³na tre¶æ
		$q=$db->prepare('REPLACE '.PRE.'artstxt (id,page,cat,text,opt)
			VALUES ('.$nid.',?,'.$art['cat'].',?,?)');

		#Tre¶æ
		for($i=1;$i<=$ile;++$i)
		{
			#Dane i opcje
			$fart[]=array($i, &$_POST['x_txt'][$i],
				(($_POST['x_br'][$i])?1:0)+(($_POST['x_emo'][$i])?2:0)+(($_POST['x_col'][$i])?4:0) );

			#Zapis
			$q->execute($fart[($i-1)]);
		}

		#Usuñ inne
		$db->exec('DELETE FROM '.PRE.'artstxt WHERE ID='.$nid.' && page>'.$ile);

		if($e->apply())
		{
			$e->info( array(
			'?co=edit&amp;act=art'=>$lang['add1'],
			'?co=edit&amp;act=1'	=>$lang['arts'],
			'?co=art&amp;id='.$nid=>$lang['seeit']));
			unset($e,$art,$fart);
			return;
		}
	}
	$e->showError(); #B³±d?
}

#FORM - Odczyt
else
{
	if($id)
	{
		$res=$db->query('SELECT * FROM '.PRE.'arts WHERE ID='.$id);
		$art=$res->fetch(2); //ASSOC
		$res=null;

		#Prawa
		if(!$art || (!Admit('A') && !Admit($art['cat'],'CAT',$art['author'])))
		{
			Info($lang['noex']);
			return;
		}

		#Pobierz tre¶æ
		$res=$db->query('SELECT page,text,opt FROM '.PRE.'artstxt WHERE ID='.$id);
		$fart=$res->fetchAll(3); //NUM
		$res=null;
		$ile=count($fart);
	}
	else
	{
		$art=array('pages'=>1,'name'=>'','access'=>1,'priority'=>2,'dsc'=>'','author'=>1,'cat'=>$last_cat);
		$fart=array(array(1,'',0));
		$ile=1;
	}
}

#Podgl±d
echo '<div id="dbox" style="display: none">';
OpenBox($lang['preview'],1);
echo '<tr><td id="tbox" class="txt"></td></tr>';
CloseBox();
 
#Form
echo '</div>
<form action="?co=edit&amp;act=art&amp;id='.$id.'" name="art" method="post">';

OpenBox($lang[ (($id)?'edit5':'add5') ] ,2);
echo '
<tr>
	<td style="width: 30%"><b>1. '.$lang['cat'].':</b></td>
	<td><select name="x_c">
		<option value="1">'.$lang['choose'].'</option>'.Slaves(1,$art['cat'],'A').'
	</select></td>
</tr>
<tr>
	<td><b>2. '.$lang['name'].':</b></td>
	<td><input maxlength="50" name="x_n" value="'.$art['name'].'" /></td>
</tr>
<tr>
	<td><b>3. '.$lang['published'].'?</b></td>
	<td>
		<input type="checkbox" name="x_a"'.(($art['access']==1)?' checked="checked"':'').' /> '.$lang['yes'].'
	</td>
</tr>
<tr>
  <td><b>4. '.$lang['priot'].':</b></td>
  <td><select name="x_p">
		<option value="1">'.$lang['high'].'</option>
		<option value="2"'.(($art['priority']==2)?' selected="selected"':'').'>'.$lang['normal'].'</option>
		<option value="3"'.(($art['priority']==3)?' selected="selected"':'').'>'.$lang['low'].'</option>
	</select></td>
</tr>
<tr>
	<td><b>5. '.$lang['desc'].':</b></td>
	<td><textarea name="x_d" cols="40" rows="2">'.$art['dsc'].'</textarea></td>
</tr>'.
	((Admit('A'))?'
<tr>
	<td><b>6. '.$lang['author'].':</b><br /><small>'.$lang['nameid'].'</small></td>
	<td><input name="x_au" value="'.$art['author'].'" maxlength="30" /></td>
</tr>'
	:'').'
<tr>
	<th colspan="2">'.$lang['text'].'</th>
</tr>
<tr>
	<td align="center" colspan="2">
		<div style="padding-bottom: 5px" id="tabs">'.$lang['page'].':
		<input type="button" class="tab" value="'.$lang['add'].'" onclick="NP()" /> ';

#Edytor
Init($catl.'edit.js');
Init('cache/emots.js');
Init('lib/editor.js');

#Karty
for($i=1;$i<=$ile;++$i)
{
	echo '<input type="button" class="tab" value="'.$i.'" id="tab'.$i.'" onclick="CP('.$i.')"'.(($i==1)?' style="font-weight: bold"':'').' />';
}
echo '</div><div id="tps">';

#Tre¶æ
for($i=1;$i<=$ile;++$i)
{
	$y=$i-1;
	echo '<div id="tp'.$i.'" style="display: none">
	<textarea name="x_txt['.$i.']" id="tpx'.$i.'" style="width: 100%" rows="18">'.
	Clean($fart[$y][1]).'</textarea>
	<fieldset>
		<legend>'.$lang['opt'].'</legend>
		<input type="checkbox" name="x_emo['.$i.']" onclick="Em()"'.(($fart[$y][2]&2)?' checked="checked"':'').' /> '.$lang['e_emo'].'&nbsp;
		<input type="checkbox" name="x_br['.$i.']"'.(($fart[$y][2]&1)?' checked="checked"':'').' /> '.$lang['e_br'].' &nbsp;
		<input type="checkbox" name="x_col['.$i.']"'.(($fart[$y][2]&4)?' checked="checked"':'').' /> '.$lang['e_col'].'
	</fieldset>
	</div>';
}
?>
<script type="text/javascript">
<!--
var pv=new Request('request.php?co=preview','tbox','')
pv.method='POST'
var f=document.forms['art'].elements;

function Prev()
{
	Show('dbox',1)
	location='#dbox'
	if(f['x_emo['+c+']'].checked) pv.add('EMOTS',1)
	if(f['x_br['+c+']'].checked) pv.add('NL',1)
	pv.add('HTML',1)
	pv.add('text',d('tpx'+c).value)
	pv.run()
}
var c=0;
var ile=<?=$ile?>;
function CP(p)
{
	if(c!=0)
	{
		d('tp'+c).style.display='none';
		d('tab'+c).removeAttribute('style')
	}
	Show('tp'+p,1)
	d('tab'+p).style.fontWeight='bold'
	ed.id='tpx'+p;
	c=p
}
function NP()
{
	++ile
	d('tabs').innerHTML+='<input class="tab" value="'+ile+'" type="button" id="tab'+ile+'" onclick="CP('+ile+')" />'
	var x=document.createElement('div')
	x.id='tp'+ile;
	x.innerHTML='<textarea rows="18" style="width: 100%" name="x_txt['+ile+']" id="tpx'+ile+'">'+
		'</textarea><fieldset><legend><?= $lang['opt'] ?></legend>'+
		'<input type="checkbox" name="x_emo['+ile+']" onclick="Em()" /> <?= $lang['e_emo'] ?>'+
		'<input type="checkbox" name="x_br['+ile+']" /> <?= $lang['e_br'] ?>'+
		'<input type="checkbox" name="x_col['+ile+']" /> <?= $lang['e_col'] ?></fieldset>'
	d('tps').appendChild(x)
	CP(ile)
}
function Em()
{
	if(f['x_emo['+c+']'].checked) ed.Emots(); else ed.Emots(0)
}
var ed=new Editor('tp1');
CP(1);
-->
</script>
</div>

<?php
echo $lang['arttip'].
	'</td>
</tr>
<tr>
	<td class="eth" colspan="2">
		<input type="submit" value="'.$lang['save'].'" />
		<input type="button" value="'.$lang['preview'].'" onclick="Prev()" /></td>
</tr>';
CloseBox();
?>
</form>
