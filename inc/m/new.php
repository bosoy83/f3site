<?php
if(iCMS!='E123') exit;
db_read('ID,name','arts','arts','tn',' WHERE access=1 ORDER BY ID DESC LIMIT 0,10');
global $arts;
$ile=count($arts);
for($i=0;$i<$ile;$i++)
{
 mlink(((strlen($arts[$i][1])>18)?substr($arts[$i][1],0,18).'...':$arts[$i][1]),'?co=art&amp;id='.$arts[$i][0],0);
}
?>
