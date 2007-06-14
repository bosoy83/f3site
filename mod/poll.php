<?php
if(iCMS!=1 || $_REQUEST['poll']) exit;
if($MenuID)
{
 global $poll,$option,$nlang;
 static $ile;
}

#Pobierz
if(!isset($poll))
{
 #ID
 if($_GET['id'] && $_GET['co']=='poll')
 {
	db_read('*','polls','poll','oa',' WHERE ID='.$_GET['id']);
 }
 #Najnowsza
 else
 {
	db_read('ID,q,ison,type,num','polls','poll','oa',' WHERE access="'.$nlang.'" ORDER BY ID DESC LIMIT 1');
 }
}

#Brak?
if(!$poll)
{
 Info($lang['lack']);
}

else
{
 #Odp.
 if(!isset($option))
 {
	db_read('ID,a,num','answers','option','tn',' WHERE IDP='.$poll['ID'].' ORDER BY seq');
 }
 $ile=count($option);
 
 if(!$MenuID)
 {
	cTable($poll['name'],1);
	echo '<tr><td align="center">';
 }
 
 #Wyniki
 if($poll['ison']==2 || ($poll['ison']==3 && LOGD!=1) || strpos($_COOKIE[$cfg['c'].'polls'],'x'.$poll['ID'].'x')!==false || !$MenuID)
 {
	#Brak g³osów?
  if($poll['num']==0)
	{
	 echo '<center>'.$lang['novotes'].'</center>';
	}
	else
	{
	 for($i=0;$i<$ile;$i++)
	 {
		$pollproc[$i]=round($option[$i][2] / $poll['num'] * 100 ,$cfg['cproc']);
	 }
   require('inc/pollres/'.$cfg[(($MenuID)?'pollr2':'pollr1')].'.php');
  }
 }
 else
 {
  echo '<form action="vote.php?t=poll&amp;id='.$poll['ID'].'" method="post">
	<center>'.$poll['q'].'</center>
	<div style="margin: 5px 0px">';
	for($i=0;$i<$ile;$i++)
	{
	 echo '<input name="u_vote'.(($poll['type']==2)?'['.$option[$i][0].']" type="checkbox" ':'" value="'.$option[$i][0].'" type="radio"').' /> '.$option[$i][1].'<br />';
  }
	echo '</div>
 <div align="center">
  <input type="submit" value="OK" />
  <input type="button" value="'.$lang['results'].'" onclick="location=\'?co=poll&amp;id='.$poll['ID'].'\'" />
 </div>
 </form>';
 }
 if(!$MenuID)
 {
	echo '</td></tr>';
	eTable();
	#Komentarze
  if($cfg['pcomm']==1)
  {
	 $id=$poll['ID'];
   define('CT','15');
   require('inc/comm.php');
  }
 }
}
?>
