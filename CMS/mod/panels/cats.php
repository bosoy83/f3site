<div style="white-space: nowrap; overflow: hidden"><?php if(iCMS!=1) exit;

$res = $db->query('SELECT ID,name FROM '.PRE.'cats WHERE sc=0
AND (access=1 OR access="'.LANG.'") ORDER BY name');

$cat = array();
$url = url('');
$res->setFetchMode(3);

foreach($res as $x)
{
	echo '<a href="'.$url.$x[0].'" class="cat">'.$x[1].'</a><br />';
}

if(admit('C'))
{
	echo '<a href="'.url('editCat', '', 'admin').'" class="cat">'.$lang['add'].'...</a>';
}

?></div>