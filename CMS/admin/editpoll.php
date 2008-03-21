<?php
if(iCMSa!=1) exit;

#Jêzyk
require LANG_DIR.'f3s.php';

#Klasa zapisu zmiennych do pliku PHP
require './lib/config.php';

#Zapis
if($_POST)
{	
	$ile = count($_POST['x_seq']);
	$del = array();

	#Dane
	$poll = array(
		'name' => Clean($_POST['x_n']),
		'q'    => Clean($_POST['x_q']),
		'ison' => (int)$_POST['x_on'],
		'type' => (int)$_POST['x_t'],
		'access' => ctype_alpha($_POST['x_lang']) ? $_POST['x_lang'] : $nlang
	);

	#START
	$db->beginTransaction();

	#Edycja
	if($id)
	{
		$q = $db->prepare('UPDATE '.PRE.'polls SET name=:name, q=:q, ison=:ison,
			type=:type, access=:access WHERE ID='.$id);
	}
	#Nowy
	else
	{
		$q = $db->prepare('INSERT INTO '.PRE.'polls (name,q,ison,type,num,access,date)
			VALUES (:name,:q,:ison,:type,0,:access,CURRENT_DATE)');
	}
	$q->execute($poll);

	#Nowy ID
	if(!$id) $id  = $db->lastInsertId();
	
	#Odpowiedzi
	$q = $db->prepare('REPLACE INTO '.PRE.'answers (ID,IDP,seq,a) VALUES (:ID,:IDP,:seq,:a)');

	for($i=0;$i<$ile;$i++)
	{
		#Dodaj, zmieñ
		if(is_numeric($_POST['x_seq'][$i]))
		{
			$an[$i] = array(
				'seq' => (int) $_POST['x_seq'][$i], 
				'ID'  => empty($_POST['x_id'][$i]) ?  null : (int) $_POST['x_id'][$i],
				'IDP' => $id,
				'a'   => Clean($_POST['x_an'][$i])
			);
			$q->execute($an[$i]);
		}
		#Usuñ
		else $del[] = (int)$_POST['x_id'][$i];
	}
	if($del) $db->exec('DELETE FROM '.PRE.'answers WHERE ID IN ('.join(',',$del).')');

	#Utwórz cache najnowszej sondy
	if(db_count('ID','polls WHERE ID='.$id.' AND access="'.$poll['access'].'" ORDER BY ID DESC LIMIT 1')===1)
	{
		#Pobierz odpowiedzi
		$an = $db->query('SELECT ID,a FROM '.PRE.'answers WHERE IDP='.$id.' ORDER BY seq')->fetchAll(3);
		$poll['ID'] = $id;
	
		#Zapisz do pliku
		$file = new Config('./cache/poll_'.$nlang.'.php');
		$file->add('poll',$poll);
		$file->add('option',$an);
		$file->add('ile',$ile);
		$file->save();
	}

	#ZatwierdŸ
	try
	{
		$db->commit();
		Info('<center>'.$lang['saved'].'<br /><br /><a href="?a=editpoll">'.$lang['addpoll'].'</a> | <a href="index.php?co=poll&amp;id='.$id.'">'.$poll['name'].'</a></center>');
		return 1;
	}
	catch(PDOException $e)
	{
		Info($e->getMessage());
	}
}

#Form
elseif($id)
{
	$poll = $db->query('SELECT * FROM '.PRE.'polls WHERE ID='.$id)->fetch(2); //ASSOC

	$res = $db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$id.' ORDER BY seq');
	$an = $res->fetchAll(2); //ASSOC
	$res = null;
	$ile = count($an);
}
else
{
	$poll = array('name'=>'', 'q'=>'', 'type'=>1, 'ison'=>1, 'access'=>$nlang);
	$ile = 3;
}
	
?>
<script type="text/javascript">
<!--
ileusr=<?=$ile?>;
function Dodaj() { ii=ileusr+1; d("odp"+ileusr).innerHTML='<?=$lang['answ']?> <input name="x_seq[]" size="1" value="'+ii+'" /> <input name="x_an[]" size="30" /> <input type="hidden" name="x_id[]" value="0" /><br /><div id="odp'+ii+'"></div>'; ileusr++; }
-->
</script>
<?php
echo '<form action="?a=editpoll'.(($id)?'&amp;id='.$id:'').'" method="post">';
OpenBox( (($id) ? $lang['editpoll'] : $lang['addpoll'] ), 2);
echo '
<tr>
	<td><b>1. '.$lang['name'].':</b></td>
	<td><input name="x_n" maxlength="50" value="'.$poll['name'].'" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['que'].':</b></td>
	<td><input name="x_q" maxlength="150" value="'.$poll['q'].'" style="width: 80%" /></td>
</tr>
<tr>
	<td><b>3. '.$lang['allowv'].'?</b></td>
	<td>
		<input name="x_on" type="radio" value="1"'.(($poll['ison']==1)?' checked="checked"':'').' /> '.$lang['yes'].' &nbsp;
		<input type="radio" name="x_on" value="2"'.(($poll['ison']==2)?' checked="checked"':'').' /> '.$lang['no'].' &nbsp;
		<input type="radio" name="x_on" value="3"'.(($poll['ison']==3)?' checked="checked"':'').' /> '.$lang['forregt'].'
	</td>
</tr>
<tr>
	<td><b>4. '.$lang['lang'].':</b></td>
	<td><select name="x_lang">'.ListBox('lang',1,(($id)?$poll['access']:$nlang)).'</select></td>
</tr>
<tr>
	<td><b>5. '.$lang['ap_type'].':</b></td>
	<td>
		<input type="radio" name="x_t" value="1"'.(($poll['type']==1)?' checked="checked"':'').' /> '.$lang['ap_max1'].' &nbsp;
		<input type="radio" name="x_t" value="2"'.(($poll['type']==2)?' checked="checked"':'').' /> '.$lang['ap_maxd'].'
	</td>
</tr>';
	CloseBox();

	#Odp.
	OpenBox($lang['answs'],1);
	echo '<tr><td align="center">'.$lang['ppuo'].'<br /><br /><div id="odp0"></div>';
	for($i=0;$i<$ile;++$i)
	{
		echo '<div id="odp'.$i.'">'.$lang['answ'].'
		<input name="x_seq[]" value="'.($i+1).'" size="1" />
		<input name="x_an[]" value="'.(($id)?$an[$i]['a']:'').'" size="30" />
		<input type="hidden" name="x_id[]" value="'.(($id)?$an[$i]['ID']:0).'" />
	</div>';
	}
	echo '
	<div id="odp'.$i.'"></div>'; ?>
	<br />
	<div align="center">
		<a href="javascript:Dodaj()"><b><?=$lang['addans']?></b></a>
	</div>
	<br />
	</td>
</tr>
<tr class="eth">
	<td><input type="submit" name="sav" value="<?=$lang['save']?>" /></td>
</tr>
<?php CloseBox() ?>
</form>