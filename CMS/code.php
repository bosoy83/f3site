<?php
define('iCMS',1);
require 'kernel.php';
if($cfg['captcha']!=1) exit;

#Jako PNG
header('Content-type: image/png');

#Losujemy liczbê
$_SESSION['code'] = $num = mt_rand(10,300).date('s');

#Generuj obrazek
$img = imagecreate(80,25);
imagecolorallocate($img,250,250,245);
$color = imagecolorallocate($img,10,50,80);
imagestring($img,5,mt_rand(10,23),4,$num,$color);
imagepng($img);
imagedestroy($img);
exit;