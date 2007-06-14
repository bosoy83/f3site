<?php
Info(file_get_contents($catl.'admintro.php').(($user[UID]['lv']==3 && is_dir('install'))?'<br /><br />'.$lang['warninst']:'').'<br /><br />'.$_SERVER['SERVER_SOFTWARE'].'<br />'.$_SERVER['SERVER_SIGNATURE'].'<br /><br /><div align="center"><b>'.$lang['add'].'</b>: <a href="?a=editart">'.$lang['arts'].'</a> | <a href="?a=editfile">'.$lang['files'].'</a> | <a href="?a=editimg">'.$lang['img'].'</a> | <a href="?a=editlink">'.$lang['links'].'</a> | <a href="?a=editnew">'.$lang['news'].'</a></div>');
?>
