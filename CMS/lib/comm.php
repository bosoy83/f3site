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
	$TOTAL = db_count('comms WHERE TYPE='.CT.' AND CID='.$id);
}
else
{
	$TOTAL = null;
}

$comm = array();
$form = array();

#Prawa do edycji i usuwania
$mayEdit = Admit('CM');
$mayDel  = $mayEdit || (CT == 10 && $id == UID);

#Pobierz
if($TOTAL !== 0)
{
	#SQL
	$res = $db->query('SELECT c.ID,c.access,c.name,c.author,c.ip,c.date,c.text,u.login
		FROM '.PRE.'comms c LEFT JOIN '.PRE.'users u ON c.author=u.ID AND c.guest!=1
		WHERE c.TYPE='.CT.' AND c.CID='.$id.
		(($mayEdit) ? '' : ' AND c.access=1').
		(($cfg['commSort']==2) ? '' : ' ORDER BY c.ID DESC').
		(($cfg['commNum']!=0) ? ' LIMIT '.$ST.','.$cfg['commNum'] : ''));

	$res->setFetchMode(3);

	#BBCode?
	if(isset($cfg['bbcode'])) include_once('./lib/bbcode.php');

	foreach($res as $x)
	{
		$comm[] = array(
			'text' => nl2br(Emots( isset($cfg['bbcode']) ? BBCode($x[6]) : $x[6] )),
			'date' => genDate($x[5]),
			'title'=> $x[2],
			'user' => $x[7] ? $x[7] : $x[7],
			'ip'   => $mayEdit ? $x[4] : null,
			'edit' => $mayEdit ? '?co=comment&amp;id='.$x[0] : false,
			'del'  => $mayDel ? ' ' : false,
			'accept'  => ($mayEdit && $x[1]!=1) ? ' ' : null,
			'profile' => $x[7] ? ((MOD_REWRITE) ? '/user/'.$x[3] : '?co=user&amp;id='.$x[3]) : null
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
	$content->data['js_url'] = 'request.php?co=comment&id='.$id.'&type='.CT;
	$content->data['mustLogin'] = false;
}
else
{
	$content->data['url'] = false;
	$content->data['mustLogin'] = true;
}