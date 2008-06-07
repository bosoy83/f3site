<?php /* Wyœwietl komentarze */
if(iCMS!=1 OR CT=='CT') return;

#Podzia³ na strony?
if($cfg['commNum']!=0)
{
	#Strona
	if(isset($_GET['pc']) && $_GET['pc']>1)
	{
		$PAGE = $_GET['pc'];
		$ST = ($PAGE-1)*$cfg['commNum'];
	}
	else
	{
		$PAGE = 1;
		$ST = 0;
	}
	$TOTAL = db_count('ID','comms WHERE TYPE='.CT.' AND CID='.$id);
}
else
{
	$TOTAL = null;
}

$comm = array();
$form = array();

#Pobierz
if($TOTAL !== 0)
{
	#SQL
	$res = $db->query('SELECT c.ID,c.name,c.author,c.ip,c.date,c.text,u.login
		FROM '.PRE.'comms c LEFT JOIN '.PRE.'users u ON c.author=u.ID AND c.guest!=1
		WHERE c.TYPE='.CT.' AND c.CID='.$id.
		(($cfg['commSort']==2) ? '' : ' ORDER BY c.ID DESC').
		(($cfg['commNum']!=0) ? ' LIMIT '.$ST.','.$cfg['commNum'] : ''));

	$res->setFetchMode(3);

	#BBCode?
	if(isset($cfg['bbcode'])) include_once('./lib/bbcode.php');

	#Prawa do edycji i usuwania
	$rights = Admit('CM');

	foreach($res as $x)
	{
		$comm[] = array(
			'text' => isset($cfg['bbcode']) ? nl2br(Emots(BBCode($x[5]))) : nl2br(Emots($x[5])),
			'ip'   => $x[3],
			'title'=> $x[1],
			'date' => genDate($x[4]),
			'user' => $x[6] ? $x[6] : $x[2],
			'rights' => $rights,
			'edit_url' => '?co=comm&amp;id='.$x[0],
			'user_url' => $x[6] ? ((MOD_REWRITE) ? '/user/'.$x[2] : '?co=user&amp;id='.$x[2]) : null
		);
	}
	$res = null;
}

#Szablon
$content->file[] = 'comments';
$content->data['comment'] = $comm;

#Mo¿e komentowaæ?
if(LOGD==1 || $cfg['commGuest']==1)
{
	$content->data['url'] = '?co=comment&amp;id='.$id.'&amp;type='.CT;
}
else
{
	$content->data['url'] = false;
}