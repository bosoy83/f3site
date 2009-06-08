<?php /* Grupy u¿ytkowników */
if(iCMS!=1) exit;

#Tytu³ strony
$content->title = $lang['groups'];

#Do³±cz
if(isset($_POST['join']) && is_numeric($_POST['join']))
{
	if(LOGD && db_count('groups WHERE '.(LEVEL>3 ? '' : 'opened=1 AND ').'access!=2 AND ID='.$_POST['join']))
	{
		$db->exec('UPDATE '.PRE.'users SET gid='.$_POST['join'].' WHERE ID='.UID);
		$user[UID]['gid'] = $_POST['join'];
	}
}

#Pobierz
$res = $db->query('SELECT ID,name,dsc,opened FROM '.PRE.'groups WHERE access=1 OR access="'.$nlang.'"');
$res -> setFetchMode(3);
$gro = array();
$may = array();

foreach($res as $x)
{
	$gro[] = array(
		'title' => $x[1],
		'desc'  => nl2br($x[2]),
		'url'   => '?co=users&id='.$x[0]
	);

	#Je¶li mo¿e do³±czyæ
	if($x[0] != $user[UID]['gid'] && ($x[3] OR LEVEL > 3)) $may[$x[0]] = $x[1];
}

#Do szablonu
$content->data = array(
	'groups' => $gro, //ASSOC
	'join'   => $may
);
$res=null;