<?php /* Wyœwietl komentarze */
if(iCMS!=1 OR CT=='CT') return;

#Akcje POST - dodaj, akceptuj komentarz
if($_POST) include './mod/comment.php';

#Podzia³ na strony?
if($cfg['commNum']!=0)
{
	#Strona
	if(isset($_GET['page']) && $_GET['page']>1)
	{
		$PAGE = $_GET['page'];
		$ST = ($PAGE-1)*$cfg['commNum'];
	}
	else
	{
		$PAGE = 1;
		$ST = 0;
	}
	$TOTAL = db_count('comms WHERE TYPE='.CT.' AND CID='.$id);
	$pages = ($TOTAL > $cfg['commNum']) ? Pages($PAGE,$TOTAL,$cfg['commNum'],'?co='.$_GET['co'].'&amp;id='.$id) : null;
}
else
{
	$TOTAL = null;
	$pages = null;
}

$comm = array();

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
			'date' => genDate($x[5],1),
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
$content->data['comment'] =& $comm;
$content->data['pages'] =& $pages;

#Mo¿e komentowaæ?
if(LOGD==1 || isset($cfg['commGuest']))
{
	if(!isset($_SESSION['post']) OR $_SESSION['post'] < $_SERVER['REQUEST_TIME'])
	{
		$content->data['url'] = '?co=comment&amp;type='.CT.'&amp;id='.$id.'&amp;mod='.$_GET['co'];
		$_SESSION['CV'][CT][$id] = true;
	}
	else
	{
		$content->data['url'] = null;
	}
	$content->data['mustLogin'] = false;
}
else
{
	$content->data['mustLogin'] = true;
}