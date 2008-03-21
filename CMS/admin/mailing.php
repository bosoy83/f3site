<?php
if(iCMSa!=1 || !Admit('MM')) exit;
require('cfg/mail.php');
require(LANG_DIR.'adm_ml.php');

#Grupa i poziom
function Prepare($x)
{
	if(empty($_POST['m_lv']))
	{
		return '';
	}
	else
	{
		return join(',',array_map('intval',$x));
	}
}

#Emoty zdalne
function RemoteEmots($x)
{
	include('./cfg/emots.php');
	foreach($emodata as $e)
	{
		$x=str_replace($e[2],'<img src="'.URL.'img/emo/'.$e[1].'" title="'.$e[0].'" alt="'.$e[2].'" style="border: 0; vertical-align: middle" />',$x);
	}
	return $x;
}

#E-mail wy³.
if($cfg['mailon']!=1)
{
	Info($lang['mailsd']);
}

#Wyœlij
elseif(isset($_POST['m_txt']))
{
	#Biblioteka
	require('./lib/mail.php');
	$mail=new Mailer();
	$mail->setSender($_POST['m_from'],$cfg['mail']);
	$mail->topic = Clean($_POST['m_topic']);
	$mail->text  = nl2br($_POST['m_txt'])."\r\n\r\n-----\r\n".$lang['candis'];
	
	#Emoty
	if(isset($_POST['m_emot'])) $mail->text=RemoteEmots($mail->text);

	#HTML
	if(!isset($_POST['m_html'])) $mail->html=0;

	#Lista u¿ytkowników
	$lv=Prepare(explode(',',$_POST['m_lv']));
	$gr=Prepare(explode(',',$_POST['m_gr']));

	$res=$db->query('SELECT login,mail FROM '.PRE.'users WHERE mails=1');
	$res->setFetchMode(3); //NUM
	$log=array();

	#Osobne
	if(isset($_POST['m_hard']))
	{
		foreach($res as $u)
		{
			if($mail->sendTo($_POST['m_rcpt'],$u[1])) $log[]=$lang['msent'].$u[0];
			else $log[]=$lang['msent'];
		}
	}
	#BCC
	else
	{
		foreach($res as $u)
		{
			$mail->AddBlindCopy($u[0],$u[1]);
		}
		if($mail->SendTo($_POST['m_rcpt'],$cfg['mail'])) $log[]=$lang['msent'];
		else $log[]=$lang['mnsent'];
	}
	Info(join('<br />',$log));
}

#Iloœæ u¿ytkowników
elseif(isset($_POST['next']))
{
	$ile=0;
	$lv=Prepare($_POST['m_lv']);
	$gr=Prepare($_POST['m_gr']);
	if($lv && $gr)
	{
		$ile=db_count('ID','users WHERE mails=1 && lv IN('.$lv.') && gid IN('.$gr.')');
	}
	if($ile==0) Info($lang['nousnd']);
}

#Formularz
if(isset($_POST['next']) && $ile>0 && $cfg['mailon']==1)
{
	?>
	<script type="text/javascript">
	<!--
	var mm=new Request('request.php?co=text','box');
	mm.method='POST';

	function Prev(step,o)
	{
		d('box').style.display='block'
		location.href='#aprev'
		mm.reset()
		mm.add('text',d('m_txt').value)
		mm.add('table',1)
		mm.add('o', ((d('m_html').checked)?'':'H') + ((d('m_emot').checked)?'E':'')+'L')
		mm.run()
	}
	-->
	</script>
	<?php
	Init('./lib/editor.js'); //Edytor
	echo '<a id="aprev"></a><div id="box" style="display: none"></div>
	<form action="?a=mailing" method="post">';
	
	OpenBox($lang['massl'].$ile,2);
	echo '
	<tr>
		<td style="width: 25%"><b>1. '.$lang['sender'].':</b></td>
		<td><input name="m_from" maxlength="50" size="30" value="'.$cfg['title'].'" /></td>
	</tr>
	<tr>
		<td><b>2. '.$lang['rcpt'].':</b></td>
		<td><input name="m_rcpt" maxlength="50" size="30" value="'.$lang['rcpt2'].$cfg['title'].'" /></td>
	</tr>
	<tr>
		<td><b>3. '.$lang['topic'].':</b></td>
		<td><input name="m_topic" maxlength="50" size="30" /></td>
	</tr>
	<tr>
		<td><b>4. '.$lang['opt'].':</b></td>
		<td>
			<input type="checkbox" name="m_html" id="m_html" checked="checked" /> HTML<br />
			<input type="checkbox" name="m_emot" id="m_emot" /> '.$lang['emot'].'<br />
			<input type="checkbox" name="m_hard" onclick="if(this.checked) if(!confirm(\''.$lang['hard2'].'\')) checked=0" /> '.$lang['hardmode'].'
		</td>
	</tr>
	<tr>
		<th colspan="2">'.$lang['text'].'</th>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<textarea name="m_txt" id="m_txt" style="width: 95%" rows="9" cols="30"></textarea>
			<script type="text/javascript">var ed=new Editor("m_txt"); ed.Emots('.Emots().')</script>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="eth">
			<input type="hidden" name="m_lv" value="'.$lv.'" />
			<input type="hidden" name="m_gr" value="'.$gr.'" />
			<input type="button" value="'.$lang['preview'].'" onclick="Prev()" />
			<input type="submit" value="OK" />
		</td>
	</tr>';
	CloseBox();
	echo '</form>';
}

#START
if(!$_POST && $cfg['mailon']==1)
{
	Info($lang['apmm1']); //Info
	include('./admin/inc/func_user.php'); //Funkcje

	echo '<form action="?a=mailing" method="POST">';
	OpenBox($lang['m1'],2);
	echo '
	<tr>
		<th>'.$lang['rights'].'</th>
		<th>'.$lang['groups'].'</th>
	</tr>
	<tr>
		<td align="center">
			<select name="m_lv[]" multiple="multiple">'.LevelList('all',1).'</select>
		</td>
		<td align="center">
			<select name="m_gr[]" multiple="multiple">'.GroupList('all').'</select>
		</td>
	</tr>
	<tr>
		<td class="pth" colspan="2" align="center">
			<input type="submit" value="OK &raquo;" name="next" />
		</td>
	</tr>';
	CloseBox();
	echo '</form>';
}
?>
