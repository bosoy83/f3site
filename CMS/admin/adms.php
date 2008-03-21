<?php
if(iCMSa!=1 || !Admit('AD')) exit;
require(LANG_DIR.'rights.php');

#Odczyt
$res=$db->query('SELECT ID,login,adm FROM '.PRE.'users WHERE lv>1');
$res->setFetchMode(3); //NUM

#Info
Info($lang['ap_iadms']);
OpenBox($lang['admins'],3);

/* PLIK NIEDOKOÑCZONY */

echo '
';

$ile=0;
foreach($res as $admin)
{
	echo '';
}

$res=null;
CloseBox();
?>
