<?php
if(iCMS!='E123' || $_mpoll!=1) exit;
if(isset($answ)) unset($answ);
db_read('seq,a,num','answers','answ','tn',' WHERE IDP='.$poll['ID'].' ORDER BY seq');
#Brak?
if(!isset($poll['q']) || !isset($answ[0][0]))
{
 echo $lang['lack'];
}
else
{
 #Dalej
 echo '
 <form action="?mode=poll" method="post">
 <div class="pollq">'.$poll['q'].'</div>';
 $ile=count($answ);
 for($i=0;$i<$ile;$i++)
 {
  echo '
  <div class="answ">
   <input name="u_vote'.(($poll['type']==2)?'['.$answ[$i][0].']" type="checkbox" ':'" value="'.$answ[$i][0].'" type="radio"').' /> '.$answ[$i][1].'
  </div>
  ';
 }
 echo '
 <div class="pollb" align="center">
  <input type="submit" value="OK" />
  <input type="button" value="'.$lang['results'].'" onclick="location=\'?co=poll&amp;id='.$poll['ID'].'\'" />
 </div>
 </form>
 ';
}
unset($poll,$answ);
?>
