<?php if(iCMS!=1) exit;

$res = $db->query('SELECT ID,name FROM '.PRE.'pages WHERE access=1'.(UID ? ' OR access=3' : '').' ORDER BY name');
$url = url('page/');
$res->setFetchMode(3);

echo '<ul>';

foreach($res as $x)
{
	echo '<li><a href="'.$url.$x[0].'">'.$x[1].'</a></li>';
}

?></ul>