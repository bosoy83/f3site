<?php
require('kernel.php');
header('Content-type: text/html; charset=iso-8859-2');

switch($_GET['co'])
{
 default: @include('plugins/'.$_GET['co'].'/http.php');
}
?>