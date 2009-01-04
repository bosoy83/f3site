<?php
if(iCMSa!=1 || !Admit('Q')) exit;
require LANG_DIR.'poll.php';

#Operacje
if($_POST)
{
	include './mod/polls/poll.php';
	isset($_POST['del']) AND DeletePoll();
	isset($_POST['reset']) AND ResetPoll();
}

#Info
$content->info($lang['ipoll'], array('?a=editPoll'=>$lang['addPoll']));

#Odczyt
$res = $db->query('SELECT ID,name,num,access FROM '.PRE.'polls ORDER BY ID DESC');
$res->setFetchMode(3); //Num

#Lista
$total = 0;
$polls = array();

foreach($res as $x)
{
	$polls[] = array(
		'num'  => ++$total,
		'url'  => '.?co=poll&amp;id='.$x[0],
		'id'   => $x[0],
		'title'  => $x[1],
		'votes'  => $x[2],
		'access' => $x[3]
	);
}

$res = null;
$content->data['polls'] = $polls;
