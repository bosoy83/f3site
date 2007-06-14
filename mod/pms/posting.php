<?php
if(iCMS!=1 || isset($_REQUEST['pm'])) exit;

#Tablica b³êdów
$error=array();

#Wys³ane dane
if($_POST)
{
	#Dane
	$pm_to=TestForm($_POST['pm_to'],1,1,0);
	$pm_th=TestForm($_POST['pm_th'],1,1,0);
	if(empty($pm_th)) $pm_th=$lang['notopic'];
	if(strlen($_POST['pm_txt'])>20000)
	{
		$error[]=$lang['pms_18'];
		$pm_txt=&$_POST['pm_txt'];
	}
	else
	{
		$pm_txt=TestForm($_POST['pm_txt'],1,0,0);
	}

	#Odbiorca
	$odb=db_read('ID','users',1,'get',' WHERE login="'.db_esc($pm_to).'"');
	if(empty($odb))
	{
		$error[]=$lang['pms_20'];
	}

	#Limit
	if(db_count('ID','pms',' WHERE owner='.$odb)>=$cfg['pm_limit'])
	{
		$error[]=$lang['pms_21'];
	}
}

#Pobierz wiadomo¶æ
elseif($id!='')
{
	db_read('a.*,u.login','pms a LEFT JOIN '.PRE.'users u ON a.usr=u.ID','pm','oa',' WHERE a.ID='.$id.' AND (a.owner='.UID.' OR (a.usr='.UID.' AND a.st=1))');

	#Odbiorca
	if(!is_numeric($pm['usr'])) exit('ERR!');
	$pm_to=$pm['login'];

	#Temat
	if($_GET['pm_r']) $pm['topic']=$lang['re'].$pm['topic'];
}

else
{
	#Odbiorca z GET
	$pm_to=($_GET['a'])?TestForm($_GET['a'],1,1,0,50):'';
}

#Limit
if($_POST['pm_s'] && $id=='')
{
	if($pm_ile>=$cfg['pm_limit']) $error[]=$lang['pms_22'];
}

#Zapis
if($_POST['sav'] && !$error)
{
	#Nowa
	if($id=='')
	{
		db_q('INSERT INTO {pre}pms (topic,usr,owner,st,date,bbc,txt) VALUES ("'.db_esc($pm_th).'",'.UID.',"'.$odb.'",1,"'.strftime('%Y-%m-%d %H:%M:%S').'",'.(($_POST['pm_b'])?1:2).',"'.db_esc(Words($pm_txt)).'")');
		db_q('UPDATE {pre}users SET pms=pms+1 WHERE ID='.$odb);
	
		#Do wys³anych?
		if($_POST['pm_s'])
		{
			db_q('INSERT INTO {pre}pms (topic,usr,owner,st,date,bbc,txt) VALUES ("'.db_esc($pm_th).'","'.$odb.'",'.UID.',4,"'.strftime('%Y-%m-%d %H:%M:%S').'",'.(($_POST['pm_b'])?1:2).',"'.db_esc(Words($pm_txt)).'")');
		}
		Info($lang['pms_23']);
	}

	#Edycja
	else
	{
		db_q('UPDATE {pre}pms SET topic="'.db_esc($pm_th).'", usr="'.$odb.'", bbc='.(($_POST['pm_b'])?1:2).', txt="'.db_esc(Words($pm_txt)).'" WHERE ID='.$id);
		Info($lang['pms_24']);
	}
}

#Podgl±d
if($_POST['preview'] && !$error)
{
	cTable($pm_th,1);
	echo '<tr><td class="txt">';
 
	#BBCode
	if($_POST['pm_b'] && $cfg['bbc']==1)
	{
		require_once('inc/bbcode.php');
		echo Emots(nl2br(ParseBBC(Words($pm_txt))));
	}
	else
	{
		echo Emots(nl2br(Words($pm_txt)));
	}
	echo '</td></tr>';
	eTable();
}

#B³êdy
if($error) Info(join('<br /><br />',$error));

#Formularz
if(!$_POST['sav'] || $error)
{
	#Nowa lub odpowied¼
	if($id=='' || $_GET['pm_r'])
	{
		echo '<form action="?co=pms&amp;act=e" method="post">';
		cTable($lang['write'],2);
	}
	#Edytuj
	else
	{
		echo '<form action="?co=pms&amp;act=e&amp;id='.$id.'" method="post">';
		cTable($lang['editpm'],2);
	}
	
	echo '
<tr>
	<td style="width: 25%"><b>1. '.$lang['pms_13'].':</b></td>
	<td><input name="pm_to" value="'.$pm_to.'" maxlength="30" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['topic'].':</b></td>
	<td><input name="pm_th" value="'.(($_POST)?$pm_th:$pm['topic']).'" size="20" maxlength="40" /></td>
</tr>
<tr>
	<td><b>3. '.$lang['opt'].':</b></td>
	<td>
		<input type="checkbox" onclick="setCookie(\''.$cfg['c'].'pm_s\',1,(this.checked)?3000:-3000)" name="pm_s"'.((($_POST && !$_POST['pm_s']) || (!$_POST && $_COOKIE[$cfg['c'].'pm_s']!=1))?'':' checked="checked"').' /> '.$lang['pms_17'].'<br />
		<input type="checkbox" name="pm_b"'.(($cfg['bbc']!=1)?' disabled="disabled"': ((!$_POST['pm_b'])?' checked="checked"':'') ).' /> '.$lang['pms_19'].'
	</td>
</tr>
<tr>
	<th colspan="2"><b>'.$lang['text'].'</b></th>
</tr>
<tr>
	<td colspan="2" style="padding: 3px" align="center">';

	#Edytor
	if($cfg['bbc']==1)
	{
		require_once('inc/btn.php');
		echo '<div style="padding: 3px">';
		Colors('pm_txt',2);
		FontBtn('pm_txt',2);
		echo '</div>';
	}
	echo '<textarea rows="10" id="pm_txt" name="pm_txt" cols="50">'.
		(($_POST)?$pm_txt:(($_GET['pm_r']==1)?'[quote]'.$pm['txt'].'[/quote]':$pm['txt'])).
		'</textarea>';
	
  if($cfg['bbc']==1)
	{
		echo '<div style="padding: 3px">';
		Btns(2,2,'pm_txt');
		echo '</div>';
	}
  include_once('cfg/emots.php');
	
	#Emotikony
  $ile=count($emodata);
  if($ile>0)
  {
		echo '<div style="padding: 3px">';
		for($i=0;$i<$ile;$i++)
		{
			echo '<img src="img/emo/'.$emodata[$i][1].'" style="cursor: pointer" title="'.$emodata[$i][0].'" alt="'.$emodata[$i][0].'" onclick="BBC(\'pm_txt\',\''.$emodata[$i][2].'\',\'\')" /> ';
		}
		echo '</div>';
  }
  echo '</td>
</tr>
<tr>
	<td colspan="2" class="eth"><input type="submit" value="OK" name="sav" /> <input type="submit" value="'.$lang['preview'].'" name="preview" /></td>
</tr>';
 eTable();
 echo '</form>';
}
?>
