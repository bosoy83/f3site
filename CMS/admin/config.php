<?php
if(iCMSa!=1 || !Admit('CFG')) exit;

#Tytu³
$content->title = $lang['config'];

#Lista dzia³ów opcji
require LANG_DIR.'adm_conf.php';

#Odczyt opcji wtyczek
$res = $db->query('SELECT ID,name,img FROM '.PRE.'confmenu WHERE lang=1 OR lang="'.$nlang.'"');
$items = $res->fetchAll(2); //NUM

#Do szablonu
$content->file = 'config';
$content->data['plugins'] =& $items;
$content->addCSS('style/admin/config.css');