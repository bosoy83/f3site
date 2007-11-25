<?php
#Info
Info( str_replace('%name',$cfg['title'],file_get_contents($catl.'admintro.php'))
	.(($user[UID]['lv']==4 && is_dir('install'))?'<br /><br />'.$lang['warninst']:'')
	.'<br /><br />'.$_SERVER['SERVER_SOFTWARE'].'<br />'.$_SERVER['SERVER_SIGNATURE']
	.'<br />OS: '.$_ENV['OS'] );
?>
