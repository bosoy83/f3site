<?php
if(iCMSa!=1 || !admit('CFG')) exit;
require LANG_DIR.'admCfg.php';
$emodata = array();

#Zapis
if($_POST)
{
	for($i=0, $num = count($_POST['txt']); $i<$num; ++$i)
	{
		$emodata[] = array(clean($_POST['dsc'][$i],20), clean($_POST['file'][$i],80), clean($_POST['txt'][$i],8));
	}

	#Klasa zapisu
	require './lib/config.php';
	try {
		$f = new Config('emots');
		$f->var = 'emodata';
		if($f->save($emodata) && file_put_contents('cache/emots.js','var emots='.json_encode($emodata),2))
		{
			$content->info($lang['saved']);
		}
		unset($ile,$emodata,$f); $_POST = null;
	}
	catch(Exception $e)
	{
		Info($lang['error'].$e);
	}
}

#Pliki
$files = '';
foreach(scandir('./img/emo') as $file)
{
	if($file[0] != '.') $files .= '<option>'.$file.'</option>';
}

#Ustawienia emotikon
if(!$_POST) include_once 'cfg/emots.php';

#Do szablonu
$content->addScript('lib/forms.js');
$content->data = array('emo' => &$emodata, 'files' => &$files);