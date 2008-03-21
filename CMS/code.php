<?php
require('kernel.php');
if($cfg['imgsec']!=1) exit;
header('Content-type: image/png');
$num=mt_rand(10,300).$time['seconds'];
$_SESSION['code']=$num;
$img=imagecreate(80,25);
imagecolorallocate($img,250,250,245);
$color=imagecolorallocate($img,10,50,80);
imagestring($img,5,mt_rand(10,23),4,$num,$color);
imagepng($img);
imagedestroy($img);
exit;
?>
