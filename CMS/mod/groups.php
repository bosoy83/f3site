<?php /* Grupy u�ytkownik�w */
if(iCMS!=1) exit;

#Tytu� strony
$content->title = $lang['groups'];

#Do��cz
/* Niepiezpieczne, kiedy akcj� wywo�ujemy zmienn� z GET
if(isset($_GET['join_now']) && $_GET['id'])
{
	if(LOGD==1 && db_count('ID','groups WHERE opened=1 && access!=3 && ID='.$_GET['id'])==1)
	{
		$db->exec('UPDATE '.PRE.'users SET gid='.$_GET['id'].' WHERE ID='.UID);
	}
}*/

#Pobierz
$res = $db->query('SELECT ID,name,dsc,opened FROM '.PRE.'groups WHERE access=1 OR access="'.$nlang.'"');

#Do szablonu
$content->data['groups'] = $res->fetchAll(2); //ASSOC

/* Mo�e niepotrzebne? W�a�ciwie potrzebne tylko do nl2br (szablony ju� obs�uguj�) i URL
$res -> setFetchMode(3); //NUM
$groups = array();

#Lista
foreach($res as $g)
{
	$groups[] = array(
		'desc'  => nl2br($g[2]),
		'url'   => '?co=users&amp;id='.$g[0],
		'title' => $g[1],
		'opened'=> $g[3]
	);
}

#Do szablonu
$content->data['groups'] =& $groups; //ASSOC
*/

$res=null;
?>
