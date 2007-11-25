<?php
/*Wysy³anie PW*/
if(iCMS!=1) exit;

#Tablica b³êdów
$error=array();

#Kopia?
$pm_copy=isset($_POST['pm_s']) || (!$_POST && isset($_COOKIE[$cfg['c'].'pm_s']))?1:0;

#Wys³ane dane
if($_POST)
{
	#Do, Temat, BBCode
	$pm_to=Clean($_POST['pm_to']);
	$pm_th=empty($_POST['pm_th']) ? $lang['notopic'] : Clean($_POST['pm_th'],50,1);
	$pm_bbc=isset($cfg['bbc']) && isset($_POST['pm_bbc'])?1:0;

	#Tre¶æ
	if(isset($_POST['pm_txt'][20001]))
	{
		$error[]=$lang['pm_18'];
	}
	$pm_txt=Clean($_POST['pm_txt'],0,1);

	#ID odbiorcy $to_id
	$to_id=(int)db_get('ID','users',' WHERE login='.$db->quote($pm_to));
	if(!$to_id)
	{
		$error[]=$lang['pm_20'];
	}
	else
	{
		#Limit
		if(db_count('ID','pms',' WHERE owner='.$to_id)>=$cfg['pm_limit'])
		{
			$error[]=$lang['pm_21'];
		}
	}

	#Limit (zapis kopii)
	if(isset($_POST['pm_s']) && !$id)
	{
		if($pm_ile>=$cfg['pm_limit']) $error[]=$lang['pm_22'];
	}

	#ZAPISZ
	if(isset($_POST['save']) && !$error)
	{
		#Start
		$db->beginTransaction();

		#Edytuj
		if($id)
		{
			$q=$db->prepare('UPDATE '.PRE.'pms SET topic=:topic, usr=:usr, bbc=:bbc, txt=:txt WHERE ID='.$id);
			Info($lang['pm_24']);
		}
		#Nowa
		else
		{
			$q=$db->prepare('INSERT INTO '.PRE.'pms (topic,usr,owner,st,date,bbc,txt)
				VALUES (:topic,:usr,:owner,:status,"'.NOW.'",:bbc,:txt)');
			$db->exec('UPDATE '.PRE.'users SET pms=pms+1 WHERE ID='.$to_id);

			#Dane
			$q->bindValue(':owner',$to_id,1); //1 = INT
			$q->bindValue(':status',1,1);
		}
		#Dane
		$q->bindValue(':topic',$pm_th);
		$q->bindValue(':usr',UID,1);
		$q->bindValue(':bbc',$pm_bbc,1);
		$q->bindParam(':txt',$pm_txt);
		$q->execute();

		#Do wys³anych?
		if($pm_copy)
		{
			$q->bindValue(':usr',$to_id,1);
			$q->bindValue(':owner',UID,1);
			$q->bindValue(':status',4,1);
			$q->execute();
		}

		#OK
		try
		{
			$db->commit();
			if(!$id) Info($lang['pm_23']); else include('./mod/pms/message.php');
			return; 
		}
		catch(PDOException $e)
		{
			Info('Error: '.$e->errorInfo[0]);
		}
	}

	#Podgl±d
	else
	{
		OpenBox($pm_th,1);
		echo '<tr><td class="txt">';

		#BBCode
		if($pm_bbc)
		{
			require('./lib/bbcode.php');
			echo nl2br(Emots(ParseBBC($pm_txt)));
		}
		else
		{
			echo nl2br(Emots($pm_txt));
		}
		echo '</td></tr>';
		CloseBox();
	}
}

#Pobierz wiadomo¶æ
elseif($id)
{
	$res=$db->query('SELECT a.*,u.login FROM '.PRE.'pms a LEFT JOIN '.PRE.'users u
		ON a.usr=u.ID WHERE a.ID='.$id.' AND (a.owner='.UID.' OR (a.usr='.UID.' AND a.st=1))');
	$pm=$res->fetch(2); //ASSOC
	$res=null;

	#Dane
	if(!is_numeric($pm['usr'])) { Info($lang['noex']); return; }
	$pm_to=$pm['login'];
	$pm_txt=&$pm['txt'];
	$pm_th=(($pm['st']==2 && strpos($pm['topic'],$lang['re']!==0))?$lang['re']:'').$pm['topic'];
	$pm_bbc=isset($cfg['bbc'])?$pm['bbc']:0;
}
else
{
	#Odbiorca z GET
	$pm_to=isset($_GET['a'])?Clean($_GET['a'],50,1):'';
	$pm_txt='';
	$pm_th='';
	$pm_bbc=isset($cfg['bbc'])?1:0;
}

#B³êdy
if($error) Info(join('<br /><br />',$error));

#Formularz

#Nowa lub odpowied¼
if(!$id || $pm['st']==2)
{
	echo '<form action="?co=pms&amp;act=e" method="post">';
	OpenBox($lang['write'],2);
}
#Edytuj
else
{
	echo '<form action="?co=pms&amp;act=e&amp;id='.$id.'" method="post">';
	OpenBox($lang['editpm'],2);
}

echo '<tr>
	<td style="width: 25%"><b>1. '.$lang['pm_13'].':</b></td>
	<td><input name="pm_to" value="'.$pm_to.'" maxlength="30" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['topic'].':</b></td>
	<td><input name="pm_th" value="'.$pm_th.'" size="20" maxlength="40" /></td>
</tr>
<tr>
	<td><b>3. '.$lang['opt'].':</b></td>
	<td>
		<input type="checkbox" name="pm_s" onclick="setCookie(\''.$cfg['c'].'pm_s\',1,(this.checked)?3000:-3000)"'.(($pm_copy)?' checked="checked"':'').' /> '.$lang['pm_17'].'<br />
		<input type="checkbox" name="pm_bbc"'.(($cfg['bbc']!=1)?' disabled="disabled"': (($pm_bbc)?' checked="checked"':'') ).' /> '.$lang['pm_19'].'
	</td>
</tr>
<tr>
	<th colspan="2"><b>'.$lang['text'].'</b></th>
</tr>
<tr>
	<td colspan="2" align="center" id="pmbox">
		<textarea rows="15" id="pm_txt" name="pm_txt" style="width: 95%" cols="50">'.
		$pm_txt.'</textarea>';

	#BBCode
	if($cfg['bbc']==1)
	{
		Init($catl.'edit.js');
		Init('lib/editor.js');
		Init('cache/emots.js');
		echo '<script type="text/javascript">var pme=new Editor("pm_txt"); pme.bbcode=1; pme.Emots()</script>';
	}

  echo '</td>
</tr>
<tr>
	<td colspan="2" class="eth">
		<input type="submit" value="'.$lang['preview'].'" />
		<input type="submit" value="OK" name="save" />
	</td>
</tr>';
CloseBox()
?>
</form>