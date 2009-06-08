<?php
if(iCMSa!=1 || !Admit('CFG')) exit;
require LANG_DIR.'admCfg.php';
$emodata = array();

#Zapis
if($_POST)
{
	#Dane
	$num = count($_POST['txt']);
	$js  = '';

	for($i=0; $i<$num; ++$i)
	{
		$emodata[] = array(Clean($_POST['dsc'][$i],20), Clean($_POST['file'][$i],80), Clean($_POST['txt'][$i],8));
		$js[] = '["'.$emodata[$i][0].'","'.$emodata[$i][1].'","'.$emodata[$i][2].'"]';
	}

	#Klasa zapisu
	require './lib/config.php';
	try {
		$f = new Config('emots');
		$f->var = 'emodata';
		if($f->save($emodata) && file_put_contents('./cache/emots.js','var emots=['.join(',',$js).']',2))
		{
			$content->info($lang['saved']);
		}
		unset($ile,$js,$x,$data,$f); $_POST = null;
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
$content->data['emo'] =& $emodata;
$content->data['files'] =& $files;