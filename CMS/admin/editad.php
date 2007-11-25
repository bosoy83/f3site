<?php
if(iCMSa!=1 || !Admit('B')) exit;
$id=isset($_GET['id'])?$_GET['id']:0;

#Zapis
if($_POST)
{
	$ad=array(
	'name'=>Clean($_POST['x_n']),
	'code'=>$_POST['x_c'],
	'ison'=>(int)$_POST['x_on'],
	'gen' =>(int)$_POST['x_gid'] ); //Dane

	#Edytuj
	if($id)
	{
		$q=$db->prepare('UPDATE '.PRE.'banners SET gen=:gen, name=:name,
			ison=:ison, code=:code WHERE ID='.$id);
	}
	#Nowy
	else
	{
		$q=$db->prepare('INSERT INTO '.PRE.'banners (gen,name,ison,code)
			VALUES (:gen,:name,:ison,:code)');
	}

	#Zapis
	try
	{
		$q->execute($ad); Info($lang['saved']); return;
	}
	catch(PDOExtension $e)
	{
		Info($lang['error'].$e->errorInfo[0]);
	}
}
else
{
	if($id)
	{
		$ad=$db->query('SELECT * FROM '.PRE.'banners WHERE ID='.$id)->fetch(2);
		if(!$ad) { Info($lang['noex']); return; }
	}
	else
	{
		$ad=array('gen'=>1,'name'=>'','ison'=>1,'code'=>'');
	}
}

#Jêzyk i edytor JS
require($catl.'adm_o.php');
Init('lib/editor.js');
Init($catl.'edit.js');

#Form
echo '<form action="?a=editad'.(($id)?'&amp;id='.$id:'').'" method="post">';
OpenBox($lang[ (($id)?'editbn':'addbn') ],2);

echo '<tr>
	<td><b>1. '.$lang['name'].':</b></td>
	<td><input name="x_n" value="'.$ad['name'].'" size="30" maxlength="50" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['ap_acc'].':</b></td>
	<td>
		<input type="radio" name="x_on" value="1"'.(($ad['ison']==1)?' checked="checked"':'').' /> '.$lang['ap_ison'].' &nbsp;
		<input type="radio" name="x_on" value="2"'.(($ad['ison']==2)?' checked="checked"':'').' /> '.$lang['ap_isoff'].'</td>
</tr>
<tr>
	<td><b>3. '.$lang['genid'].':</b></td>
	<td><input name="x_gid" value="'.$ad['gen'].'" style="width: 50px" /></td>
</tr>
<tr>
	<td><b>4. '.$lang['htmlc'].':</b></td>
	<td>
		<textarea name="x_c" id="x_c" style="width: 90%" rows="8">'.Clean($ad['code']).'</textarea>
		<script type="text/javascript">var ed=new Editor("x_c")</script>
	</td>
</tr>
<tr>
	<td colspan="2" class="eth"><input type="submit" value="'.$lang['save'].'" /></td>
</tr>';
CloseBox();
?>
</form>