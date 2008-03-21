<?php
if(iCMS!=1 || !Admit('IP')) exit;
require(LANG_DIR.'adm_o.php');

#Zapis
if($_POST)
{
	#Dane, OPCJE ('opt'): 1 - BR, 2 - emoty, 4 - w tabeli, 8 - komentarze, 16 - PHP
	$ip=array(
	'text'	=>&$_POST['x_t'],
	'access'=>Clean($_POST['x_a']),
	'name'	=>Clean($_POST['x_n']),
	'opt' 	=>(isset($_POST['x_br'])?1:0)+(isset($_POST['x_e'])?2:0)+(isset($_POST['x_t'])?4:0)+
		(isset($_POST['x_c'])?8:0)+(isset($_POST['x_php'])?16:0) );

	#Edycja
	if($id)
	{
		$q=$db->prepare('UPDATE '.PRE.'pages SET name=:name,access=:access,opt=:opt,text=:text WHERE ID='.$id);
	}
	#Nowa strona
	else
	{
		$q=$db->prepare('INSERT INTO '.PRE.'pages (name,access,opt,text) VALUES (:name,:access,:opt,:text)');
	}

	#OK
	try
	{
		$q->execute($ip);
		Info($lang['saved'].' <a href="index.php?co=page&amp;id='.
			(($id)?$id:$db->lastInsertId()).'">'.$lang['goto'].' &raquo;</a>');
		return;
	}
	catch(PDOException $e)
	{
		Info($lang['error'].$e->errorInfo[0]);
	}
}

#FORM
else
{
	if($id)
	{
		$ip=$db->query('SELECT * FROM '.PRE.'pages WHERE ID='.$id)->fetch(2);
		if(!$ip)
		{
			Info($lang['error']); return;
		}
	}
	else
	{
		$ip=array('name'=>'','access'=>1,'text'=>'','opt'=>13);
	}
}

#Biblioteki JS
Init('lib/editor.js');
Init(LANG_DIR.'edit.js');
Init('cache/emots.js');

echo '<form action="?a=editpage'.(($id)?'&amp;id='.$id:'').'" method="post">';
OpenBox($lang[ (($id)?'ap_editp':'ap_addp') ],2);

echo '<tr>
	<td style="width: 35%"><b>1. '.$lang['name'].':</b></td>
	<td><input name="x_n" value="'.$ip['name'].'" maxlength="50" size="50" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['ap_acc'].':</b></td>
	<td><select name="x_a">
		<option value="1">'.$lang['ap_isaon'].'</option>
		<option value="3"'.(($ip['access']==3)?' selected="selected"':'').'>'.$lang['forregt'].'</option>
		<option value="2"'.(($ip['access']==2)?' selected="selected"':'').'>'.$lang['ap_isaoff'].'</option>
	</select></td>
</tr>
<tr>
	<td><b>3. '.$lang['opt'].':</b></td>
	<td>
		<input type="checkbox" name="x_c"'.(($ip['opt']&8)?' checked="checked"':'').' /> '.$lang['comms'].'<br />
		<input type="checkbox" name="x_e"'.(($ip['opt']&2)?' checked="checked"':'').' /> '.$lang['emoon'].'<br />
		<input type="checkbox" name="x_br"'.(($ip['opt']&1)?' checked="checked"':'').' /> '.$lang['br'].'<br />
		<input type="checkbox" name="x_t"'.(($ip['opt']&1)?' checked="checked"':'').' /> '.$lang['ap_epttab'].'<br />
		<input type="checkbox" name="x_php"'.(($ip['opt']&16)?' checked="checked"':'').' /> PHP
	</td>
</tr>';
CloseBox();

#Tre¶æ
OpenBox($lang['text'],1);
echo '<tr>
	<td colspan="2" align="center">
		<textarea name="x_t" id="x_t" cols="60" rows="17" style="width: 98%">'.Clean($ip['text']).'</textarea>
	</td>
</tr>
<tr>
	<td colspan="2" class="eth">
		<input type="submit" name="preview" value="'.$lang['preview'].'" />
		<input type="submit" value="'.$lang['save'].'" name="sav" />
	</td>
</tr>';
CloseBox();
?>
</form>
<script type="text/javascript">
var ed=new Editor('x_t');
ed.Emots();
</script>
