<?php
if(iCMSa!=1 || !Admit('AD') || !$_GET['id'] || $_GET['id']==UID) exit($lang['noex']);
require LANG_DIR.'rights.php';
require LANG_DIR.'profile.php';

#Zbiór praw
$set = array(
	'C'=>$lang['cats'], 		'IP'=>$lang['ipages'],
	'Q'=>$lang['polls'], 		0,
	'U'=>$lang['users'], 		'AD'=>$lang['ads'],
	'UG'=>$lang['groups'],	'LOG'=>$lang['log'],
	'MM'=>$lang['mailing'],	0,
	'CFG'=>$lang['conf'],		'CDB'=>$lang['dbcopy'],
	'NM'=>$lang['nav'],			'B'=>$lang['ads'],
	'PI'=>$lang['plugs'],		0,
	'DEL'=>$lang['aldel'],	'CM'=>$lang['urdc'],
	'FM'=>$lang['fmv'],			'FM2'=>$lang['fmv2']
);

#Wtyczki
$plug = array();
foreach($db->query('SELECT ID,text FROM '.PRE.'admmenu WHERE rights=1') as $tmp)
{
	$plug[$tmp['ID']]=$tmp['text'];
}

#Zapis
if($_POST)
{
	$new = array(); //Nowe prawa
	foreach($_POST['o'] as $key=>$val)
	{
		if(isset($set[$key]) || isset($plug[$key])) $new[]=$key;
	}
	$lv=(int)$_POST['xu_lv'];

	#Mo¿e zmieniæ w³a¶ciciela?
	if($user[UID]['lv']!=4)
	{
		if($lv>3 || $lv<0) exit('ERROR: Wrong level.');
		$q=' && lv!=4';
	}
	else { $q=''; }

	#Zapisz
	try
	{
		$db->exec('UPDATE '.PRE.'users SET adm="'.join('|',$new).'", lv='.$lv.' WHERE ID='.$id.$q);
		Info($lang['saved']);
		return;
	}
	catch(PDOException $e)
	{
		Info($lang['error'].$e->errorInfo[0]);
	}
}

/* FORM */

#Funkcje
require('./lib/user.php');
require('./lib/categories.php');

#Pobierz
$adm=$db->query('SELECT login,lv,adm FROM '.PRE.'users WHERE ID='.$id.
	((LEVEL!=4)?' && lv!=4':'')) -> fetch(3);

#Prawa do kategorii
//$adm=$db->query('SELECT ID,name FROM '.PRE.'cats LEFT JOIN '.PRE.'xses');

if(empty($adm[0])) exit('Cannot read user data!');
$prv=explode('|',$adm[2]); //Prawa

echo '<form action="?a=editadm&amp;id='.$id.'" method="post">';
OpenBox($lang['editadm'].': '.$adm[0],2);

echo '
<tr valign="top">
	<td style="padding: 4px" style="overflow: scroll; width: 50%; max-height: 400px">
		<div style="overflow-y: scroll; max-height: 500px">
		<fieldset>
		<legend>'.$lang['global'].'</legend>';

		#Podstawowe
		foreach($set as $k=>$v)
		{
			if($v===0)
				echo '<br />';
			else
				echo '<input type="checkbox" id="o_'.$k.'" name="o['.$k.']"'.
				((in_array($k,$prv))?' checked="checked"':'').' /> <label for="o_'.$k.'">'.$v.'</label><br />';
		}
		echo '</fieldset>

		<fieldset>
		<legend>'.$lang['plugs'].'</legend>';
		
		#Prawa
		foreach($res as $m)
		{
			echo '<input type="checkbox" id="o_'.$m[0].'" name="o['.$m[0].']"'.((in_array($m[0],$prv))?' checked="checked"':'').' /> <label for="o_'.$m[0].'">'.$m[0].'</label><br />';
		}
		echo '</div></fieldset>
	</td>
	<td style="overflow: auto; max-height: 500px; width: 50%">
		<fieldset>
		<legend>'.$lang['cats'].'</legend>
		:: Do zrobienia: prawa do kategorii ::
		</fieldset>
	</td>
</tr>
<tr>
	<td colspan="2" class="eth"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
CloseBox();
?>
</form>
