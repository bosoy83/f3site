<?php /* Wyœwietl komentarze */
function comments($id, $type=5, $mayPost=true)
{
	global $db,$cfg,$content;

	#Podzia³ na strony?
	if($cfg['commNum'])
	{
		#Strona
		if(isset($_GET['page']) && $_GET['page']>1)
		{
			$page = $_GET['page'];
			$st = ($page-1)*$cfg['commNum'];
		}
		else
		{
			$page = 1;
			$st = 0;
		}
		$total = dbCount('comms WHERE TYPE='.$type.' AND CID='.$id);
		$CP = ($total > $cfg['commNum']) ? pages($page,$total,$cfg['commNum']) : null;
	}
	else
	{
		$total = null;
		$CP = null;
	}

	$comm = array();

	#Prawa do edycji i usuwania
	$mayEdit = admit('CM');
	$mayDel  = $mayEdit || ($type == 10 && $id == UID);
	$comURL  = url('comment/');
	$modURL  = url('moderate/');
	$userURL = url('user/');

	#Pobierz
	if($total !== 0)
	{
		#SQL
		$res = $db->query('SELECT c.ID,c.access,c.name,c.author,c.ip,c.date,c.text,u.login,u.photo
			FROM '.PRE.'comms c LEFT JOIN '.PRE.'users u ON c.author=u.ID AND c.guest!=1
			WHERE c.TYPE='.$type.' AND c.CID='.$id.
			(($mayEdit) ? '' : ' AND c.access=1').
			(($cfg['commSort']==2) ? '' : ' ORDER BY c.ID DESC').
			(($total) ? ' LIMIT '.$st.','.$cfg['commNum'] : ''));

		$res->setFetchMode(3);

		#BBCode?
		if(isset($cfg['bbcode'])) include_once './lib/bbcode.php';

		foreach($res as $x)
		{
			$comm[] = array(
				'text' => nl2br(emots( isset($cfg['bbcode']) ? BBCode($x[6]) : $x[6] )),
				'date' => genDate($x[5],1),
				'title'=> $x[2],
				'user' => $x[7] ? $x[7] : $x[3],
				'ip'   => $mayEdit ? $x[4] : null,
				'edit' => $mayEdit ? $comURL.$x[0] : false,
				'del'  => $mayDel ? $comURL.$x[0] : false,
				'photo' => isset($cfg['commPhoto']) && $x[8] ? $x[8] : false,
				'accept' => $mayEdit && $x[1]!=1 ? $comURL.$x[0] : false,
				'findIP' => $mayEdit ? $modURL.$x[4] : false,
				'profile' => $x[7] ? $userURL.urlencode($x[7]) : false
			);
		}
		$res = null;
	}

	#Szablon
	$content->file[] = 'comments';
	$content->data['comment'] =& $comm;
	$content->data['parts'] =& $CP;

	#Mo¿e komentowaæ?
	if(LOGD==1 || isset($cfg['commGuest']))
	{
		if(!isset($_SESSION['post']) OR $_SESSION['post'] < $_SERVER['REQUEST_TIME'])
		{
			$content->data['url'] = $comURL.$id.'/'.$type;
			$_SESSION['CV'][$type][$id] = true;
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
}