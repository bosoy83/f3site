<?php
if(iCMSa!=1 || !Admit('B')) exit;
require LANG_DIR.'adm_o.php';

#Info
$content->info($lang['bnrinfo'], array('?a=editad' => $lang['addbn']));

#Odczyt
$ads = $db->query('SELECT ID,gen,name,ison FROM '.PRE.'banners ORDER BY gen,name')->fetchAll(2);

#Do szablonu
$content->data['ads'] =& $ads;
?>
