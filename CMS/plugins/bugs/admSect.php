<?php
if(iCMSa!=1) exit;

#Zapis sekcji
if($_POST)
{
	$all = array();
	$fix = array();
	$add = array();
	foreach($_POST['sect'] as $seq=>$title)
	{
		if(isset($_POST['id'][$seq]))
		{
			$fix[] = array($seq, Clean($title), $_POST['id'][$seq]);
			$all[] = (int)$_POST['id'][$seq];
		}
		else
		{
			$add[] = array($seq, Clean($title));
		}
	}
	#Usuñ stare
	$db->beginTransaction();
	$db->exec('DELETE FROM '.PRE.'bugsect WHERE ID NOT IN('.join(',', $all).')');

	#Zmieñ istniej¹ce
	if($fix)
	{
		$q = $db->prepare('UPDATE '.PRE.'bugsect SET seq=:seq, title=:title WHERE ID=:id');
		foreach($fix as &$x) $q -> execute($x);
	}

	#Nowe rekordy
	if($add)
	{
		$q = $db->prepare('INSERT INTO '.PRE.'bugsect (seq,title) VALUES (:seq,:title)');
		foreach($add as &$x) $q -> execute($x);
	}
	$db->commit();
	Header('Location: '.URL.'adm.php?a=bugs');
	unset($fix,$add,$all,$x);
}

#Pobierz sekcje - FETCH_ASSOC
$all = $db->query('SELECT * FROM '.PRE.'bugsect ORDER BY seq') -> fetchAll(2);

#Szablon
$content->addScript('lib/forms.js');
$content->file = 'adminSect';
$content->data = array('section' => &$all);