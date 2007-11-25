<?php
require('cfg/mail.php');
if(iCMSa!=1 || !Admit('MM') || $cfg['mailon']!=1) exit;
require($catl.'adm_ml.php');
?>