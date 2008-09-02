<?php
if(iCMSa!=1 || !Admit('MM')) exit;
require 'cfg/mail.php';
require LANG_DIR.'adm_ml.php';

#Grupa i poziom
function Prepare($x)
{
	if(empty($_POST['lv']))
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
	include './cfg/emots.php';
	foreach($emodata as $e)
	{
		$x = str_replace($e[2], '<img src="'.URL.'img/emo/'.$e[1].'" title="'.$e[0].'" alt="'.$e[2].'" style="border: 0; vertical-align: middle" />', $x);
	}
	return $x;
}

#E-mail wy�.
if(!isset($cfg['mailon']))
{
	$content->info($lang['mailsd']); return 1;
}

#Wy�lij
elseif(isset($_POST['txt']))
{
	#Biblioteka
	require './lib/mail.php';
	$mail = new Mailer();
	$mail->setSender($_POST['from'],$cfg['mail']);
	$mail->topic = Clean($_POST['topic']);
	$mail->text  = nl2br($_POST['txt'])."\r\n\r\n-----\r\n".$lang['candis'];

	#Emoty
	if(isset($_POST['emot'])) $mail->text = RemoteEmots($mail->text);

	#HTML
	if(!isset($_POST['html'])) $mail->html = 0;

	#Lista u�ytkownik�w
	$lv = Prepare(explode(',', $_POST['lv']));
	$gr = Prepare(explode(',', $_POST['gr']));

	$res = $db->query('SELECT login,mail FROM '.PRE.'users WHERE mails=1');
	$res ->setFetchMode(3); //NUM
	$log = array();

	#Osobne
	if(isset($_POST['hard']))
	{
		foreach($res as $u)
		{
			if($mail->sendTo($_POST['rcpt'],$u[1])) $log[] = $lang['msent'].$u[0];
			else $log[] = $lang['msent'];
		}
	}
	#BCC
	else
	{
		foreach($res as $u)
		{
			$mail->addBlindCopy($u[0],$u[1]);
		}
		if($mail->sendTo($_POST['rcpt'],$cfg['mail'])) $log[] = $lang['msent'];
		else $log[] = $lang['mnsent'];
	}
	$content->info('<ul><li>'.join('</li><li>',$log).'</li></ul>');
}

#Ilo�� u�ytkownik�w
elseif(isset($_POST['next']))
{
	$ile = 0;
	$lv = Prepare($_POST['lv']);
	$gr = Prepare($_POST['gr']);
	if($lv && $gr)
	{
		$ile = db_count('users WHERE mails=1 AND lv IN('.$lv.') AND gid IN('.$gr.')');
	}
	if($ile==0) $content->info($lang['nousnd']);
}

#Formularz
if(isset($_POST['next']) && $ile>0)
{
	$content->addScript('./lib/editor.js'); //Edytor
	$content->addScript('./cache/emots.js'); //Emotki
	$content->addScript(LANG_DIR.'edit.js'); //J�zyk
	$content->data = array(
		'start' => false,
		'cfg'   => &$cfg,
		'level' => $lv,
		'group' => $gr,
		'title' => $lang['massl'].$ile
	);
}

#START
if(!$_POST)
{
	include './lib/user.php'; //Funkcje
	$content->info($lang['apmm1']);
	$content->data = array(
		'levels' => LevelList('all',1),
		'groups' => GroupList('all'),
		'start'  => true,
	);
}