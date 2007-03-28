<?php
if(iCMS!='E123') exit;
db_read('ID,name','arts','arts','tn',' WHERE access=1 ORDER BY ID DESC LIMIT 0,10');
global $arts;
$ile=count($arts);
if($ile>0)
{
	echo '<ul>';
	for($i=0;$i<$ile;$i++)
	{
		echo '<li><a href="?co=art&amp;id='.$arts[$i][0].'">'.
		((strlen($arts[$i][1])>18)?substr($arts[$i][1],0,18).'...':$arts[$i][1]).'</a></li>';
	}
	echo '</ul>';
}
?>
