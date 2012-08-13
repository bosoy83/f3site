<?php
if(iCMSa!=1 || !admit('CFG')) exit;
require LANG_DIR.'admCfg.php';

#Action: save
if($_POST)
{
	$num = count($_POST['bad']);
	$words1 = Array();
	$words2 = Array();

	for($i=0; $i<$num; ++$i)
	{
		$words1[] = substr($_POST['bad'][$i],0,50);
		$words2[] = substr($_POST['good'][$i],0,50);
	}

	#Load saver class
	require './lib/config.php';
	try
	{
		$f = new Config('words');
		$f->add('words1', $words1);
		$f->add('words2', $words2);
		$f->save();
		$content->info($lang['saved']);
	}
	catch(Exception $e)
	{
		$content->info($lang['error'].$e);
	}
}

#Action: edit
else
{
	include './cfg/words.php';
	$num  = count($words1);
}

#FORM
$word = array();
for($i=0; $i<$num; ++$i)
{
	$word[] = array(clean($words1[$i]), clean($words2[$i]));
}

#Template
$content->script('lib/forms.js');
$content->info( isset($cfg['censor']) ? $lang['wordInfo'] : $lang['wordOff'] );
$content->add('censor', array('word' => &$word));
