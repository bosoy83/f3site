<?php
if(iCMSa!=1) exit;
$id=isset($_GET['id'])?$_GET['id']:0;
require($catl.'f3s.php');

#Zapis
if($_POST)
{
	#Zmienne POST do zwyk³ych
	extract($_POST);
	
	$ile=count($xp_an);
	$del=array();

	#Ostatnie ID
	$db->beginTransaction();

	#Edycja
	if($id)
	{
		$q=$db->prepare('UPDATE '.PRE.'polls SET name=:name, q=:q, ison=:ison,
			type=:type, access=:access WHERE ID='.$id);
	}
	#Nowy
	else
	{
		$q=$db->prepare('INSERT INTO '.PRE.'polls (name,q,ison,type,num,access,date)
			VALUES (:name,:q,:ison,:type,0,:access,NOW())');
	}

	#Przypisz dane
	$q->bindParam(':name',Clean($xp_n));
	$q->bindParam(':q',Clean($xp_q));
	$q->bindParam(':ison',$xp_i,1);
	$q->bindParam(':type',$xp_t,1); //1 = INT
	$q->bindParam(':access',$xp_a);
	$q->execute();
	
	if(!$id) $id=$db->lastInsertId();
	
	#Odpowiedzi
	$q=$db->prepare('REPLACE INTO '.PRE.'answers ('.(($id)?'ID,':'').'IDP,seq,a,num)
		VALUES (:id,:idp,:seq,:a,0)');
	$q->bindValue(':idp',$id,1);

	for($i=0;$i<$ile;$i++)
	{
		#Dodaj, zmieñ
		if(is_numeric($xp_seq[$i]))
		{
			$q->bindValue(':id',$xp_id[$i],1);
			$q->bindValue(':seq',$xp_seq[$i],1);
			$q->bindValue(':a',Clean($xp_an[$i]));
			$q->execute();
		}
		#Usuñ
		else $del[]=(int)$xp_id[$i];
	}
	if($del) $db->exec('DELETE FROM '.PRE.'answers WHERE ID IN ('.join(',',$del).')');

	$db->commit();

	Info('<center>'.$lang['saved'].'<br /><br /><a href="?a=editpoll">'.$lang['addpoll'].'</a> | <a href="index.php?co=poll&amp;id='.$id.'">'.$xp_n.'</a></center>');
}

#Form
else
{
	#Odczyt
	if($id)
	{
		$res=$db->query('SELECT * FROM '.PRE.'polls WHERE ID='.$id);
		$poll=$res->fetch(2); //ASSOC
		$res=null;

		$res=$db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$id.' ORDER BY seq');
		$an=$res->fetchAll(3); //NUM
		$res=null;
		$ile=count($an);
	}
	else { $ile=3; }
	
?>
<script type="text/javascript">
<!--
ileusr=<?=$ile?>;
function Dodaj() { ii=ileusr+1; d("odp"+ileusr).innerHTML='<?=$lang['answ']?> <input name="xp_seq[]" size="1" value="'+ii+'" /> <input name="xp_an[]" size="30" /> <input type="hidden" name="xp_id[]" value="0" /><br /><div id="odp'+ii+'"></div>'; ileusr++; }
-->
</script>
<?php
echo '<form action="?a=editpoll'.(($id)?'&amp;id='.$id:'').'" method="post">';
OpenBox( (($id=='new')?$lang['addpoll']:$lang['editpoll']),2);
echo '
<tr>
	<td><b>1. '.$lang['name'].':</b></td>
	<td><input name="xp_n" maxlength="50" value="'.(($id)?$poll['name']:'').'" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['que'].':</b></td>
	<td><input name="xp_q" maxlength="150" value="'.(($id)?$poll['q']:'').'" style="width: 80%" /></td>
</tr>
<tr>
	<td><b>3. '.$lang['allowv'].'?</b></td>
	<td><input name="xp_i" type="radio" value="1"'.((!$id || $poll['ison']==1)?' checked="checked"':'').' /> '.$lang['yes'].' &nbsp;<input type="radio" name="xp_i" value="2"'.(($id && $poll['ison']==2)?' checked="checked"':'').' /> '.$lang['no'].' &nbsp;<input type="radio" name="xp_i" value="3"'.(($id && $poll['ison']==3)?' checked="checked"':'').' /> '.$lang['forregt'].'</td>
</tr>
<tr>
	<td><b>4. '.$lang['lang'].':</b></td>
	<td><select name="xp_a">'.ListBox('lang',1,(($id)?$poll['access']:$nlang)).'</select></td>
</tr>
<tr>
	<td><b>5. '.$lang['ap_type'].':</b></td>
	<td><input type="radio" name="xp_t" value="1"'.((!$id || $poll['type']==1)?' checked="checked"':'').' /> '.$lang['ap_max1'].' &nbsp;<input type="radio" name="xp_t" value="2"'.(($id && $poll['type']==2)?' checked="checked"':'').' /> '.$lang['ap_maxd'].'</td>
</tr>';
	CloseBox();

	#Odp.
	OpenBox($lang['answs'],1);
	echo '<tr><td align="center">'.$lang['ppuo'].'<br /><br /><div id="odp0"></div>';
	for($i=0;$i<$ile;++$i)
	{
		echo '<div id="odp'.$i.'">'.$lang['answ'].'
		<input name="xp_seq[]" value="'.($i+1).'" size="1" />
		<input name="xp_an[]" value="'.(($id)?$an[$i][1]:'').'" size="30" />
		<input type="hidden" name="xp_id[]" value="'.(($id)?$an[$i][0]:0).'" />
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
<?php
	CloseBox();
	echo '</form>';
}
?>
