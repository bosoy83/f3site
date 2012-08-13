<?php //Poll results
if(iCMS!=1) exit;

#Get a poll from database or cache
if($id)
{
	if(!$poll = $db->query('SELECT * FROM '.PRE.'polls WHERE ID='.$id) -> fetch(2)) return;
}
elseif(file_exists('./cache/poll_'.LANG))
{
	require('./cache/poll_'.LANG);
}
else return;

#Page title and description
$content->title = $poll['name'];
$content->desc  = $poll['q'];
$id = $poll['ID'];

#No votes
if($poll['num'] == 0)
{
	$content->info($lang['novotes'], array(url('polls') => $lang['archive']));
	return 1;
}

#Get answers
if($id)
{
	$option = $db->query('SELECT ID,a,num FROM '.PRE.'answers WHERE IDP='.$id.
	' ORDER BY '.(isset($cfg['pollSort']) ? 'num DESC,' : '').'seq')->fetchAll(3);
}

#How much answers?
$num = count($option);

#Creation date
$poll['date'] = genDate($poll['date'], true);

# %
$item = array();
foreach($option as &$o)
{
	$item[] = array(
		'num'  => $o[2],
		'label' => $o[1],
		'percent' => round($o[2] / $poll['num'] * 100 ,$cfg['pollRound'])
	);
}

#Template
$content->add('poll', array(
	'poll' => &$poll,
	'item' => &$item,
	'archive' => url('polls')
));

#Comments
if(isset($cfg['pollComm']))
{
	include './lib/comm.php';
	comments($id, 15);
}