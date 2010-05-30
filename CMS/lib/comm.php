<?php /* Show comments */
function comments($id, $type=5, $mayPost=true, $url='')
{
	global $db,$cfg,$content;

	#Page division
	if($cfg['commNum'])
	{
		#Select page
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
		if(!$url)
		{
			$url = url($GLOBALS['URL'][0].'/'.$id);
		}
		$total = dbCount('comms WHERE TYPE='.$type.' AND CID='.$id);
		$CP = ($total > $cfg['commNum']) ? pages($page,$total,$cfg['commNum'],$url) : null;
	}
	else
	{
		$total = null;
		$CP = null;
	}

	$comm = array();

	#May edit or delete
	$mayEdit = admit('CM');
	$mayDel  = $mayEdit || ($type == 10 && $id == UID);
	$comURL  = url('comment/');
	$modURL  = url('moderate/');
	$userURL = url('user/');

	#Get from database
	if($total !== 0)
	{
		$res = $db->query('SELECT c.ID,c.access,c.name,c.author,c.ip,c.date,c.text,u.login,u.photo
			FROM '.PRE.'comms c LEFT JOIN '.PRE.'users u ON c.author=u.ID AND c.guest!=1
			WHERE c.TYPE='.$type.' AND c.CID='.$id.
			(($mayEdit) ? '' : ' AND c.access=1').
			(($cfg['commSort']==2) ? '' : ' ORDER BY c.ID DESC').
			(($total) ? ' LIMIT '.$st.','.$cfg['commNum'] : ''));

		$res->setFetchMode(3);

		#BBCode
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

	#Template
	$content->file[] = 'comments';
	$content->data['comment'] =& $comm;
	$content->data['parts'] =& $CP;

	#Highlight code
	$content->data['color'] = isset($cfg['colorCode']);

	#May comment
	if(UID || isset($cfg['commGuest']))
	{
		if(empty($_SESSION['post']) OR $_SESSION['post'] < $_SERVER['REQUEST_TIME'])
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