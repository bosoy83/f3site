<?php
if(iCMSa!=1 || !Admit('NM')) exit;
require($catl.'adm_o.php');
$id=isset($_GET['id'])?$_GET['id']:0;

#Zapis
if($_POST)
{	
	$db->beginTransaction();
	
	#Edytuj
	if($id && !isset($_POST['savenew']))
	{
		$q=$db->prepare('UPDATE '.PRE.'menu SET text=:title, disp=:disp, menu=:menu,
			type=:type, img=:img, value=:value WHERE ID='.$id);
		$db->exec('DELETE FROM '.PRE.'mitems WHERE menu='.$id);
	}
	#Nowy()
	else
	{
		$q=$db->prepare('INSERT INTO '.PRE.'menu (seq,text,disp,menu,type,img,value)
			VALUES ('.(db_count('ID','menu')+1).',:title,:disp,:menu,:type,:img,:value)');
		$id=db_id();
	}
	#Dane
	$q->bindValue(':title',$_POST['m_tit']);
	$q->bindValue(':disp',Clean($_POST['m_vis']));
	$q->bindValue(':menu',$_POST['m_page'],1);
	$q->bindValue(':type',$_POST['m_type'],1); //1 = INT
	$q->bindValue(':value',$_POST['m_txt']);
	$q->bindValue(':img',Clean($_POST['m_img']));
	$q->execute();
	
	#Linki
	if($_POST['m_type']==3)
	{
		#ID
		if(!$id) $id=$db->lastInsertId();

		#Zapytanie
		$q=$db->prepare('INSERT INTO '.PRE.'mitems (seq,menu,text,url,nw)
			VALUES (?,?,?,?,?)');

		$ile=count($_POST['i_seq']);
		for($i=0;$i<$ile;++$i)
		{
			if(!empty($_POST['i_seq'][$i]))
			{
				$q->bindValue(1,$_POST['i_seq'][$i],1);
				$q->bindValue(2,$id,1);
				$q->bindValue(3,$_POST['i_txt'][$i]);
				$q->bindValue(4,Clean($_POST['i_adr'][$i]));
				$q->bindValue(5, (isset($_POST['i_nw'][$i]))?1:0 ,1);
				$q->execute();
			}
		}
	}
	$db->commit();
	
	#Generuj menu
	include('./admin/inc/mcache.php');
	RenderMenu();

	#Lista
	unset($q,$id);
	$_POST=array();
	include('./admin/nav.php');
}

else
{
	#Odczyt
	if($id)
	{
		$res=$db->query('SELECT * FROM '.PRE.'menu WHERE ID='.$id);
		$m=$res->fetch(2);
		$res=null;
		if(!isset($m['ID'])) exit('No such menu ID.');
	}
?>

<script type="text/javascript">
<!--
ile=0;

function Dodaj(name,adr,nw)
{
	var div=document.createElement('div');
	div.style.margin='8px';
	div.id='d'+ ++ile;

	div.innerHTML=['<input size="1" name="i_seq[]" value="'+ile+'" /> &nbsp;',
	'<?=$lang['title']?>: <input name="i_txt[]" value="'+name+'" /> &nbsp;',
	'<?=$lang['adr']?>: <input name="i_adr[]" value="'+adr+'" /> ',
	'<input type="checkbox" name="i_nw['+(ile-1)+']" '+((nw==1)?' checked="checked"':'')+'/>'].join('');

	d('box').appendChild(div);
}
-->
</script>

<?php
echo '<form action="?a=editnav'.(($id)?'&amp;id='.$id:'').'" method="post">';
OpenBox((($id)?$lang['ap_navbe']:$lang['ap_navbn']),2);
echo '
<tr>
	<td width="38%"><b>1. '.$lang['ap_navbtit'].'</b></td>
	<td><input name="m_tit" maxlength="50" value="'.(($id)?Clean($m['text']):'').'" /></td>
</tr>
<tr>
	<td><b>2. '.$lang['ap_acc'].':</b></td>
	<td>
		<select name="m_vis">
			<option value="1">'.$lang['ap_isaon'].'</option>'
			.ListBox('lang',1, (($id)?$m['disp']:'') )
			.'<option value="2"'.(($id && $m['disp']==2)?' selected="selected"':'').'>'.$lang['ap_isahid'].'</option>
		</select>
	</td>
</tr>
<tr>
	<td><b>3. '.$lang['ap_page'].':</b></td>
	<td>
		<input type="radio" value="1" name="m_page"'.((!$id || $m['menu']==1)?' checked="checked"':'').' /> '.$lang['ap_leftp'].' &nbsp;
		<input type="radio" value="2" name="m_page"'.(($id && $m['menu']==2)?' checked="checked"':'').' /> '.$lang['ap_rightp'].'</td>
</tr>
<tr>
	<td><b>4. '.$lang['ap_mtype'].':</b></td>
	<td>
		<input type="radio" value="1" name="m_type"'.(($id && $m['type']==1)?' checked="checked"':'').' /> '.$lang['ap_txt'].' &nbsp;
		<input type="radio" value="2" name="m_type"'.(($id && $m['type']==2)?' checked="checked"':'').' /> '.$lang['ap_file'].' &nbsp;
		<input type="radio" value="3" name="m_type"'.((!$id || $m['type']==3)?' checked="checked"':'').' /> '.$lang['ap_urls'].'</td>
</tr>
<tr>
	<td>
		<b>5. '.$lang['bgimg'].':</b><br />
		<small>'.$lang['0off'].'</small>
	</td>
	<td>
		<input id="m_img" name="m_img" maxlength="200" value="'.(($id)?$m['img']:0).'" />'.((Admit('FM'))?' <input type="button" value="'.$lang['files'].' &raquo;" onclick="Okno(\'?x=fm&amp;ff=m_img\',580,400,150,150)" />':'').'
	</td>
</tr>
<tr>
	<td>
		<b>6. '.$lang['ap_txtfile'].'</b><br />
		<small>'.$lang['ap_nmlink'].'</small>
	</td>
	<td>
		<textarea rows="4" name="m_txt" cols="45">'.(($id)?Clean($m['value']):'').'</textarea>
	</td>
</tr>
<tr>
	<th colspan="2"><b>'.$lang['ap_navbody'].'</b></td>
</tr>
<tr>
	<td colspan="2" align="center"><div id="box">';

	#ID
	if($id && $m['type']==3)
	{
		$res=$db->query('SELECT text,url,nw FROM '.PRE.'mitems WHERE menu='.$id.' ORDER BY seq');
		$res->setFetchMode(3);
		$s='<script type="text/javascript">';

		#Linki
		foreach($res as $i)
		{
			$s.='Dodaj("'.Clean($i[0]).'","'.$i[1].'",'.$i[2].');';
		}
		echo $s.'</script>';
	}
	echo '</div><br />
	<center>
  <a href="javascript:Dodaj(\'\',\'http://\',0)"><b>'.$lang['ap_navadd'].'</b></a>
  <br /><br />
  <input type="checkbox" checked="checked" disabled="disabled" /> = '.$lang['ap_nwn'].'
	</center>
	<br />'.$lang['ap_whdelmi'].'
	</td>
</tr>
<tr class="eth">
	<td colspan="2">
		<input type="submit" value="'.$lang['save'].'" />
		<input type="submit" value="'.$lang['savenew'].'" name="savenew" />
	</td>
</tr>';
CloseBox();
}
?>
